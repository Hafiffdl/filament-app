<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataFaskesResource\Pages;
use App\Filament\Resources\DataFaskesResource\RelationManagers;
use App\Models\DataFaskes;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DataFaskesResource extends Resource
{
    protected static ?string $model = DataFaskes::class;

    protected static ?string $navigationLabel = 'Data Faskes';
    protected static ?string $navigationIcon = 'heroicon-o-home'; // Heroicons valid
    protected static ?string $navigationGroup = 'Faskes & Barang';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_faskes')->required(),
                Forms\Components\TextInput::make('kode_faskes')->required(),
                Forms\Components\Textarea::make('alamat')->required(),
                Forms\Components\TextInput::make('nama_penanggung_jawab')->required(),
                Forms\Components\TextInput::make('nip_penanggung_jawab')->required(),
                Forms\Components\TextInput::make('nama_pengurus_barang')->required(),
                Forms\Components\TextInput::make('nip_pengurus_barang')->required(),

                // Menampilkan relasi ke BarangMaster
                Forms\Components\HasManyRepeater::make('barangMaster')
                    ->relationship('barangMaster')
                    ->schema([

                        // Pilihan barang berdasarkan nomor batch dari BarangMaster
                        Forms\Components\Select::make('nomor_batch')
                            ->label('Pilih Barang (Berdasarkan Nomor Batch)')
                            ->relationship('barangMasters', 'nomor_batch')
                            ->searchable()
                            ->required(),
                        
                        Forms\Components\TextInput::make('nama_barang')->label('Nama Barang')->required(),
                        Forms\Components\TextInput::make('jumlah')->required(),
                        Forms\Components\TextInput::make('jumlah_barang')->label('Jumlah Barang')->required(),
                    ])
                    ->collapsed(),
            ]);
    }

    public function store()
    {
        // Jika ada logic khusus untuk penyimpanan bisa ditambahkan di sini.
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_faskes')->sortable()->searchable(),
                TextColumn::make('kode_faskes')->sortable()->searchable(),
                TextColumn::make('alamat'),
                TextColumn::make('nama_penanggung_jawab'),
                TextColumn::make('nip_penanggung_jawab'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDataFaskes::route('/'),
            'create' => Pages\CreateDataFaskes::route('/create'),
            'edit' => Pages\EditDataFaskes::route('/{record}/edit'),
        ];
    }
}
