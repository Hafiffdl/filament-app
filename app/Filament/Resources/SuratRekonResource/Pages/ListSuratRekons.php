<?php

namespace App\Filament\Resources\SuratRekonResource\Pages;

use App\Filament\Resources\SuratRekonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuratRekons extends ListRecords
{
    protected static string $resource = SuratRekonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
