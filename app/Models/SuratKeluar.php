<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $table = 'surat_keluar';
    protected $fillable = [
        'nomor',
        'tanggal',
        'faskes_id',
        'barang_master_id',
        'jumlah',
        'total_harga',
        'kadaluarsa',
        'harga_satuan',
        'tanggal_transaksi',
        'kode_faskes'

    ];
    protected $casts = [
        'tanggal' => 'date',
    ];

    protected $dates = ['kadaluarsa'];

    public function faskes()
    {
        return $this->belongsTo(Faskes::class,'faskes_id');
    }

    public function barangTransaksis()
    {
        return $this->belongsToMany(BarangTransaksi::class, 'surat_keluar_barang_transaksi');
    }

}
