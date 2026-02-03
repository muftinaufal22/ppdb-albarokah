<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah user dengan username 'TU' sudah ada
        $user = User::updateOrCreate(
            ['username' => 'TU'], // kolom unik untuk pengecekan
            [
                'name'     => 'Tata Usaha',
                'email'    => 'tatausaha@gmail.com',
                'role'     => 'Admin',
                'status'   => 'Aktif',
                'password' => Hash::make('bismillah'),
            ]
        );

        // Assign role hanya kalau belum punya
        if (!$user->hasRole('Admin')) {
            $user->assignRole('Admin');
        }

        $this->command->info('Data User ' . $user->name . ' berhasil disimpan/diupdate.');
    }
}
