<?php

namespace App\Filament\Resources\BarangTransaksiResource\Pages;

use App\Filament\Resources\BarangTransaksiResource;
use App\Models\BarangTransaksi;
use Illuminate\Support\Facades\Log; // Import Log facade
use Filament\Resources\Pages\CreateRecord;

class CreateBarangTransaksi extends CreateRecord
{
    protected static string $resource = BarangTransaksiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        foreach ($data['items'] as &$item) {
            if (!isset($item['barang_master_id']) || empty($item['barang_master_id'])) {
                throw new \Exception('barang_master_id tidak ada atau kosong dalam data yang dikirimkan.');
            }

            if (!isset($item['harga_satuan'])) {
                $barangMaster = \App\Models\BarangMaster::find($item['barang_master_id']);
                if ($barangMaster) {
                    $item['harga_satuan'] = $barangMaster->harga_satuan ?? 0;
                } else {
                    throw new \Exception('Barangmaster tidak ditemukan.');
                }
            }

            if (!isset($item['total_harga'])) {
                $item['total_harga'] = $item['harga_satuan'] * $item['jumlah'];
            }
            
            // Log data yang akan dikirim ke create
        Log::info('Data yang dikirim ke create:', [
            'faskes_id' => $data['faskes_id'],
            'barang_master_id' => $item['barang_master_id'],
            'jumlah' => $item['jumlah'],
            'total_harga' => $item['total_harga'],
            'kadaluarsa' => $item['kadaluarsa'],
        ]);
        // Logging item untuk debugging
        Log::info('Item Data sebelum create', ['item' => $item]);
            // Proses insert ke dalam tabel BarangTransaksi
            BarangTransaksi::create ( [
                'faskes_id' => $data['faskes_id'],
                'barang_master_id' => $item['barang_master_id'],
                'jumlah' => $item['jumlah'],
                'total_harga' => $item['total_harga'],
                'kadaluarsa' => $item['kadaluarsa'],
            ]);
        }

        // Logging data yang berhasil disimpan
        Log::info('Data berhasil disimpan', ['data' => $data]);

        return $data;
    }
}

