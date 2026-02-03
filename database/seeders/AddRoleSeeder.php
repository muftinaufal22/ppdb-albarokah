<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AddRoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Perpustakaan',
            'PPDB',
            'Bendahara',
            // Tambahkan role lain di sini kalau ada
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role, 'guard_name' => 'web']
            );
        }
    }
}
