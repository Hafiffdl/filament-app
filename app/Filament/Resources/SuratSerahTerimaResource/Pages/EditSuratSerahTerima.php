<?php

namespace App\Filament\Resources\SuratSerahTerimaResource\Pages;

use App\Filament\Resources\SuratSerahTerimaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuratSerahTerima extends EditRecord
{
    protected static string $resource = SuratSerahTerimaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
