<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PpdbSetting;
use Carbon\Carbon; // Import Carbon

class PpdbSettingFactory extends Factory
{
    protected $model = PpdbSetting::class;

    public function definition(): array
    {
        return [
            'is_active' => true,
            'tanggal_buka' => Carbon::now()->subDays(7)->toDateString(), // Buka 7 hari lalu
            'tanggal_tutup' => Carbon::now()->addDays(7)->toDateString(), // Tutup 7 hari lagi
            'pesan_nonaktif' => 'Pendaftaran ditutup sesuai jadwal.',
        ];
    }

    /** State: PPDB dinonaktifkan secara manual */
    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }

    /** State: Tanggal tutup sudah lewat */
    public function closedByDate(): static
    {
        return $this->state(['tanggal_tutup' => Carbon::yesterday()->toDateString()]);
    }
}