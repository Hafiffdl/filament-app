<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('faskes', function (Blueprint $table) {
            $table->string('nama')->after('id'); // Add 'nama' column
        });
    }

    public function down()
    {
        Schema::table('faskes', function (Blueprint $table) {
            $table->dropColumn('nama');
        });
    }
};
