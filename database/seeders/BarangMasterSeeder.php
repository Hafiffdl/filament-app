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

        BarangMaster::create([
            'nama_barang' => 'Masker (face mask)',
            'nomor_batch' => '10022086',
            'kadaluarsa' => '2050-01-31',
            'harga_satuan' => 1200,
            'satuan' => 'Buah',
            'sumber_dana' => 'APBN',
        ]);

        
        BarangMaster::create([
            'nama_barang' => 'Povidone (lodine 30 ml)',
            'nomor_batch' => '22052388',
            'kadaluarsa' => '2026-04-30',
            'harga_satuan' => 4551,
            'satuan' => 'Buah',
            'sumber_dana' => 'APBN',
        ]);

        
        BarangMaster::create([
            'nama_barang' => 'Asam Mefenamat 500 mg',
            'nomor_batch' => 'TMECB30271',
            'kadaluarsa' => '2025-03-31',
            'harga_satuan' => 1400,
            'satuan' => 'Buah',
            'sumber_dana' => 'APBN',
        ]);

        BarangMaster::create([
            'nama_barang' => 'Sarung Tangan Steril',
            'nomor_batch' => '23033503',
            'kadaluarsa' => '2028-02-29',
            'harga_satuan' => 6438,
            'satuan' => 'Pasang',
            'sumber_dana' => 'APBN',
        ]);

    }
}

        // Lanjutkan dengan data lain sesuai dengan gambar
        // php artisan db:seed --class=BarangMasterSeeder
