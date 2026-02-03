<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToSppSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('spp_setting', function (Blueprint $table) {
            // Tambahkan kolom yang hilang
            $table->unsignedBigInteger('kelas_id')->after('amount');
            $table->string('tahun_ajaran', 20)->after('kelas_id');
            $table->enum("bulan", ["januari","februari","maret","april","mei","juni","juli","agustus","september","oktober","november","desember"])->nullable()->after('tahun_ajaran');
            
            // Tambahkan foreign key untuk kelas_id
            $table->foreign('kelas_id')
                ->references('id')
                ->on('kelas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spp_setting', function (Blueprint $table) {
            // Drop foreign key dulu
            $table->dropForeign(['kelas_id']);
            
            // Drop kolom
            $table->dropColumn(['kelas_id', 'tahun_ajaran', 'bulan']);
        });
    }
}