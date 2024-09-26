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
        Schema::create('barang_transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faskes_id')->nullable()->constrained('faskes')->onDelete('cascade');
            $table->foreignId('barang_master_id')->constrained('barang_masters')->onDelete('cascade'); // Ensure 'barang_masters' table exists
            $table->integer('jumlah');
            $table->decimal('total_harga', 10, 2);
            $table->date('kadaluarsa');
            $table->date('tanggal_transaksi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_transaksis');
    }
};

