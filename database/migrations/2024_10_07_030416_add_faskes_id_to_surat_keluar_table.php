<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->unsignedBigInteger('faskes_id')->nullable(); // Add faskes_id column
            $table->foreign('faskes_id')->references('id')->on('faskes')->onDelete('cascade'); // Create foreign key constraint
        });
    }

    public function down()
    {
        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->dropForeign(['faskes_id']);
            $table->dropColumn('faskes_id');
        });
    }

};
