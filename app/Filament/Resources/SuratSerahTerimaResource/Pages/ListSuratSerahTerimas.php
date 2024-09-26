<?php

namespace App\Filament\Resources\SuratSerahTerimaResource\Pages;

use App\Filament\Resources\SuratSerahTerimaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuratSerahTerimas extends ListRecords
{
    protected static string $resource = SuratSerahTerimaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
