<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratSerahTerimaRsudResource\Pages;
use App\Filament\Resources\SuratSerahTerimaRsudResource\RelationManagers;
use App\Models\SuratSerahTerimaRsud;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuratSerahTerimaRsudResource extends Resource
{
    protected static ?string $model = SuratSerahTerimaRsud::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuratSerahTerimaRsuds::route('/'),
            'create' => Pages\CreateSuratSerahTerimaRsud::route('/create'),
            'edit' => Pages\EditSuratSerahTerimaRsud::route('/{record}/edit'),
        ];
    }
}
