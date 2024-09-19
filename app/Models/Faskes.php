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
        'alamat'
    ];

    // Relationship to BarangTransaksi
    public function barangTransaksis()
    {
        return $this->hasMany(BarangTransaksi::class);
    }
}

