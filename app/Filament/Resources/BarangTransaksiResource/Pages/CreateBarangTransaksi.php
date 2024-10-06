<?php

namespace App\Filament\Resources\BarangTransaksiResource\Pages;

use App\Filament\Resources\BarangTransaksiResource;
use App\Models\BarangTransaksi;
use App\Models\BarangTransaksiItem;
use Filament\Resources\Pages\CreateRecord;
use App\Models\BarangMaster;
use Illuminate\Support\Facades\Log; 

class CreateBarangTransaksi extends CreateRecord
{
    protected static string $resource = BarangTransaksiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
{
    Log::info('Data yang diterima:', $data);

    if (!isset($data['items']) || empty($data['items'])) {
        throw new \Exception("Items tidak ditemukan dalam data yang dikirim.");
    }

    // Proses transaksi utama
    $barangTransaksi = BarangTransaksi::create([
        'faskes_id' => $data['faskes_id'],
        'tanggal_transaksi' => $data['tanggal_transaksi'],
    ]);

    // Proses setiap item di dalam data
    foreach ($data['items'] as $item) {
        BarangTransaksiItem::create([
            'barang_transaksi_id' => $barangTransaksi->id,
            'barang_master_id' => $item['barang_master_id'],
            'jumlah' => $item['jumlah'],
            'harga_satuan' => $item['harga_satuan'],
            'total_harga' => $item['total_harga'],
        ]);
    }

    return $data;
}
}

