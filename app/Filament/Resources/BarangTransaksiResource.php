<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangTransaksiResource\Pages;
use App\Models\BarangMaster;
use App\Models\BarangTransaksi;
use App\Models\Faskes;
use Exception;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class BarangTransaksiResource extends Resource
{
    protected static ?string $model = BarangTransaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $pluralModelLabel = 'Transaksi Alokon';

    protected static ?string $modelLabel = 'Transaksi Alokon';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('faskes_id')
                    ->relationship('faskes', 'nama')
                    ->options(Faskes::pluck('nama', 'id'))
                    ->label('Faskes')
                    ->required()
                    ->searchable(),

                DatePicker::make('tanggal_transaksi')
                    ->label('Tanggal SBBK')
                    ->required(),

                Repeater::make('items')
                    ->label('Transaksi Barang')
                    ->relationship('items')
                    ->schema([
                        Select::make('barang_master_id')
                            ->relationship('barangMaster', 'nama_barang')
                            ->label('Nama Barang')
                            ->options(function () {
                                return BarangMaster::all()->mapWithKeys(function ($barang) {
                                    return [$barang->id => $barang->nama_barang . ' - ' . $barang->nomor_batch];
                                });
                            })
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state) {
                                    $barangMaster = BarangMaster::find($state);
                                    if ($barangMaster) {
                                        $set('stock', $barangMaster->stock);
                                        $set('harga_satuan', $barangMaster->harga_satuan);

                                        // Hitung ulang total harga
                                        $jumlah = $get('jumlah') ?? 0;
                                        $set('total_harga', $barangMaster->harga_satuan * $jumlah);
                                    }
                                }
                            })
                            ->afterStateHydrated(function ($component, $state, callable $set) {
                                if ($state) {
                                    $barangMaster = BarangMaster::find($state);
                                    if ($barangMaster) {
                                        $set('stock', $barangMaster->stock);
                                    }
                                }
                            }),

                            TextInput::make('stock')
                            ->label('Stock')
                            ->disabled()
                            ->numeric()
                            ->dehydrated(false),

                        TextInput::make('harga_satuan')
                            ->label('Harga Satuan')
                            ->required()
                            ->numeric()
                            ->disabled()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $jumlah = $get('jumlah') ?? 0;
                                $set('total_harga', $state * $jumlah);
                            }),

                        TextInput::make('jumlah')
                            ->label('Jumlah')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $hargaSatuan = $get('harga_satuan') ?? 0;
                                $set('total_harga', $hargaSatuan * $state);

                                $hargaSatuan = $get('harga_satuan');
                                $barangMaster = BarangMaster::find($get('barang_master_id'));

                                // Cek stok barang saat jumlah diubah
                                if ($barangMaster && $barangMaster->stock < $state) {
                                    Notification::make()
                                        ->title('Stok Barang Tidak Cukup')
                                        ->danger()
                                        ->body("Stok barang '{$barangMaster->nama_barang}' tidak cukup. Stok saat ini: {$barangMaster->stock}.")
                                        ->send();
                                }

                                $set('total_harga', $hargaSatuan * $state);
                            }),

                        TextInput::make('total_harga')
                            ->label('Total Harga')
                            ->disabled()
                            ->numeric()
                            ->formatStateUsing(function ($state) {
                                return number_format($state, 2, ',', '.');
                            })
                            ->dehydrated(true),
                    ])
                    ->minItems(1)
                    ->defaultItems(1)
                    ->createItemButtonLabel('Tambah Barang')
            ]);
    }

    public static function beforeCreate($data)
    {
        // Loop through all items in the transaction and check stock
        DB::beginTransaction();  // Mulai transaksi database
        try {
            foreach ($data['items'] as $item) {
                $barangMaster = BarangMaster::find($item['barang_master_id']);
                if ($barangMaster && $barangMaster->stock <= 0) {
                    // Tampilkan notifikasi error
                    Notification::make()
                        ->title('Error Stok')
                        ->danger()
                        ->body("Stok barang '{$barangMaster->nama_barang}' habis. Transaksi tidak dapat dilanjutkan.")
                        ->send();

                    throw new Exception("Stok barang '{$barangMaster->nama_barang}' habis.");
                }
            }
            // Jika stok mencukupi, commit transaksi database
            DB::commit();
        } catch (Exception $e) {
            // Jika ada error, rollback transaksi dan tampilkan error
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('faskes.nama')
                    ->label('Faskes')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tanggal_transaksi')
                    ->label('Tanggal Transaksi')
                    ->date()
                    ->sortable(),

                TextColumn::make('items.barangMaster.nama_barang')
                    ->label('Nama Barang')
                    ->getStateUsing(function ($record) {
                        return $record->items->map(function ($item, $index) {
                            return ($index + 1) . '. ' . ($item->barangMaster->nama_barang ?? 'N/A');
                        })->implode('<br>');
                    })
                    ->html(),

                TextColumn::make('items.barangMaster.nomor_batch')
                    ->label('Nomor Batch')
                    ->getStateUsing(function ($record) {
                        return $record->items->map(function ($item, $index) {
                            return ($index + 1) . '. ' . ($item->barangMaster->nomor_batch ?? 'N/A');
                        })->implode('<br>');
                    })
                    ->html(),

                TextColumn::make('items.barangMaster.kadaluarsa')
                    ->label('Kadaluarsa')
                    ->getStateUsing(function ($record) {
                        // Force refresh the relationship to get updated data
                        $record->load('items.barangMaster');
                        return $record->items->map(function ($item, $index) {
                            $kadaluarsaDate = $item->barangMaster->kadaluarsa;
                            if ($kadaluarsaDate instanceof \DateTime) {
                                return ($index + 1) . '. ' . $kadaluarsaDate->format('d-m-Y');
                            } elseif (is_string($kadaluarsaDate)) {
                                return ($index + 1) . '. ' . $kadaluarsaDate;
                            }
                            return ($index + 1) . '. N/A';
                        })->implode('<br>');
                    })
                    ->html(),

                TextColumn::make('items.jumlah')
                    ->label('Jumlah')
                    ->getStateUsing(function ($record) {
                        return $record->items->map(function ($item, $index) {
                            return ($item->jumlah ?? 'N/A');
                        })->implode('<br>');
                    })
                    ->html(),

                TextColumn::make('items.barangMaster.satuan')
                    ->label('Satuan')
                    ->getStateUsing(function ($record) {
                        return $record->items->map(function ($item, $index) {
                            return ($index + 1) . '. ' . ($item->barangMaster->satuan ?? 'N/A');
                        })->implode('<br>');
                    })
                    ->html(),

                TextColumn::make('items.barangMaster.harga_satuan')
                ->label('Harga Satuan')
                ->getStateUsing(function ($record) {
                    return $record->items->map(function ($item, $index) {
                        return 'Rp ' . number_format($item->harga_satuan, 2, ',', '.');
                    })->implode('<br>');
                })
                ->html(),

                TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->getStateUsing(function ($record) {
                        // Force a fresh query to get the latest data
                        $record->load('items');
                        return 'Rp ' . number_format($record->items->sum('total_harga'), 2, ',', '.');
                    })
                    ->alignEnd()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('items_not_empty')
                    ->label('Transaksi dengan Barang')
                    ->query(fn (Builder $query) => $query->has('items'))
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangTransaksis::route('/'),
            'create' => Pages\CreateBarangTransaksi::route('/create'),
            'edit' => Pages\EditBarangTransaksi::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Transaksi Barang';
    }

    public static function getSlug(): string
    {
        return 'transaksi-alokon';
    }
}
