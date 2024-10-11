<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('barang_transaksis', function (Blueprint $table) {
        $table->unsignedBigInteger('barang_transaksi_id')->nullable(); // Tambahkan kolom barang_transaksi_id
        $table->foreign('barang_transaksi_id')->references('id')->on('barang_transaksis')->onDelete('cascade'); // Foreign key constraint
    });
}

public function down()
{
    Schema::table('barang_transaksis', function (Blueprint $table) {
        $table->dropForeign(['barang_transaksi_id']);
        $table->dropColumn('barang_transaksi_id');
    });
}

};
