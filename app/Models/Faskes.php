<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faskes extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'kode_faskes', 'alamat'];

    public function barangTransaksis()
    {
        return $this->hasMany(BarangTransaksi::class);
    }

    public function suratKeluar()
    {
        return $this->hasMany(SuratKeluar::class);
    }
}

