<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratRekonBarangTransaksiTable extends Migration
{
    public function up()
    {
        Schema::create('surat_rekon_barang_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_rekon_id')->constrained('surat_rekons')->onDelete('cascade');
            $table->foreignId('barang_transaksi_id')->constrained('barang_transaksis')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('surat_rekon_barang_transaksi');
    }
}
