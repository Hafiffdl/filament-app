<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratBast extends Model
{
    use HasFactory;

    protected $table = 'surat_bast';  // Define the table name

    protected $fillable = [
        'nomor',
        'tanggal',
        'faskes_id',
        'transaction_date',
        'tanggal_transaksi',
        'barang_master_id',
        'jumlah',
        'total_harga',
        'kadaluarsa',
        'harga_satuan',
        'kode_faskes',
        'spmb_nomor',
        'nama', 'alamat', 'nama_penanggung_jawab', 'nip_penanggung_jawab', 'nama_pengurus_barang', 'nip_pengurus_barang'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'transaction_date' => 'date'
    ];

    protected $dates = ['kadaluarsa'];

    public function faskes()
    {
        return $this->belongsTo(Faskes::class, 'faskes_id');
    }

    public function barangTransaksis()
    {
        return $this->belongsToMany(BarangTransaksi::class, 'surat_bast_barang_transaksi', 'surat_bast_id', 'barang_transaksi_id');
    }
}
