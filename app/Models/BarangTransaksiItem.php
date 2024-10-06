<?php

namespace App\Models;

use Error;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangTransaksiItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_transaksi_id',
        'barang_master_id',
        'jumlah',
        'harga_satuan',
        'total_harga',
    ];

    public function barangTransaksi()
    {
        return $this->belongsTo(BarangTransaksi::class);
    }

    public function barangMaster()
{
    return $this->belongsTo(BarangMaster::class, 'barang_master_id');
}


    protected static function booted()
    {
        static::saving(function ($item) {
            $barang = $item->barangMaster;
            if ($barang) {
                $item->total_harga = $item->jumlah * $barang->harga_satuan;

                // Ensure stock does not go negative
                if ($barang->stock < $item->jumlah) {
                    throw new Error("Stock tidak cukup untuk transaksi ini.");
                }

                // Update stock
                $barang->stock -= $item->jumlah;
                $barang->save();
            }
        });
    }
}
