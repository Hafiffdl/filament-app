<?php

namespace App\Filament\Resources\BarangTransaksiResource\Pages;

use App\Filament\Resources\BarangTransaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBarangTransaksis extends ListRecords
{
    protected static string $resource = BarangTransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
