<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangTransaksiResource\Pages;
use App\Models\BarangMaster;
use App\Models\BarangTransaksi;
use App\Models\Faskes;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
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
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $barangMaster = BarangMaster::find($state);
                                if ($barangMaster) {
                                    $set('harga_satuan', $barangMaster->harga_satuan);
                                    $set('stock', $barangMaster->stock);
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
                            ->reactive(),

                        TextInput::make('jumlah')
                            ->label('Jumlah')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $hargaSatuan = $get('harga_satuan');
                                $set('total_harga', $hargaSatuan * $state);
                            }),

                        TextInput::make('total_harga')
                            ->label('Total Harga')
                            ->disabled()
                            ->numeric()
                            ->dehydrated()
                            ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                    ])
                    ->minItems(1)
                    ->defaultItems(1)
                    ->createItemButtonLabel('Tambah Barang'),
            ]);
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
