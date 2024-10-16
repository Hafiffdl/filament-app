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
        Schema::table('surat_bast', function (Blueprint $table) {
            $table->date('transaction_date')->nullable()->after('faskes_id');
        });
    }

    public function down()
    {
        Schema::table('surat_bast', function (Blueprint $table) {
            $table->dropColumn(['transaction_date']);
        });
    }
};
