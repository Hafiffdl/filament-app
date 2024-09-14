<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BarangMaster;

class BarangMasterSeeder extends Seeder
{
    public function run()
    {
        BarangMaster::create([
            'nama_barang' => 'Implant',
            'nomor_batch' => '3100035',
            'kadaluarsa' => '2028-03-22',
            'harga_satuan' => 118000,
            'satuan' => 'Set',
            'sumber_dana' => 'APBN',
        ]);

        BarangMaster::create([
            'nama_barang' => 'Syringe, 3 ml (Implant)',
            'nomor_batch' => '01022388',
            'kadaluarsa' => '2028-01-31',
            'harga_satuan' => 1099,
            'satuan' => 'Buah',
            'sumber_dana' => 'APBN',
        ]);
    }
}

        // Lanjutkan dengan data lain sesuai dengan gambar
        // php artisan db:seed --class=BarangMasterSeeder
