<?php

namespace App\Models;

use Error;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangTransaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'faskes_id', 'barang_master_id', 'jumlah', 'total_harga', 'kadaluarsa', 'harga_satuan', 'tanggal_transaksi'
    ];

    public function faskes()
    {
        return $this->belongsTo(Faskes::class, 'faskes_id');
    }

    public function barangMaster()
    {
        return $this->belongsTo(BarangMaster::class, 'barang_master_id');
    }

    public function barangTransaksis()
    {
        return $this->hasMany(BarangTransaksi::class);
    }

    public function suratKeluar()
    {
        return $this->belongsToMany(SuratKeluar::class, 'surat_keluar_barang_transaksi');
    }

    public function getDetailAttribute()
    {
        return "{$this->barangMaster->nama_barang} - Batch: {$this->barangMaster->nomor_batch}";
    }

    protected static function booted()
    {
        static::saving(function ($transaksi) {
            $barang = $transaksi->barangMaster;
            if ($barang) {
                $transaksi->total_harga = $transaksi->jumlah * $barang->harga_satuan;

                // Ensure stock does not go negative
                if ($barang->stock < $transaksi->jumlah) {
                    throw new Error("Stock tidak cukup untuk transaksi ini.");
                }

                // Update stock
                $barang->stock -= $transaksi->jumlah;
                $barang->save();
            }
        });
    }
}
