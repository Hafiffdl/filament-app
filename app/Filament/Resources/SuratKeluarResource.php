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
use Illuminate\Database\Eloquent\Builder;

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
                ->afterStateUpdated(function ($state, callable $set) {
                    if ($state) {
                        $barangTransaksis = BarangTransaksi::where('faskes_id', $state)->get();
                        $set('barang_transaksi_ids', $barangTransaksis->pluck('id')->toArray());
                        $set('total_harga', $barangTransaksis->sum('total_harga'));
                    }
                }),
            Select::make('barang_transaksi_ids')
                ->label('Barang Transaksi')
                ->options(function (callable $get) {
                    $faskesId = $get('faskes_id');
                    if ($faskesId) {
                        return BarangTransaksi::where('faskes_id', $faskesId)
                            ->get()
                            ->pluck('detail', 'id');
                    }
                    return [];
                })
                ->multiple()
                ->required(),
            TextInput::make('total_harga')
                ->disabled()
                ->label('Total Harga')
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('nomor')->label('Nomor Surat')->sortable(),
            TextColumn::make('tanggal')->date()->label('Tanggal Surat')->sortable(),
            TextColumn::make('spmb_nomor')->label('SPMB')->sortable(),
            TextColumn::make('faskes.nama')->label('Faskes')->sortable(),
            TextColumn::make('barangTransaksis.barangMaster.nama_barang')->label('Nama Barang')->sortable(),
            TextColumn::make('barangTransaksis.barangMaster.nomor_batch')->label('Nomor Batch')->sortable(),
            TextColumn::make('barangTransaksis.kadaluarsa')->label('Kadaluarsa')->sortable(),
            TextColumn::make('barangTransaksis.barangMaster.satuan')->label('Satuan')->sortable(),
            TextColumn::make('barangTransaksis.barangMaster.sumber_dana')->label('Sumber Dana')->sortable(),
            TextColumn::make('barangTransaksis.jumlah')->label('Jumlah')->sortable(),
            TextColumn::make('barangTransaksis.total_harga')->label('Total Harga')->sortable()
        ])
        ->actions([
            Action::make('printSBBK')
                ->label('Print SBBK') // Label untuk Print SBBK
                ->icon('heroicon-o-printer') // Ikon untuk Print SBBK
                ->url(fn (SuratKeluar $record) => route('print.surat-keluar', $record->id)) // Route ke Print SBBK
                ->openUrlInNewTab(), // Membuka di tab baru

            Action::make('printBAST')
                ->label('Print BAST') // Label untuk Print BAST
                ->icon('heroicon-o-printer') // Ikon untuk Print BAST
                ->url(fn (SuratKeluar $record) => route('print.surat-serah-terima', $record->id)) // Route ke Print BAST
                ->openUrlInNewTab() // Membuka di tab baru
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
