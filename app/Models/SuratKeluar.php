<?php

namespace App\Models;

use Carbon\Carbon;
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
        'kode_faskes',
        'spmb_nomor',
        'nama', 'alamat', 'nama_penanggung_jawab', 'nip_penanggung_jawab', 'nama_pengurus_barang', 'nip_pengurus_barang'

    ];
    protected $casts = [
        'tanggal' => 'date',
    ];

    protected $dates = ['kadaluarsa'];

    public function faskes()
    {
        return $this->belongsTo(Faskes::class, 'faskes_id');
    }

    public function barangTransaksis()
    {
        return $this->belongsToMany(BarangTransaksi::class, 'surat_keluar_barang_transaksi', 'surat_keluar_id', 'barang_transaksi_id');
    }

    public function scopeByFaskesAndLastSixMonths($query, $faskesId)
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6);
        return $query->where('faskes_id', $faskesId)
                     ->where('tanggal', '>=', $sixMonthsAgo);
    }

}
