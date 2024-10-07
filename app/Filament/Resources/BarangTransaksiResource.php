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
use Illuminate\Database\Eloquent\Builder; 

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
                ->options(BarangMaster::all()->pluck('nama_barang', 'id')->unique()) // Pastikan opsi barang unik
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
    
                // Pengecekan apakah items tersedia, jika tidak, transaksi tidak ditampilkan
                TextColumn::make('items')
                    ->label('Nama Barang')
                    ->getStateUsing(function ($record) {
                        // Tambahkan pengecekan apakah record memiliki items
                        if (!$record || !$record->items) {
                            return 'Data barang tidak ditemukan'; // Jika tidak ada items, return pesan default
                        }
    
                        $items = $record->items;
                        if ($items->isEmpty()) {
                            return 'Data barang tidak ditemukan'; // Jika items kosong, return pesan default
                        }
    
                        // Jika item tersedia, tampilkan nama barang dan jumlah
                        return $items->map(function ($item) {
                            $barangMaster = $item->barangMaster;
                            if ($barangMaster) {
                                return $barangMaster->nama_barang . ' (Jumlah: ' . $item->jumlah . ')';
                            } else {
                                return 'Barang tidak ditemukan'; // Jika barangMaster tidak ada
                            }
                        })->implode(', ');
                    })
                    ->sortable(),
    
                TextColumn::make('items.barangMaster.nomor_batch')
                    ->label('Nomor Batch')
                    ->sortable(),
    
                TextColumn::make('items.barangMaster.kadaluarsa')
                    ->label('Kadaluarsa')
                    ->sortable(),
    
                TextColumn::make('items.barangMaster.satuan')
                    ->label('Satuan')
                    ->sortable(),
    
                TextColumn::make('items.total_harga')
                    ->label('Total Harga')
                    ->getStateUsing(function ($record) {
                        if (!$record || !$record->items) {
                            return '0'; // Jika tidak ada barang, default 0
                        }
    
                        $items = $record->items;
                        if ($items->isEmpty()) {
                            return '0'; // Jika items kosong, total harga 0
                        }
    
                        // Hitung total harga
                        return $items->sum(function ($item) {
                            $hargaSatuan = $item->barangMaster ? $item->barangMaster->harga_satuan : 0;
                            return $hargaSatuan * $item->jumlah;
                        });
                    })
                    ->formatStateUsing(fn ($state) => number_format(floatval($state), 2, ',', '.')),
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
}
