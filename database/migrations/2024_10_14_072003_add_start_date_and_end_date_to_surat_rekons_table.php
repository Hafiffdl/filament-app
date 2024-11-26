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
        Schema::table('surat_rekons', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('faskes_id');
            $table->date('end_date')->nullable()->after('start_date');
        });
    }

    public function down()
    {
        Schema::table('surat_rekons', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};
