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
            $table->unsignedBigInteger('surat_keluar_id')->after('id')->nullable();
            $table->foreign('surat_keluar_id')->references('id')->on('surat_keluar')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->dropForeign(['surat_keluar_id']);
            $table->dropColumn('surat_keluar_id');
        });
    }
};
