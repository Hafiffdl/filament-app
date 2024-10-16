<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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

    public function suratBasts()
    {
        return $this->belongsToMany(SuratBast::class, 'surat_bast_barang_transaksi', 'barang_transaksi_id', 'surat_bast_id');
    }

    // Scope untuk mengambil data 6 bulan terakhir berdasarkan faskes
    public function scopeByFaskesAndLastSixMonths($query, $faskesId)
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6);
        return $query->where('faskes_id', $faskesId)
                     ->where('tanggal_transaksi', '>=', $sixMonthsAgo);
    }

    public function getDetailAttribute()
    {
        $items = $this->items->map(function ($item) {
            $barangMaster = $item->barangMaster;
            return "{$barangMaster->nama_barang} - Batch: {$barangMaster->nomor_batch} - Jumlah: {$item->jumlah}";
        })->implode(', ');

        return $items ?: 'Barang tidak ditemukan';
    }
}

