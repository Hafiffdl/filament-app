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
        Schema::create('data_faskes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_faskes');

        Schema::table('data_faskes', function (Blueprint $table) {
            $table->string('nomor_batch'); // Pastikan ini tipe string
            $table->foreign('nomor_batch')->references('nomor_batch')->on('barang_masters')->onDelete('cascade');
        });
    }
};
