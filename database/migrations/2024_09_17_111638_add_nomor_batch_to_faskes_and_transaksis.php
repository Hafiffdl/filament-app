<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menambahkan kolom nomor_batch di tabel data_faskes
        Schema::table('data_faskes', function (Blueprint $table) {
            $table->string('nomor_batch');
            $table->foreign('nomor_batch')
                  ->references('nomor_batch')
                  ->on('barang_masters')
                  ->onDelete('cascade');
        });

        // Menambahkan kolom nomor_batch di tabel barang_transaksis
        Schema::table('barang_transaksis', function (Blueprint $table) {
            $table->string('nomor_batch');
            $table->foreign('nomor_batch')
                  ->references('nomor_batch')
                  ->on('barang_masters')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus kolom nomor_batch dari tabel data_faskes
        Schema::table('data_faskes', function (Blueprint $table) {
            $table->dropForeign(['nomor_batch']);
            $table->dropColumn('nomor_batch');
        });

        // Menghapus kolom nomor_batch dari tabel barang_transaksis
        Schema::table('barang_transaksis', function (Blueprint $table) {
            $table->dropForeign(['nomor_batch']);
            $table->dropColumn('nomor_batch');
        });
    }
};
