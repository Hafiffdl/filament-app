<?php

namespace App\Models;

use Error;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        // Saat membuat transaksi baru
        static::creating(function ($item) {
            DB::beginTransaction();
            try {
                $barang = $item->barangMaster;

                if (!$barang) {
                    throw new Error("Barang tidak ditemukan");
                }

                // Cek stok cukup
                if ($barang->stock < $item->jumlah) {
                    throw new Error("Stok tidak mencukupi. Stok tersedia: {$barang->stock}");
                }

                // Kurangi stok
                $barang->stock -= $item->jumlah;
                $barang->save();

                // Set total harga
                $item->total_harga = $item->jumlah * $item->harga_satuan;

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });

        // Saat mengupdate transaksi
        static::updating(function ($item) {
            DB::beginTransaction();
            try {
                $barang = $item->barangMaster;

                if (!$barang) {
                    throw new Error("Barang tidak ditemukan");
                }

                // Ambil jumlah lama
                $jumlahLama = $item->getOriginal('jumlah');

                // Kembalikan stok lama
                $barang->stock += $jumlahLama;

                // Cek apakah stok cukup untuk jumlah baru
                if ($barang->stock < $item->jumlah) {
                    throw new Error("Stok tidak mencukupi. Stok tersedia: {$barang->stock}");
                }

                // Kurangi dengan jumlah baru
                $barang->stock -= $item->jumlah;
                $barang->save();

                // Update total harga
                $item->total_harga = $item->jumlah * $item->harga_satuan;

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });

        // Saat menghapus transaksi
        static::deleting(function ($item) {
            DB::beginTransaction();
            try {
                $barang = $item->barangMaster;

                if ($barang) {
                    // Kembalikan stok
                    $barang->stock += $item->jumlah;
                    $barang->save();
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        });
    }
}
