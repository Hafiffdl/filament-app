<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faskes extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kode_faskes',
        'alamat',
        'nama_penanggung_jawab',
        'nip_penanggung_jawab',
        'nama_pengurus_barang',
        'nip_pengurus_barang',
    ];

    // Relationship to BarangTransaksi
    
    public function barangTransaksis()
    {
        return $this->hasMany(BarangTransaksi::class);
    }

}

