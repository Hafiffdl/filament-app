<?php

namespace App\Filament\Resources\SuratBastResource\Pages;

use App\Filament\Resources\SuratBastResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuratBasts extends ListRecords
{
    protected static string $resource = SuratBastResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
