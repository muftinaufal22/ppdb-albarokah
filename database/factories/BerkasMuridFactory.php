<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BerkasMurid;
use App\Models\User;

class BerkasMuridFactory extends Factory
{
    /**
     * Tentukan model yang terkait dengan factory.
     */
    protected $model = BerkasMurid::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // Asumsi user_id akan dibuat otomatis atau disediakan saat create
            'user_id' => User::factory(), 
            'kartu_keluarga' => null,
            'akte_kelahiran' => null,
            'ktp'            => null,
            'foto'           => null,
        ];
    }
}