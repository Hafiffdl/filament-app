<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpmbTanggalToSuratKeluarTable extends Migration
{
    public function up()
    {
        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->date('spmb_tanggal')->nullable()->after('spmb_nomor');
        });
    }

    public function down()
    {
        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->dropColumn('spmb_tanggal');
        });
    }
}
