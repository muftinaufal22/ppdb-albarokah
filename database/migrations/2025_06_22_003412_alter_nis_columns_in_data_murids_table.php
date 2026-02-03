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
    Schema::table('data_murids', function (Blueprint $table) {
        $table->string('nis', 50)->nullable()->change();
        $table->string('nisn', 50)->nullable()->change();
    });
}

public function down()
{
    Schema::table('data_murids', function (Blueprint $table) {
        $table->integer('nis')->nullable()->change();
        $table->integer('nisn')->nullable()->change();
    });
}
};
