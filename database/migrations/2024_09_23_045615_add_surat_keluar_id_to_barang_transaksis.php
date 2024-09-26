<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSuratKeluarIdToBarangTransaksis extends Migration
{
    public function up()
    {
        Schema::table('barang_transaksis', function (Blueprint $table) {
            $table->unsignedBigInteger('surat_keluar_id')->after('id')->nullable();
            $table->foreign('surat_keluar_id')->references('id')->on('surat_keluar')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('barang_transaksis', function (Blueprint $table) {
            $table->dropForeign(['surat_keluar_id']);
            $table->dropColumn('surat_keluar_id');
        });
    }
}

