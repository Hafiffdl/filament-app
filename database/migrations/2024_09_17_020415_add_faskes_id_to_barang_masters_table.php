<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFaskesIdToBarangMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barang_masters', function (Blueprint $table) {
            $table->unsignedBigInteger('faskes_id')->nullable();  // Tambahkan kolom faskes_id
            $table->foreign('faskes_id')->references('id')->on('data_faskes')->onDelete('set null');  // Relasi ke tabel data_faskes
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barang_masters', function (Blueprint $table) {
            $table->dropColumn('id'); // Hapus kolom id jika ada
            $table->string('nomor_batch')->primary(); // Tetapkan nomor_batch sebagai primary key
        });
        
    }
}
