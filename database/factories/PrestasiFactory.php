<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Prestasi;
use Illuminate\Support\Str; // <-- TAMBAHKAN BARIS INI

class PrestasiFactory extends Factory {
    protected $model = Prestasi::class;
    public function definition(): array {
        $judul = $this->faker->sentence(4);
        return [
            'judul'             => $judul,
            'slug'              => Str::slug($judul), // <-- Sekarang ini akan berfungsi
            'deskripsi'         => $this->faker->paragraph(2),
            'kategori'          => $this->faker->randomElement(['Akademik', 'Non_akademik']),
            'tingkat'           => $this->faker->randomElement(['Nasional', 'Provinsi', 'Kabupaten']),
            'peraih'            => $this->faker->name(),
            'penyelenggara'     => $this->faker->company(),
            'tanggal_prestasi'  => $this->faker->date(),
            'gambar'            => null,
            'status'            => 'aktif',
            'views'             => $this->faker->numberBetween(0, 1000),
        ];
    }
}