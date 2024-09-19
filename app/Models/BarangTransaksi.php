<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangTransaksi extends Model
{
    use HasFactory;

    protected $fillable = ['barang_master_id', 'jumlah', 'total_harga', 'kadaluarsa'];

   
    public function barangMaster()
    {
        return $this->belongsTo(BarangMaster::class, 'nomor_batch', 'nomor_batch');
    }

    // Relasi ke DataFaskes
    public function faskes()
    {
        return $this->belongsTo(DataFaskes::class, 'faskes_id');  // sesuaikan kolom foreign key
    }

    protected static function booted()
    {
        static::saving(function ($transaksi) {
            $barang = $transaksi->barangMaster;
            if ($barang) {
                $transaksi->total_harga = $transaksi->jumlah * $barang->harga_satuan;

                // Ensure stock does not go negative
                if ($barang->stock < $transaksi->jumlah) {
                    throw new \Exception("Stock tidak cukup untuk transaksi ini.");
                }

                // Update stock
                $barang->stock -= $transaksi->jumlah;
                $barang->save();
            }
        });
    }
}
