<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AddRoleBendaharaSeederTableSeeder extends Seeder
{
    public function run()
    {
        // Buat role Bendahara jika belum ada
        $role = Role::firstOrCreate(
            ['name' => 'Bendahara', 'guard_name' => 'web']
        );

        $this->command->info('Role ' . $role->name . ' berhasil dibuat/ditemukan.');
    }
}
