<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratBastResource\Pages;
use App\Models\BarangTransaksi;
use App\Models\Faskes;
use App\Models\SuratBast;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables;

class SuratBastResource extends Resource
{
    protected static ?string $model = SuratBast::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $pluralModelLabel = 'Surat BAST';

    protected static ?string $modelLabel = 'Surat BAST';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nomor')
            ->required()
            ->label('Nomor Surat BAST')
            ->rule('regex:/^[a-zA-Z0-9\/\.\-\:\s]+$/'),
            DatePicker::make('tanggal')
                ->required()
                ->label('Tanggal Surat BAST'),
            DatePicker::make('tanggal_transaksi')
                ->required()
                ->label('Tanggal Transaksi'),
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
                // ->afterStateUpdated(function ($state, callable $set) {
                //     $totalHarga = BarangTransaksi::whereIn('id', $state)
                //         ->with('items.barangMaster')
                //         ->get()
                //         ->sum(function ($transaksi) {
                //             return $transaksi->items->sum(function ($item) {
                //                 return $item->jumlah * $item->barangMaster->harga_satuan;
                //             });
                //         });
                //     $set('total_harga', $totalHarga);
                // }),
        ]);
    }
    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('nomor')
                ->label('Nomor Surat')
                ->sortable()
                ->searchable()
                ->getStateUsing(fn ($record) => $record->nomor),
            TextColumn::make('tanggal')
                ->label('Tanggal Surat')
                ->date()
                ->sortable(),
            TextColumn::make('faskes.nama')
                ->label('Faskes')
                ->sortable()
                ->searchable(),
                TextColumn::make('barangTransaksis.tanggal_transaksi')
                ->label('Tanggal Transaksi')
                ->getStateUsing(function ($record) {
                    return $record->barangTransaksis->map(function ($transaksi) {
                        $transactionDate = $transaksi->tanggal_transaksi;

                        if ($transactionDate instanceof \DateTime) {
                            return $transactionDate->format('d-m-Y');
                        } elseif (is_string($transactionDate)) {
                            return $transactionDate;
                        }

                        return 'N/A';  // Fallback if no valid date is found
                    })->implode("\n");  // Use line breaks to separate multiple values
                })
                ->extraAttributes(['style' => 'white-space: pre-line;'])  // Ensure line breaks are displayed
                ->tooltip(function ($record) {
                    return $record->barangTransaksis->map(function ($transaksi) {
                        $transactionDate = $transaksi->tanggal_transaksi;

                        if ($transactionDate instanceof \DateTime) {
                            return $transactionDate->format('d-m-Y');
                        } elseif (is_string($transactionDate)) {
                            return $transactionDate;
                        }

                        return 'N/A';
                    })->implode(', ');  // Tooltip will show dates separated by commas
                })
                ->sortable()
                ->date(),
            TextColumn::make('barangTransaksis.items.barangMaster.nama_barang')
                ->label('Nama Barang')
                ->getStateUsing(function ($record) {
                    $namaBarang = $record->barangTransaksis->flatMap(function ($transaksi) {
                        return $transaksi->items->map(function ($item) {
                            return $item->barangMaster->nama_barang;
                        });
                    })->unique()->take(3);

                    $displayText = $namaBarang->implode(', ');
                    $fullText = $record->barangTransaksis->flatMap(function ($transaksi) {
                        return $transaksi->items->map(function ($item) {
                            return $item->barangMaster->nama_barang;
                        });
                    })->unique()->implode(', ');

                    return $namaBarang->count() > 3 ? $displayText . '...' : $displayText;
                })
                ->tooltip(function ($record) {
                    return $record->barangTransaksis->flatMap(function ($transaksi) {
                        return $transaksi->items->map(function ($item) {
                            return $item->barangMaster->nama_barang;
                        });
                    })->unique()->implode(', ');
                })
                ->wrap(),
            TextColumn::make('barangTransaksis.items.barangMaster.nomor_batch')
                ->label('Nomor Batch')
                ->getStateUsing(function ($record) {
                    $nomorBatch = $record->barangTransaksis->flatMap(function ($transaksi) {
                        return $transaksi->items->map(function ($item) {
                            return $item->barangMaster->nomor_batch;
                        });
                    })->unique()->take(3);

                    $displayText = $nomorBatch->implode(', ');
                    return $nomorBatch->count() > 3 ? $displayText . '...' : $displayText;
                })
                ->tooltip(function ($record) {
                    return $record->barangTransaksis->flatMap(function ($transaksi) {
                        return $transaksi->items->map(function ($item) {
                            return $item->barangMaster->nomor_batch;
                        });
                    })->unique()->implode(', ');
                })
                ->wrap(),
            TextColumn::make('barangTransaksis.items.jumlah')
                ->label('Jumlah')
                ->getStateUsing(function ($record) {
                    return $record->barangTransaksis->flatMap(function ($transaksi) {
                        return $transaksi->items->map(function ($item) {
                            return $item->jumlah;
                        });
                    })->sum();
                }),
                TextColumn::make('barangTransaksis.items.barangMaster.harga_satuan')
                ->label('Harga Satuan')
                ->getStateUsing(function ($record) {
                    $hargaSatuanList = $record->barangTransaksis->flatMap(function ($transaksi) {
                        return $transaksi->items->map(function ($item) {
                            return $item->barangMaster->harga_satuan;
                        });
                    })->unique()->sort()->take(2);

                    $displayText = $hargaSatuanList->map(function ($harga) {
                        return 'Rp. ' . number_format($harga, 2, ',', '.');
                    })->implode(', ');

                    return $hargaSatuanList->count() > 2 ? $displayText . '...' : $displayText;
                })
                ->tooltip(function ($record) {
                    $hargaSatuanList = $record->barangTransaksis->flatMap(function ($transaksi) {
                        return $transaksi->items->map(function ($item) {
                            return $item->barangMaster->harga_satuan;
                        });
                    })->unique()->sort();

                    return $hargaSatuanList->map(function ($harga) {
                        return 'Rp. ' . number_format($harga, 2, ',', '.');
                    })->implode(', ');
                }),

            TextColumn::make('total_harga')
                ->label('Total Harga')
                ->getStateUsing(function ($record) {
                    $totalHarga = $record->barangTransaksis->sum(function ($transaksi) {
                        return $transaksi->items->sum(function ($item) {
                            return $item->jumlah * $item->barangMaster->harga_satuan;
                        });
                    });

                    return 'Rp. ' . number_format($totalHarga, 2, ',', '.');
                }),
            ])
            ->actions([
                Action::make('printBAST')
                    ->label('Print BAST')
                    ->icon('heroicon-o-printer')
                    ->url(fn (SuratBast $record) => route('print.surat-serah-terima', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuratBasts::route('/'),
            'create' => Pages\CreateSuratBast::route('/create'),
            'edit' => Pages\EditSuratBast::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Laporan';
    }

    public static function getSlug(): string
    {
        return 'surat-bast';
    }
}
