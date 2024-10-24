<?php

namespace App\Filament\Resources\SuratRekonResource\Pages;

use App\Filament\Resources\SuratRekonResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSuratRekon extends CreateRecord
{
    protected static string $resource = SuratRekonResource::class;
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
