<?php

namespace App\Filament\Resources\SuratSerahTerimaRsudResource\Pages;

use App\Filament\Resources\SuratSerahTerimaRsudResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuratSerahTerimaRsuds extends ListRecords
{
    protected static string $resource = SuratSerahTerimaRsudResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
