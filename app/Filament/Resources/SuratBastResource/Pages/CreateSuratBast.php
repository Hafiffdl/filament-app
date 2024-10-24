<?php

namespace App\Filament\Resources\SuratBastResource\Pages;

use App\Filament\Resources\SuratBastResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSuratBast extends CreateRecord
{
    protected static string $resource = SuratBastResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $barangTransaksiIds = $data['barang_transaksi_ids'];
        unset($data['barang_transaksi_ids']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $barangTransaksiIds = $this->data['barang_transaksi_ids'];
        $this->record->barangTransaksis()->attach($barangTransaksiIds);
    }
}
