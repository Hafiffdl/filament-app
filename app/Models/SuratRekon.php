<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratRekon extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor', 'spmb_nomor', 'tanggal', 'faskes_id', 'total_harga', 'start_date', 'end_date'
    ];

    public function barangTransaksis()
    {
        return $this->belongsToMany(BarangTransaksi::class, 'surat_rekon_barang_transaksi', 'surat_rekon_id', 'barang_transaksi_id');
    }

    public function suratKeluars()
    {
        return $this->belongsToMany(SuratKeluar::class, 'surat_rekon_surat_keluar', 'surat_rekon_id', 'surat_keluar_id');
    }

    public function faskes()
    {
        return $this->belongsTo(Faskes::class, 'faskes_id');
    }
}
