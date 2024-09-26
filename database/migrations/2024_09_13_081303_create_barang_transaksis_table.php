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
            $table->foreignId('faskes_id')->constrained();
            $table->foreignId('barang_master_id')->constrained()->nullable(false);
            $table->integer('jumlah');
            $table->decimal('total_harga', 10, 2);
            $table->date('kadaluarsa');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_transaksis', function (Blueprint $table) {
            // Revert back to non-nullable if the migration is rolled back
            $table->unsignedBigInteger('barang_master_id')->nullable(false)->change();
        });
        Schema::dropIfExists('barang_transaksis');
    }
};