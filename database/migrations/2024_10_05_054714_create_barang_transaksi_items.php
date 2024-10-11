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
        Schema::create('barang_transaksi_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_transaksi_id')->constrained('barang_transaksis')->onDelete('cascade');
            $table->foreignId('barang_master_id')->constrained('barang_masters');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 10, 2);
            $table->decimal('total_harga', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_transaksi_items');
    }
};
