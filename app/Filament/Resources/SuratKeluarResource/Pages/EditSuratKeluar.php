<?php

namespace App\Filament\Resources\SuratKeluarResource\Pages;

use App\Filament\Resources\SuratKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuratKeluar extends EditRecord
{
    protected static string $resource = SuratKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['barang_transaksi_ids'] = $this->record->barangTransaksis->pluck('id')->toArray();
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $barangTransaksiIds = $data['barang_transaksi_ids'];
        unset($data['barang_transaksi_ids']);

        return $data;
    }

    protected function afterSave(): void
    {
        $barangTransaksiIds = $this->data['barang_transaksi_ids'];
        $this->record->barangTransaksis()->sync($barangTransaksiIds);
    }
}
