<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangMasterResource\Pages;
use App\Models\BarangMaster;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BarangMasterResource extends Resource
{
    protected static ?string $model = BarangMaster::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $pluralModelLabel = 'Data Alokon';
    protected static ?string $modelLabel = 'Data Alokon';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_barang')
                    ->label('Nama Barang')
                    ->required(),

                TextInput::make('nomor_batch')
                    ->label('Nomor Batch')
                    ->required(),

                DatePicker::make('kadaluarsa')
                    ->label('Kadaluarsa')
                    ->required(),

                TextInput::make('harga_satuan')
                ->dehydrateStateUsing(fn ($state) => str_replace(',', '.', str_replace('.', '', $state)))
                ->reactive()
                ->afterStateHydrated(function (TextInput $component, $state) {
                    $component->state(number_format($state, 2, ',', '.'));
                })
                ->label('Harga Satuan')
                ->required(),

                TextInput::make('satuan')
                    ->label('Satuan')
                    ->required(),

                TextInput::make('sumber_dana')
                    ->label('Sumber Dana')
                    ->required(),

                TextInput::make('stock')
                    ->label('Stock')
                    ->numeric() // Ensure the input is numeric
                    ->default(0) // Set a default value
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_barang')->label('Nama Barang')->sortable()->searchable(),
                TextColumn::make('nomor_batch')->label('Nomor Batch')->sortable(),
                TextColumn::make('kadaluarsa')->label('Kadaluarsa')->sortable(),
                TextColumn::make('harga_satuan')->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 2, ',', '.'))->label('Harga Satuan')->sortable(),
                TextColumn::make('satuan')->label('Satuan')->sortable(),
                TextColumn::make('sumber_dana')->label('Sumber Dana')->sortable(),
                TextColumn::make('stock')->label('Stock')->sortable(),
            ])
            ->filters([])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangMasters::route('/'),
            'create' => Pages\CreateBarangMaster::route('/create'),
            'edit' => Pages\EditBarangMaster::route('/{record}/edit'),
        ];
    }

    public static function getSlug(): string
    {
        return 'data-alokon';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Master Data';
    }
}
