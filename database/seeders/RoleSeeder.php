<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Admin',
            'Guru',
            'Staf',
            'Murid',
            'Orang Tua',
            'Alumni',
            'Guest',
            'Perpustakaan',
            'PPDB',
            'Bendahara',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role, 'guard_name' => 'web']
            );
        }
    }
}
