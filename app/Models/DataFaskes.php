<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataFaskes extends Model
{
    use HasFactory;

    protected $table = 'data_faskes';  // pastikan nama tabelnya sesuai dengan migrasi

    // Tambahkan kolom yang diizinkan untuk mass assignment
    protected $fillable = [
        'nama_faskes',
        'kode_faskes',
        'alamat',
        'nama_penanggung_jawab',
        'nip_penanggung_jawab',
        'nama_pengurus_barang', // Kolom wajib diisi
        'nip_pengurus_barang',  // Kolom wajib diisi
    ];

    // Relasi ke BarangMaster
    public function barangMasters()
    {
        return $this->belongsTo(BarangMaster::class, 'nomor_batch', 'nomor_batch'); // Relasi berdasarkan nomor_batch
    }

    // Relasi ke BarangMaster melalui faskes_id
    public function barangMaster()
    {
        return $this->hasMany(BarangMaster::class, 'faskes_id'); // sesuaikan kolom foreign key
    }

    // Relasi ke BarangTransaksi
    public function barangTransaksi()
    {
        return $this->hasMany(BarangTransaksi::class, 'faskes_id'); // sesuaikan kolom foreign key
    }

    public static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        request()->validate([
            'nomor_batch' => 'required|exists:barang_masters,nomor_batch',
        ]);
    });
}

}

