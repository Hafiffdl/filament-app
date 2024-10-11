<?php

namespace App\Filament\Resources\BarangTransaksiResource\Pages;

use App\Filament\Resources\BarangTransaksiResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateBarangTransaksi extends CreateRecord
{
    protected static string $resource = BarangTransaksiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        Log::info('Data yang diterima:', $data);

        if (!isset($data['items']) || empty($data['items'])) {
            // Instead of throwing an exception, we'll set a default empty array
            $data['items'] = [];
            Log::warning('Items tidak ditemukan dalam data yang dikirim. Menggunakan array kosong.');
        }

        // Process the items if they exist
        if (!empty($data['items'])) {
            foreach ($data['items'] as &$item) {
                // Ensure all necessary fields are present
                $item['jumlah'] = $item['jumlah'] ?? 0;
                $item['harga_satuan'] = $item['harga_satuan'] ?? 0;
                $item['total_harga'] = $item['jumlah'] * $item['harga_satuan'];
            }
        }

        return $data;
    }
}
