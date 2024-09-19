<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToDataFaskesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_faskes', function (Blueprint $table) {
            $table->string('nama_faskes')->nullable(false);
            $table->string('kode_faskes')->nullable(false);
            $table->string('alamat')->nullable(false);
            $table->string('nama_penanggung_jawab')->nullable(false);
            $table->string('nip_penanggung_jawab')->nullable(false);
            $table->string('nama_pengurus_barang');
            $table->string('nip_pengurus_barang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_faskes', function (Blueprint $table) {
            $table->dropColumn([
                'nama_faskes',
                'kode_faskes',
                'alamat',
                'nama_penanggung_jawab',
                'nip_penanggung_jawab',
                'nama_pengurus_barang',
                'nip_pengurus_barang',
            ]);
        });
    }
}
