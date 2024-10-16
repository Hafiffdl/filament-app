<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratRekonsTable extends Migration
{
    public function up()
    {
        Schema::create('surat_rekons', function (Blueprint $table) {
            $table->id();
            $table->string('nomor');
            $table->string('spmb_nomor');
            $table->date('tanggal');
            $table->foreignId('faskes_id')->constrained()->onDelete('cascade');
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('surat_rekons');
    }
}
