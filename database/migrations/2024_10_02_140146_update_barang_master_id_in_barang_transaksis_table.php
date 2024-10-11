<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        Schema::table('barang_transaksis', function (Blueprint $table) {
            // Hapus foreign key lama (jika sudah ada) di barang_master_id
            $table->dropForeign(['barang_master_id']);

            // Tambahkan kembali foreign key dengan aturan yang benar
            $table->foreign('barang_master_id')
                ->references('id')->on('barang_masters') // Menghubungkan ke tabel barang_masters
                ->onDelete('cascade')  // Aturan baru untuk menghapus data anak ketika induk dihapus
                ->onUpdate('cascade'); // Perbarui foreign key saat parent diupdate
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
            // Hapus foreign key yang diubah
            $table->dropForeign(['barang_master_id']);

            // Tambahkan kembali foreign key lama jika perlu (aturan sebelumnya)
            $table->foreign('barang_master_id')
                ->references('id')->on('barang_masters')
                ->onDelete('restrict') // Aturan sebelumnya bisa disesuaikan jika diperlukan
                ->onUpdate('cascade');
        });
    }
};
