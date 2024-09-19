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
        Schema::table('barang_masters', function (Blueprint $table) {
            // Menghapus auto increment ID jika ada
            $table->dropColumn('id');
            
            // Menambahkan nomor_batch sebagai primary key
            $table->string('nomor_batch')->primary();

            // Menambahkan stock
            $table->integer('stock')->default(0); // Add the stock column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_masters', function (Blueprint $table) {
            // Mengembalikan stock
            $table->dropColumn('stock'); // Remove the stock column

            // Mengembalikan nomor_batch menjadi bukan primary key
            $table->dropPrimary(['nomor_batch']); 
            
            // Mengembalikan ID auto-increment
            $table->bigIncrements('id'); 
        });
    }
};

