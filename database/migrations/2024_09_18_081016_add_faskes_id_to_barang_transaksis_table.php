<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('barang_transaksis', function (Blueprint $table) {
            $table->foreignId('faskes_id')->after('id')->constrained('faskes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('barang_transaksis', function (Blueprint $table) {
            $table->dropForeign(['faskes_id']);
            $table->dropColumn('faskes_id');
        });
    }
};
