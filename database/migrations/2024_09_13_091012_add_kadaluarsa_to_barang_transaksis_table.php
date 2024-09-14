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
        Schema::table('barang_transaksis', function (Blueprint $table) {
            $table->date('kadaluarsa')->nullable()->after('total_harga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_transaksis', function (Blueprint $table) {
            $table->dropColumn('kadaluarsa');
        });
    }
};
