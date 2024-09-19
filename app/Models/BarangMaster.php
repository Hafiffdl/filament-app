<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMaster extends Model
{
    use HasFactory;

    // Nomor batch menjadi primary key
    protected $primaryKey = 'nomor_batch';
    public $incrementing = false;
    protected $keyType = 'string';

    // Kolom yang bisa diisi
    protected $fillable = [
        'nama_barang', 
        'nomor_batch', 
        'kadaluarsa', 
        'harga_satuan', 
        'satuan', 
        'sumber_dana', 
        'stock'
    ];

    

    // Relasi ke BarangTransaksi
    public function barangTransaksis()
    {
        return $this->hasMany(BarangTransaksi::class);
    }

    // Relasi ke DataFaskes
    public function faskes()
    {
        return $this->belongsTo(DataFaskes::class, 'faskes_id');  // sesuaikan kolom foreign key
    }

    // Update stock value
    public function updateStock(int $amount)
    {
        $this->stock += $amount;
        $this->save();
    }

    // Relasi ke DataFaskes
    public function dataFaskes()
    {
        return $this->hasMany(DataFaskes::class, 'nomor_batch', 'nomor_batch');
    }
}

