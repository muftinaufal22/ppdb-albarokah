<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role; // Digunakan untuk memastikan Role ada

class LoginFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Pastikan role yang dibutuhkan ada di database tes
        Role::findOrCreate('Admin');
        Role::findOrCreate('Murid');
        Role::findOrCreate('Guest');
    }

    #[Test]
    public function test_user_aktif_can_login_with_valid_credentials()
    {
        // 1. Arrange
        $user = User::factory()->create([
            'email'    => 'user@aktif.com',
            'password' => bcrypt('password123'),
            'status'   => 'Aktif',
        ]);
        $user->assignRole('Murid'); // Tetapkan role agar sesuai logika aplikasi

        // 2. Act
        $response = $this->post(route('login'), [
            'email'    => 'user@aktif.com',
            'password' => 'password123',
        ]);

        // 3. Assert
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    #[Test]
    public function test_user_cannot_login_with_invalid_password()
    {
        // 1. Arrange
        $user = User::factory()->create([
            'email'    => 'user@aktif.com',
            'password' => bcrypt('password123'),
        ]);

        // 2. Act
        $response = $this->post(route('login'), [
            'email'    => 'user@aktif.com',
            'password' => 'password-SALAH',
        ]);

        // 3. Assert
        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    #[Test]
    public function test_user_tidak_aktif_cannot_login()
    {
        // 1. Arrange
        // Kita gunakan state 'tidakAktif' dari UserFactory yang sudah kita perbaiki
        $user = User::factory()->create([
            'email'    => 'user@tidakaktif.com',
            'password' => bcrypt('password123'),
            'status'   => 'Tidak Aktif', // Status sengaja dibuat non-aktif
        ]);

        // 2. Act
        $response = $this->post(route('login'), [
            'email'    => 'user@tidakaktif.com',
            'password' => 'password123',
        ]);

        // 3. Assert
        $this->assertGuest();
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Akun yang kamu gunakan sudah Tidak Aktif !');
    }
}