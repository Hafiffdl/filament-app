<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faskes extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'kode_faskes', 'alamat', 'nama_penanggung_jawab', 'nip_penanggung_jawab', 'nama_pengurus_barang', 'nip_pengurus_barang'];

    // public function barangTransaksis()
    // {
    //     return $this->hasMany(BarangTransaksi::class);
    // }

    public function barangTransaksis(): HasMany
    {
        return $this->hasMany(BarangTransaksi::class);
    }

    public function suratKeluar()
    {
        return $this->hasMany(SuratKeluar::class);
    }
}

