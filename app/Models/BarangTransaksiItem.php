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
        static::creating(function ($item) {
            $barang = $item->barangMaster;
            if ($barang) {
                // Check if stock is 0 or less
                if ($barang->stock <= 0) {
                    throw new Error("Stok barang tidak mencukupi untuk transaksi ini.");
                }

                // Check if requested amount exceeds available stock
                if ($barang->stock < $item->jumlah) {
                    throw new Error("Stok barang tidak cukup untuk transaksi ini.");
                }

                // Calculate total price
                $item->total_harga = $item->jumlah * $barang->harga_satuan;

                // Update stock
                $barang->stock -= $item->jumlah;
                $barang->save();
            }
        });
    }
}
