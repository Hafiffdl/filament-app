<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangTransaksiResource\Pages;
use App\Models\BarangMaster;
use App\Models\BarangTransaksi;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BarangTransaksiResource extends Resource
{
    protected static ?string $model = BarangTransaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('faskes_id')
                    ->relationship('faskes', 'nama')
                    ->label('Faskes')
                    ->required()
                    ->searchable(),

                DatePicker::make('tanggal_transaksi')
                    ->label('Tanggal Transaksi')
                    ->required(),

                // Repeater for multiple item inputs
                Repeater::make('items')
                    ->label('Transaksi Barang')
                    ->relationship('barangTransaksis') // Define your relationship
                    ->schema([
                        Select::make('barang_master_id')
                            ->relationship('barangMaster', 'nama_barang')
                            ->label('Nama Barang')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state) {
                                $barangMaster = BarangMaster::find($state);
                                if ($barangMaster) {
                                    $set('harga_satuan', $barangMaster->harga_satuan);
                                    $set('nomor_batch', $barangMaster->nomor_batch);
                                    $set('sumber_dana', $barangMaster->sumber_dana);
                                    $set('satuan', $barangMaster->satuan);
                                    $set('stock', $barangMaster->stock);
                                    $set('kadaluarsa', $barangMaster->kadaluarsa);
                                }
                            }),

                        TextInput::make('jumlah')
                            ->label('Jumlah')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $hargaSatuan = $get('harga_satuan');
                                $set('total_harga', $state * $hargaSatuan);
                            }),

                        TextInput::make('total_harga')
                            ->label('Total Harga')
                            ->disabled()
                            ->dehydrateStateUsing(fn ($state) => number_format($state, 2, ',', '.')), // Format for display
                    ])
                    ->minItems(1) // Minimum of 1 item
                    ->createItemButtonLabel('Tambah Barang'), // Label for adding new items

                TextInput::make('stock')
                    ->label('Stock')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('faskes.nama')->label('Faskes')->sortable(),
                TextColumn::make('barangMaster.nama_barang')->label('Nama Barang')->sortable(),
                TextColumn::make('barangMaster.nomor_batch')->label('Nomor Batch')->sortable(),
                TextColumn::make('kadaluarsa')->label('Kadaluarsa')->sortable(),
                TextColumn::make('barangMaster.satuan')->label('Satuan')->sortable(),
                TextColumn::make('barangMaster.sumber_dana')->label('Sumber Dana')->sortable(),
                TextColumn::make('jumlah')->label('Jumlah')->sortable(),
                TextColumn::make('tanggal_transaksi')->label('Tanggal Transaksi')->sortable(),
                TextColumn::make('barangMaster.harga_satuan')
                    ->label('Harga Satuan')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')), // Format for display
                TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')), // Format for display
            ])
            ->filters([])
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
}
