<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_keluar_barang_transaksi', function (Blueprint $table) {
            $table->id(); // Tambahkan primary key
            $table->foreignId('surat_keluar_id')->constrained('surat_keluar')->onDelete('cascade');
            $table->foreignId('barang_transaksi_id')->constrained('barang_transaksi')->onDelete('cascade');
            $table->timestamps(); // Tambahkan kolom timestamp
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('surat_keluar_barang_transaksi');
    }
};
