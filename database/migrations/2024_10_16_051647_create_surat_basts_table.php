<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('surat_bast', function (Blueprint $table) {
            $table->id();
            $table->string('nomor');  // Nomor Surat BAST
            $table->string('spmb_nomor')->nullable();  // SPMB Nomor
            $table->date('tanggal');  // Tanggal Surat BAST
            $table->timestamps();  // created_at and updated_at
            $table->foreignId('faskes_id')->constrained('faskes')->onDelete('cascade');  // Foreign key to Faskes table
        });
    }

    public function down()
    {
        Schema::dropIfExists('surat_bast');
    }
};
