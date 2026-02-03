<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('deskripsi');
            $table->enum('kategori', [
                'Akademik', 
                'Non_akademik', 
               
                
            ]);
            $table->enum('tingkat', [
                'Sekolah',
                'Kecamatan', 
                'Kabupaten', 
                'Provinsi', 
                'Nasional', 
                'Internasional'
            ]);
            $table->string('peraih');
            $table->string('penyelenggara')->nullable();
            $table->date('tanggal_prestasi');
            $table->string('gambar')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->bigInteger('views')->default(0);
            $table->timestamps();

            // Indexes for better performance
            $table->index(['status', 'tanggal_prestasi']);
            $table->index(['kategori', 'status']);
            $table->index(['tingkat', 'status']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prestasis');
    }
};