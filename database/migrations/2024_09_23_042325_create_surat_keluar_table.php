<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratKeluarTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('surat_keluar')) {
            Schema::create('surat_keluar', function (Blueprint $table) {
                $table->id();
                $table->foreignId('surat_keluar_id')->constrained()->onDelete('cascade');
                $table->foreignId('barang_transaksi_id')->constrained()->onDelete('cascade');
                $table->string('nomor');
                $table->string('spmb_nomor');
                $table->date('tanggal');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('surat_keluar');
    }
}

