<?php

namespace App\Filament\Resources\BarangMasterResource\Pages;

use App\Filament\Resources\BarangMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBarangMasters extends ListRecords
{
    protected static string $resource = BarangMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
