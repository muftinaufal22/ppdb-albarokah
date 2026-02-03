<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('struktur_organisasi', function (Blueprint $table) {
            $table->id();
            // Menjadi tidak nullable, karena record ini hanya ada jika ada foto
            $table->string('foto'); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('struktur_organisasi');
    }
};