<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangTransaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'faskes_id',
        'barang_master_id',
        'jumlah',
        'total_harga',
        'kadaluarsa',
        'harga_satuan',
    ];

    // Relasi ke Faskes
    public function faskes()
    {
        return $this->belongsTo(Faskes::class, 'faskes_id');
    }

    // Relasi ke BarangMaster
    public function BarangMaster()
    {
        return $this->belongsTo(BarangMaster::class, 'barang_master_id');
    }

    protected static function booted()
    {
        static::saving(function ($transaksi) {
            $barang = $transaksi->BarangMaster;
            if ($barang) {
                $transaksi->total_harga = $transaksi->jumlah * $barang->harga_satuan;

                // Ensure stock does not go negative
                if ($barang->stock < $transaksi->jumlah) {
                    throw new Exception("Stock tidak cukup untuk transaksi ini.");
                }

                // Update stock
                $barang->stock -= $transaksi->jumlah;
                $barang->save();
            }
        });
    }
}
