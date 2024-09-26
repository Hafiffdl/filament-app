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
                ->searchable()
                ->placeholder('Select an option')
                ->required(),

            Repeater::make('items')
                ->label('Items')
                ->schema([
                    Select::make('barang_master_id')
                    ->relationship('BarangMaster', 'nama_barang') // Menghubungkan dengan relasi BarangMaster
                    ->label('Nama Barang')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        $BarangMaster = BarangMaster::find($state);
                        if ($BarangMaster) {
                            $set('harga_satuan', $BarangMaster->harga_satuan); // Ambil harga_satuan dari BarangMaster
                            $set('nomor_batch', $BarangMaster->nomor_batch);
                            $set('sumber_dana', $BarangMaster->sumber_dana);
                            $set('satuan', $BarangMaster->satuan);
                            $set('stock', $BarangMaster->stock);
                            $set('kadaluarsa', $BarangMaster->kadaluarsa);
                        } else {
                            $set('harga_satuan', 0); // Set harga_satuan ke 0 jika BarangMaster tidak ditemukan
                        }
                    })
                    ->required(), // Pastikan select ini required

                    TextInput::make('harga_satuan')
                    ->label('Harga Satuan')
                    ->numeric()
                    ->disabled() // Field ini tetap disabled, karena ingin menampilkan harga, bukan diedit oleh user
                    ->dehydrateStateUsing(fn ($state) => $state),  // Dehydrate memastikan nilai ini ikut disimpan
                            

                    TextInput::make('nomor_batch')
                        ->label('Nomor Batch')
                        ->disabled(),

                    DatePicker::make('kadaluarsa')
                        ->label('Kadaluarsa')
                        ->required(),

                    TextInput::make('sumber_dana')
                        ->label('Sumber Dana')
                        ->disabled(),

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
                        ->dehydrateStateUsing(fn ($state) => $state),  // pastikan datanya tidak dihapus
                ])
                ->required()
                ->minItems(1) // Set minimum items if needed
                ->maxItems(10) // Set maximum items if needed
                ->addActionLabel('Add Item')

            // Include any additional fields here...
        ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('faskes.nama')->label('Faskes')->sortable(),
                TextColumn::make('BarangMaster.nama_barang')->label('Nama Barang')->sortable(),
                TextColumn::make('BarangMaster.nomor_batch')->label('Nomor Batch')->sortable(),
                TextColumn::make('kadaluarsa')->label('Kadaluarsa')->sortable(),
                TextColumn::make('BarangMaster.satuan')->label('Satuan')->sortable(),
                TextColumn::make('BarangMaster.sumber_dana')->label('Sumber Dana')->sortable(),
                TextColumn::make('jumlah')->label('Jumlah')->sortable(),
                TextColumn::make('BarangMaster.harga_satuan')
                    ->label('Harga Satuan')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state, 2, ',', '.')), // FormatbarangMaster. for display
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