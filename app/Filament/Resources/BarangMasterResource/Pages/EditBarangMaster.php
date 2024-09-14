<?php

namespace App\Filament\Resources\BarangMasterResource\Pages;

use App\Filament\Resources\BarangMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBarangMaster extends EditRecord
{
    protected static string $resource = BarangMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
