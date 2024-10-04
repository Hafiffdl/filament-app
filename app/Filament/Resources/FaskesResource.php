<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaskesResource\Pages;
use App\Models\Faskes;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Tables;

class FaskesResource extends Resource
{
    protected static ?string $model = Faskes::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama')
                ->required()
                ->label('Nama Faskes'),

            Forms\Components\TextInput::make('kode_faskes')
                ->required()
                ->label('Kode Faskes'),

            Forms\Components\TextInput::make('alamat')
                ->required()
                ->label('Alamat')
                ->maxLength(255),

            // Tambahan kolom baru
            Forms\Components\TextInput::make('nama_penanggung_jawab')
                ->required()
                ->label('Nama Penanggung Jawab'),

            Forms\Components\TextInput::make('nip_penanggung_jawab')
                ->required()
                ->label('NIP Penanggung Jawab'),

            Forms\Components\TextInput::make('nama_pengurus_barang')
                ->required()
                ->label('Pengurus Barang'),

            Forms\Components\TextInput::make('nip_pengurus_barang')
                ->required()
                ->label('NIP Pengurus Barang'),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('nama')->label('Nama Faskes')->searchable()->wrap(),
            Tables\Columns\TextColumn::make('kode_faskes')->label('Kode Faskes')->alignment('center'),
            Tables\Columns\TextColumn::make('alamat')->label('Alamat')->limit(40) // Batas karakter, potong jika lebih dari 50 karakterr
            ->wrap() // Membungkus teks agar lebih rapi
            ->extraAttributes(['style' => 'text-overflow: ellipsis; overflow: hidden; white-space: nowrap; max-width: 500c;']), // Atur overflow

            // Tambahan kolom baru
            Tables\Columns\TextColumn::make('nama_penanggung_jawab')->label('Nama Penanggung Jawab'),
            Tables\Columns\TextColumn::make('nip_penanggung_jawab')->label('NIP Penanggung Jawab'),
            Tables\Columns\TextColumn::make('nama_pengurus_barang')->label('Nama Pengurus Barang'),
            Tables\Columns\TextColumn::make('nip_pengurus_barang')->label('NIP Pengurus Barang'),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaskes::route('/'),
            'create' => Pages\CreateFaskes::route('/create'),
            'edit' => Pages\EditFaskes::route('/{record}/edit'),
        ];
    }
}
