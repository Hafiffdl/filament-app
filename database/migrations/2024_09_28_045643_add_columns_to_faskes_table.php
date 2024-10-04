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
        Schema::table('faskes', function (Blueprint $table) {
            // Tambahkan kolom baru di sini
            $table->string('nama_penanggung_jawab')->nullable();
            $table->string('nip_penanggung_jawab')->nullable();
            $table->string('nama_pengurus_barang')->nullable();
            $table->string('nip_pengurus_barang')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faskes', function (Blueprint $table) {
            // Hapus kolom baru saat rollback
            $table->dropColumn('nama_penanggung_jawab');
            $table->dropColumn('nip_penanggung_jawab');
            $table->dropColumn('nama_pengurus_barang');
            $table->dropColumn('nip_pengurus_barang');
        });
    }
};
