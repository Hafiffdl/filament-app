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
                ->maxLength(255), // Set the maximum length if necessary
        ]);
    }
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('nama')->label('Nama Faskes')->searchable(),
            Tables\Columns\TextColumn::make('kode_faskes')->label('Kode Faskes'),
            Tables\Columns\TextColumn::make('alamat')->label('Alamat'),
            // Tambahkan kolom lain sesuai kebutuhan
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->filters([
            // Tambahkan filter jika perlu
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
