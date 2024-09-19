<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMaster extends Model
{
    use HasFactory;

    protected $fillable = ['nama_barang', 'nomor_batch', 'kadaluarsa', 'harga_satuan', 'satuan', 'sumber_dana', 'stock'];

    // Relasi ke BarangTransaksi
    public function barangTransaksis()
    {
        return $this->hasMany(barangTransaksi::class);
    }
}
