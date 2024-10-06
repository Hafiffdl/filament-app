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
use Illuminate\Support\Facades\Log; // Import Log

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

                // Repeater untuk input data beberapa item
                Repeater::make('items')
                    ->label('Transaksi Barang')
                    ->schema([
                        Select::make('barang_master_id')
                            ->options(BarangMaster::all()->pluck('nama_barang', 'id'))
                            ->label('Nama Barang')
                            ->required(),
                        
                        TextInput::make('harga_satuan') // Tambahkan harga_satuan agar terdehidrasi
                            ->label('Harga Satuan')
                            ->disabled()
                            ->dehydrated(), // Pastikan harga_satuan ikut dikirim saat submit form
                        
                        TextInput::make('jumlah')
                            ->label('Jumlah')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $barangMaster = BarangMaster::find($get('barang_master_id'));
                                
                                if ($barangMaster) {
                                    Log::info('BarangMaster ditemukan: ' . $barangMaster->toJson());

                                    $hargaSatuan = $barangMaster->harga_satuan;
                                    $set('harga_satuan', $hargaSatuan); // Set harga_satuan pada field
                                    $set('total_harga', $hargaSatuan * $state); // Hitung total harga
                                } else {
                                    Log::error('BarangMaster tidak ditemukan untuk ID: ' . $get('barang_master_id'));
                                    $set('total_harga', 0);
                                }
                            }),

                        TextInput::make('total_harga')
                            ->label('Total Harga')
                            ->disabled()
                            ->dehydrated()
                            ->dehydrateStateUsing(fn ($state) => number_format($state, 2, ',', '.')),
                    ])
                    ->minItems(1)
                    ->createItemButtonLabel('Tambah Barang'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('faskes.nama')
                    ->label('Faskes')
                    ->sortable(),
                
                // Akses field barangMaster melalui 'items.first'
                TextColumn::make('items.first.barangMaster.nama_barang')
                    ->label('Nama Barang')
                    ->sortable(),
                    
                TextColumn::make('items.first.barangMaster.nomor_batch')
                    ->label('Nomor Batch')
                    ->sortable(),
                    
                TextColumn::make('items.first.barangMaster.kadaluarsa')
                    ->label('Kadaluarsa')
                    ->sortable(),
                    
                TextColumn::make('items.first.barangMaster.satuan')
                    ->label('Satuan')
                    ->sortable(),

                // Pastikan harga_satuan diakses dengan benar
                TextColumn::make('items.first.barangMaster.harga_satuan')
                    ->label('Harga Satuan')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format(floatval($state), 2, ',', '.')), // Konversi string ke float
                    
                TextColumn::make('items.first.total_harga')
                    ->label('Total Harga')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format(floatval($state), 2, ',', '.')), // Konversi string ke float
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
