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
            $table->foreignId('faskes_id')->constrained('faskes'); // Foreign key ke faskes
            $table->date('tanggal_transaksi');
            $table->decimal('total_harga', 10, 2)->nullable();
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

