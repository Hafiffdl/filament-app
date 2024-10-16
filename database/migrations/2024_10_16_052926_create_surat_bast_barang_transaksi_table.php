<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratBastBarangTransaksiTable extends Migration
{
    public function up()
    {
        Schema::create('surat_bast_barang_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_bast_id')->constrained('surat_bast')->onDelete('cascade');  // Foreign key to surat_bast
            $table->foreignId('barang_transaksi_id')->constrained('barang_transaksis')->onDelete('cascade');  // Foreign key to barang_transaksis
            $table->timestamps();  // created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('surat_bast_barang_transaksi');
    }
}
