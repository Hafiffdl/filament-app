<?php

namespace App\Filament\Resources\DataFaskesResource\Pages;

use App\Filament\Resources\DataFaskesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDataFaskes extends ListRecords
{
    protected static string $resource = DataFaskesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
