<?php

namespace App\Filament\Resources\SuratKeluarResource\Pages;

use App\Filament\Resources\SuratKeluarResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSuratKeluar extends CreateRecord
{
    protected static string $resource = SuratKeluarResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['nomor'] = str_replace('/', '-', $data['nomor']);
        $data['spmb_nomor'] = str_replace('/', '-', $data['spmb_nomor']);

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
