<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBarangTransaksisKadaluarsaDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barang_transaksis', function (Blueprint $table) {
            $table->date('kadaluarsa')->nullable()->change(); // Ubah agar bisa null
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barang_transaksis', function (Blueprint $table) {
            $table->date('kadaluarsa')->nullable(false)->change(); // Ubah kembali ke nullable false
        });
    }
}
