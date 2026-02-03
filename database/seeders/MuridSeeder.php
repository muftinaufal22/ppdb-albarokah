<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\dataMurid;
use App\Models\DataOrangTua;
use App\Models\BerkasMurid;

class MuridSeeder extends Seeder
{
    public function run(): void
    {
        // $murid = User::create([
        //     'name' => 'Ahmad Setiawan',
        //     'username' => 'ahmad.setiawan',
        //     'email' => 'ahmad@example.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'Murid',
        //     'status' => 'Aktif',
        //     'foto_profile' => null,
        //     'kelas_id' => 1, // Sesuaikan dengan ID kelas yang valid
        // ]);

        // dataMurid::create([
        //     'user_id' => $murid->id,
        //     'jenis_kelamin' => 'L',
        //     'whatsapp' => '081234567890',
        //     'nis' => 'NIS2993', 
        //     'nisn' => 'NISN6786', 
        //     'tempat_lahir' => 'Jakarta',
        //     'tgl_lahir' => '2010-05-15',
        //     'agama' => 'Islam',
        //     'alamat' => 'Jl. Pendidikan No. 123',
        // ]);

        // DataOrangTua::create([
        //     'user_id' => $murid->id,
        //     'nama_ayah' => 'Budi Setiawan',
        //     'pendidikan_ayah' => 'S1',
        //     'pekerjaan_ayah' => 'ASN',
        //     'alamat_ayah' => 'Jl. Pendidikan No. 123',
        //     'nama_ibu' => 'Siti Aminah',
        //     'pendidikan_ibu' => 'SMA/SMK',
        //     'pekerjaan_ibu' => 'Ibu Rumah Tangga',
        //     'alamat_ibu' => 'Jl. Pendidikan No. 123',
        // ]);

        // BerkasMurid::create([
        //     'user_id' => $murid->id,
        //     'kartu_keluarga' => null,
        //     'akte_kelahiran' => null,
        //     'ktp' => null,
        //     'foto' => null,
        // ]);
    }
}