<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratKeluarResource\Pages;
use App\Models\BarangTransaksi;
use App\Models\SuratKeluar;
use App\Models\Faskes;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;

class SuratKeluarResource extends Resource
{
    protected static ?string $model = SuratKeluar::class;
    protected static ?string $navigationIcon = 'heroicon-o-document';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nomor')
                ->required()
                ->label('Nomor Surat'),
            TextInput::make('spmb_nomor')
                ->required()
                ->label('SPMB'),
            DatePicker::make('tanggal')
                ->required()
                ->label('Tanggal Surat'),
            Select::make('faskes_id')
                ->label('Faskes')
                ->options(Faskes::pluck('nama', 'id'))
                ->required()
                ->reactive()
                ->afterStateUpdated(fn (callable $set) => $set('barang_transaksi_ids', [])),
            Select::make('barang_transaksi_ids')
                ->label('Barang Transaksi')
                ->options(function (callable $get) {
                    $faskesId = $get('faskes_id');
                    if (!$faskesId);

                    return BarangTransaksi::where('faskes_id', $faskesId)
                        ->with(['items.barangMaster'])
                        ->get()
                        ->flatMap(function ($transaksi) {
                            return $transaksi->items->map(function ($item) {
                                $barangMaster = $item->barangMaster;
                                $totalHarga = $item->jumlah * $barangMaster->harga_satuan;
                                return [
                                    $item->id => "{$barangMaster->nama_barang} - Batch: {$barangMaster->nomor_batch} - Jumlah: {$item->jumlah} - Harga Satuan: Rp " . number_format($barangMaster->harga_satuan, 2) . " - Total: Rp " . number_format($totalHarga, 2)
                                ];
                            });
                        });
                })
                ->multiple()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $totalHarga = BarangTransaksi::whereHas('items', function ($query) use ($state) {
                        $query->whereIn('id', $state);
                    })->sum('total_harga');
                    $set('total_harga', $totalHarga);
                }),
            TextInput::make('total_harga')
                ->disabled()
                ->label('Total Harga')
                ->reactive()
                ->afterStateHydrated(function (TextInput $component, $state) {
                    $component->state(number_format($state, 2, ',', '.'));
                })
                ->dehydrateStateUsing(fn ($state) => str_replace([',', '.'], ['', '.'], $state)),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('nomor')->label('Nomor Surat')->sortable(),
            TextColumn::make('tanggal')->date()->label('Tanggal Surat')->sortable(),
            TextColumn::make('spmb_nomor')->label('SPMB')->sortable(),
            TextColumn::make('faskes.nama')->label('Faskes')->sortable(),
            TextColumn::make('barangTransaksis.items.barangMaster.nama_barang')->label('Nama Barang')->sortable(),
            TextColumn::make('barangTransaksis.items.barangMaster.nomor_batch')->label('Nomor Batch')->sortable(),
            TextColumn::make('barangTransaksis.items.jumlah')->label('Jumlah')->sortable(),
            TextColumn::make('barangTransaksis.items.barangMaster.harga_satuan')
                ->label('Harga Satuan')
                ->money('idr')
                ->sortable(),
            TextColumn::make('barangTransaksis.items.total_harga')
                ->label('Total Harga')
                ->money('idr')
                ->sortable()
                ->getStateUsing(function ($record) {
                    return $record->barangTransaksis->sum(function ($transaksi) {
                        return $transaksi->items->sum('total_harga');
                    });
                }),
        ])
        ->actions([
            Action::make('printSBBK')
                ->label('Print SBBK')
                ->icon('heroicon-o-printer')
                ->url(fn (SuratKeluar $record) => route('print.surat-keluar', $record->id))
                ->openUrlInNewTab(),

            Action::make('printBAST')
                ->label('Print BAST')
                ->icon('heroicon-o-printer')
                ->url(fn (SuratKeluar $record) => route('print.surat-serah-terima', $record->id))
                ->openUrlInNewTab()
        ])
        ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuratKeluars::route('/'),
            'create' => Pages\CreateSuratKeluar::route('/create'),
            'edit' => Pages\EditSuratKeluar::route('/{record}/edit'),
        ];
    }
}
