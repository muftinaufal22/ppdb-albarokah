<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasswordUpdateTest extends TestCase
{
    use RefreshDatabase; // Otomatis reset database untuk setiap tes

    /**
     * Helper function untuk membuat 1 user baru,
     * memberinya password 'PasswordLama123',
     * dan langsung login sebagai user tersebut.
     */
    private function createUserAndLogin(): User
    {
        $user = User::factory()->create([
            'password' => Hash::make('PasswordLama123') // Password awal
        ]);
        $this->actingAs($user); // Login sebagai user ini
        return $user;
    }

    /**
     * @test
     * Test Case: TC-001 (Sukses)
     * Deskripsi: Mengubah password dengan semua data valid.
     */
    public function test_user_can_update_password_successfully(): void
    {
        $user = $this->createUserAndLogin();

        // GANTI URL '/profile/password/' jika route Anda berbeda
        $response = $this->put('profile-settings/change-password/' . $user->id, [
            'current_password' => 'PasswordLama123',
            'password' => 'PasswordBaru456',
            'password_confirmation' => 'PasswordBaru456',
        ]);

        // Ekspektasi:
        // 1. Harus redirect (status 302)
        $response->assertStatus(302);
        // 2. Harus ada pesan sukses (PERHATIKAN TYPO "diudate" di controller Anda)
        $response->assertSessionHas('success', 'Password Berhasil diupdate !');
        // 3. Cek database, password harus sudah berubah
        $this->assertTrue(Hash::check('PasswordBaru456', $user->fresh()->password));
    }

    /**
     * @test
     * Test Case: TC-002 (Gagal)
     * Deskripsi: Gagal karena 'Password Saat Ini' yang salah.
     */
    public function test_fails_if_current_password_is_incorrect(): void
    {
        $user = $this->createUserAndLogin();

        $response = $this->put('profile-settings/change-password/' . $user->id, [
            'current_password' => 'PasswordLamaSALAH', // Ini datanya salah
            'password' => 'PasswordBaru456',
            'password_confirmation' => 'PasswordBaru456',
        ]);

        // Ekspektasi:
        // 1. Harus kembali ke form
        // 2. Harus ada pesan error khusus untuk field 'current_password'
        $response->assertSessionHasErrors('current_password');
    }

    /**
     * @test
     * Test Case: TC-005 (Gagal)
     * Deskripsi: Gagal karena 'Password Baru' dan 'Konfirmasi' tidak cocok.
     */
    public function test_fails_if_new_password_confirmation_does_not_match(): void
    {
        $user = $this->createUserAndLogin();

        $response = $this->put('profile-settings/change-password/' . $user->id, [
            'current_password' => 'PasswordLama123',
            'password' => 'PasswordBaru456',
            'password_confirmation' => 'PasswordBaruBEDA', // Ini datanya tidak cocok
        ]);

        // Ekspektasi: Harus ada pesan error di field 'password' (aturan 'confirmed')
        $response->assertSessionHasErrors('password');
    }

    /**
     * @test
     * Test Case: TC-004 (Gagal)
     * Deskripsi: Gagal karena 'Password Baru' kurang dari 8 karakter.
     */
    public function test_fails_if_new_password_is_less_than_8_characters(): void
    {
        $user = $this->createUserAndLogin();

        $response = $this->put('profile-settings/change-password/' . $user->id, [
            'current_password' => 'PasswordLama123',
            'password' => 'Baru123', // Kurang dari 8
            'password_confirmation' => 'Baru123',
        ]);

        // Ekspektasi: Harus ada pesan error di field 'password'
        $response->assertSessionHasErrors('password');
    }

    /**
     * @test
     * Test Case: TC-003 (Gagal) -> INI ADALAH BUG ANDA!
     * Deskripsi: Gagal karena 'Password Baru' sama dengan 'Password Lama'.
     */
    public function test_fails_if_new_password_is_same_as_old_password(): void
    {
        $user = $this->createUserAndLogin();

        $response = $this->put('profile-settings/change-password/' . $user->id, [
            'current_password' => 'PasswordLama123',
            'password' => 'PasswordLama123', // Sama dengan password lama
            'password_confirmation' => 'PasswordLama123',
        ]);

        // Ekspektasi: Harusnya ada pesan error di 'password'
        // Tapi karena ini BUG, tes ini akan GAGAL (MERAH)
        $response->assertSessionHasErrors('password');
    }
}