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
use Carbon\Carbon;

class SuratKeluarResource extends Resource
{
    protected static ?string $model = SuratKeluar::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // protected static ?string $modelLabel = 'Surat Keluar SBBK & BAST';
    protected static ?string $pluralModelLabel = 'Surat SBBK';

    protected static ?string $modelLabel = 'Surat SBBK';

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nomor')
            ->required()
            ->label('Nomor Surat SBBK')
            ->rule('regex:/^[a-zA-Z0-9\/\.\-\:\s]+$/')  // Izinkan karakter /, titik, dll.
            ->maxLength(50),

        TextInput::make('spmb_nomor')
            ->required()
            ->label('SPMB')
            ->rule('regex:/^[a-zA-Z0-9\/\.\-\:\s]+$/'),
        DatePicker::make('spmb_tanggal')
            ->required()
            ->label('Tanggal SPMB'),
            DatePicker::make('tanggal_transaksi')
                ->required()
                ->label('Tanggal Transaksi SBBK'),
            Select::make('faskes_id')
                ->label('Faskes')
                ->searchable()
                ->options(Faskes::pluck('nama', 'id'))
                ->required()
                ->reactive()
                ->afterStateUpdated(fn (callable $set) => $set('barang_transaksi_ids', [])),
                Select::make('barang_transaksi_ids')
                ->label('Barang Transaksi')
                ->options(function (callable $get) {
                    $faskesId = $get('faskes_id');
                    $transactionDate = $get('tanggal_transaksi');

                    if (!$faskesId || !$transactionDate) return [];

                    // Mengambil barang transaksi dari faskes dalam rentang tanggal yang ditentukan
                    return BarangTransaksi::where('faskes_id', $faskesId)
                        ->whereDate('tanggal_transaksi',[$transactionDate]) // Filter berdasarkan rentang tanggal
                        ->with(['items.barangMaster']) // Memastikan relasi barang master dimuat
                        ->get()
                        ->pluck('detail', 'id');
                })
                ->multiple()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $totalHarga = BarangTransaksi::whereIn('id', $state)
                        ->with('items.barangMaster')
                        ->get()
                        ->sum(function ($transaksi) {
                            return $transaksi->items->sum(function ($item) {
                                return $item->jumlah * $item->barangMaster->harga_satuan;
                            });
                        });
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
            TextColumn::make('nomor')
                ->label('Nomor Surat SBBK')
                ->sortable()
                ->searchable()
                ->getStateUsing(fn ($record) => $record->nomor),
            TextColumn::make('tanggal_transaksi')
                ->label('Tanggal Transaksi SBBK')
                ->date()
                ->sortable(),
            TextColumn::make('spmb_nomor')
                ->label('SPMB')
                ->sortable()
                ->searchable()
                ->getStateUsing(fn ($record) => $record->spmb_nomor),
            TextColumn::make('spmb_tanggal')
                ->label('Tanggal SPMB')
                ->date()
                ->sortable(),
            TextColumn::make('faskes.nama')
                ->label('Faskes')
                ->sortable()
                ->searchable(),
                TextColumn::make('barangTransaksis.items.barangMaster.nama_barang')
                ->label('Nama Barang')
                ->getStateUsing(function ($record) {
                    $items = $record->barangTransaksis->flatMap(function ($transaksi) {
                        return $transaksi->items->map(function ($item) {
                            return $item->barangMaster->nama_barang;
                        });
                    })->unique()->values();

                    $formattedList = $items->map(function ($item, $index) {
                        return str_pad(($index + 1) . '.', 3, ' ') . ' ' . $item;
                    })->implode('<br>');

                    return $formattedList;
                })
                ->html()
                ->alignLeft(),
                TextColumn::make('barangTransaksis.items.barangMaster.nomor_batch')
                ->label('Nomor Batch')
                ->getStateUsing(function ($record) {
                    $items = $record->barangTransaksis->flatMap(function ($transaksi) {
                        return $transaksi->items->map(function ($item) {
                            return $item->barangMaster->nomor_batch;
                        });
                    })->unique()->values();

                    $formattedList = $items->map(function ($item, $index) {
                        return ($index + 1) . '. ' . $item;
                    })->implode('<br>');

                    return $formattedList;
                })
                ->html()
                ->alignLeft(),
            TextColumn::make('barangTransaksis.items.jumlah')
                ->label('Jumlah')
                ->getStateUsing(function ($record) {
                    return $record->barangTransaksis->flatMap(function ($transaksi) {
                        return $transaksi->items->map(function ($item) {
                            return "<div class='px-2 py-1'>{$item->jumlah}</div>";
                        });
                    })->implode('');
                })
                ->html(),
            TextColumn::make('barangTransaksis.items.barangMaster.harga_satuan')
                ->label('Harga Satuan')
                ->getStateUsing(function ($record) {
                    return $record->barangTransaksis->flatMap(function ($transaksi) {
                        return $transaksi->items->map(function ($item) {
                            $formattedPrice = 'Rp. ' . number_format($item->barangMaster->harga_satuan, 2, ',', '.');
                            return "<div class='px-2 py-1'>{$formattedPrice}</div>";
                        });
                    })->unique()->implode('');
                })
                ->html()
                ->alignRight(),
            TextColumn::make('total_harga')
                ->label('Total Harga')
                ->getStateUsing(function ($record) {
                    $totalHarga = $record->barangTransaksis->sum(function ($transaksi) {
                        return $transaksi->items->sum(function ($item) {
                            return $item->jumlah * $item->barangMaster->harga_satuan;
                        });
                    });
                    return 'Rp. ' . number_format($totalHarga, 2, ',', '.');
                })
                ->alignRight(),
        ])
        ->actions([
            Action::make('printSBBK')
                ->label('Print SBBK')
                ->icon('heroicon-o-printer')
                ->url(fn (SuratKeluar $record) => route('print.surat-keluar', $record->id))
                ->openUrlInNewTab(),
        ])
        ->actions([Tables\Actions\EditAction::make()])
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

    public static function getNavigationGroup(): ?string
    {
        return 'Laporan';
    }

    public static function getSlug(): string
    {
        return 'surat-keluar';
    }
}
