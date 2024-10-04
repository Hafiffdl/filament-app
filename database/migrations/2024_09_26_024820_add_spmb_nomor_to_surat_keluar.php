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
        Schema::table('surat_keluar', function (Blueprint $table) {
            // Menambahkan kolom spmb_nomor ke tabel surat_keluar
            $table->string('spmb_nomor')->nullable()->after('nomor'); // after menentukan posisi setelah kolom 'nomor'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_keluar', function (Blueprint $table) {
            // Menghapus kolom spmb_nomor
            $table->dropColumn('spmb_nomor');
        });
    }
};
