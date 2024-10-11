<?php

namespace App\Models;

use Error;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangTransaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'faskes_id', 'total_harga', 'tanggal_transaksi'
    ];

    public function items()
    {
        return $this->hasMany(BarangTransaksiItem::class, 'barang_transaksi_id');
    }

    public function faskes()
    {
        return $this->belongsTo(Faskes::class, 'faskes_id');
    }

    public function itemsWithMaster()
{
    return $this->hasMany(BarangTransaksiItem::class, 'barang_transaksi_id')
                ->with('barangMaster');
}

public function suratKeluars()
{
    return $this->belongsToMany(SuratKeluar::class, 'surat_keluar_barang_transaksi', 'barang_transaksi_id', 'surat_keluar_id');
}

    public function getDetailAttribute()
    {
        $items = $this->items->map(function ($item) {
            $barangMaster = $item->barangMaster;
            return "{$barangMaster->nama_barang} - Batch: {$barangMaster->nomor_batch} - Jumlah: {$item->jumlah}";
        })->implode(', ');

        return $items ?: 'Barang tidak ditemukan';
    }

    // protected static function booted()
    // {
    //     static::saving(function ($item) {
    //         $barang = $item->barangMaster; // Ambil barangMaster dari BarangTransaksiItem
    //         if ($barang) {
    //             $item->total_harga = $item->jumlah * $barang->harga_satuan; // Hitung total harga berdasarkan jumlah dan harga satuan barang

    //             // Pastikan stok tidak negatif
    //             if ($barang->stock < $item->jumlah) {
    //                 throw new Error("Stock tidak cukup untuk transaksi ini.");
    //             }

    //             // Update stok barang
    //             $barang->stock -= $item->jumlah;
    //             $barang->save(); // Simpan perubahan stok barang
    //         }
    //     });
    // }
}
