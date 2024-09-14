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
            $table->integer('stock')->default(0); // Add the stock column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_masters', function (Blueprint $table) {
            $table->dropColumn('stock'); // Remove the stock column
        });
    }
};
