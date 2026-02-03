<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PasswordResetBrowserTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test: Browser otomatis isi form reset password
     */
    public function test_browser_can_fill_reset_password_form()
    {
        // 1. Buat user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('oldpassword123'),
        ]);

        // 2. Generate token
        $token = Password::createToken($user);

        // 3. Buka browser dan isi form OTOMATIS
        $this->browse(function (Browser $browser) use ($token, $user) {
            $browser->visit('/reset-password/' . $token . '?email=' . $user->email)
                    ->pause(2000) // Tunggu halaman load
                    
                    // Screenshot halaman awal
                    ->screenshot('1-halaman-reset-password')
                    
                    // Isi form dengan selector ID (lebih spesifik)
                    ->type('#password', 'newpassword123')
                    ->type('#password-confirm', 'newpassword123')
                    
                    // Screenshot sebelum submit
                    ->screenshot('2-sebelum-klik-button')
                    
                    // Klik button Reset Password
                    ->press('Reset Password')
                    
                    // Tunggu redirect
                    ->pause(3000)
                    
                    // Screenshot setelah submit
                    ->screenshot('3-setelah-submit')
                    
                    // Cek redirect ke login
                    ->assertPathIs('/login');
        });

        // 4. Verifikasi password berhasil diganti di database
        $this->assertTrue(
            Hash::check('newpassword123', $user->fresh()->password),
            'Password tidak berhasil diupdate di database'
        );
    }

    /**
     * Test: Validasi error ketika password terlalu pendek
     */
    public function test_shows_error_when_password_too_short()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('oldpassword123'),
        ]);

        $token = Password::createToken($user);

        $this->browse(function (Browser $browser) use ($token, $user) {
            $browser->visit('/reset-password/' . $token . '?email=' . $user->email)
                    ->pause(2000)
                    ->type('#password', '123') // Password terlalu pendek
                    ->type('#password-confirm', '123')
                    ->press('Reset Password')
                    ->pause(2000)
                    ->screenshot('4-validation-error')
                    // Cek masih di halaman yang sama (tidak redirect)
                    ->assertPathIs('/reset-password/' . $token);
        });
    }
}