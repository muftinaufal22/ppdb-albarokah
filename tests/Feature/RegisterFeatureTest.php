<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\dataMurid;
use App\Models\PpdbSetting;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class RegisterFeatureTest extends TestCase
{
    use RefreshDatabase;

    private array $validData = [
        'name'                  => 'Nama Calon Murid',
        'email'                 => 'murid.baru@example.com',
        'whatsapp'              => '081234567890',
        'password'              => 'PasswordKuat123',
        'confirm_password'      => 'PasswordKuat123',
        'jenis_kelamin'         => 'Laki-laki',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        // Pastikan role 'Guest' dan 'Admin' dibuat di database tes
        Role::findOrCreate('Guest');
        Role::findOrCreate('Admin'); 
    }

    /**
     * Helper untuk membuat PpdbSetting yang benar di database
     */
    private function createPpdbSetting(bool $isActive): void
    {
        PpdbSetting::create([
            'is_active' => $isActive,
            'opening_date' => now()->subDays(1),
            'closing_date' => now()->addDays(30),
            'closed_message' => 'Pendaftaran PPDB saat ini ditutup.',
        ]);
    }

    // =========================================================================
    // TC-001 (SUKSES)
    // =========================================================================
    #[Test]
    public function user_can_register_when_ppdb_is_open(): void
    {
        // 1. Arrange
        $this->createPpdbSetting(true); // Buat PPDB setting yang OPEN
        Role::findOrCreate('Guest'); 

        // 2. Act
        $response = $this->post(route('register.store'), $this->validData);

        // 3. Assert
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', 'Success, Data Berhasil dikirim!');
        
        // ASSERTION UTAMA (Integritas Data):
        $this->assertDatabaseHas('users', [
            'name'      => 'Nama Calon Murid',
            'email'     => 'murid.baru@example.com',
            'username'  => 'Nama',
            'status'    => 'Aktif',
        ]);
        
        $newUser = User::where('email', 'murid.baru@example.com')->first();
        $this->assertNotNull($newUser);
        $this->assertTrue($newUser->hasRole('Guest'));
        
        $this->assertDatabaseHas('data_murids', [
            'user_id'       => $newUser->id,
            'whatsapp'      => '081234567890',
            'jenis_kelamin' => 'Laki-laki',
        ]);
    }

    // =========================================================================
    // TC-014: VALIDASI REQUIRED KHUSUS
    // =========================================================================
    #[Test]
    public function test_registration_fails_if_confirm_password_is_missing_tc014(): void
    {
        // 1. Arrange
        $this->createPpdbSetting(true);
        $invalidData = $this->validData;
        unset($invalidData['confirm_password']); 

        // 2. Act
        $response = $this->post(route('register.store'), $invalidData);

        // 3. Assert
        $response->assertStatus(302); 
        $response->assertSessionHasErrors('confirm_password'); 
    }
    
    // =========================================================================
    // SKENARIO LAIN
    // =========================================================================

    #[Test]
    public function user_cannot_register_when_ppdb_is_closed(): void
    {
        $this->createPpdbSetting(false); // PPDB CLOSED
        
        $response = $this->post(route('register.store'), $this->validData);
        
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('users', ['email' => 'murid.baru@example.com']);
        $this->assertDatabaseCount('data_murids', 0);
    }

    #[Test]
    public function registration_fails_with_invalid_email(): void
    {
        $this->createPpdbSetting(true);
        Role::findOrCreate('Guest');
        
        $invalidData = array_merge($this->validData, ['email' => 'invalid-email']);
        
        $response = $this->post(route('register.store'), $invalidData);
        
        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', ['name' => 'Nama Calon Murid']);
    }

    #[Test]
    public function registration_fails_if_email_already_exists(): void
    {
        $this->createPpdbSetting(true);
        Role::findOrCreate('Guest');
        
        User::factory()->create(['email' => 'murid.baru@example.com']);
        
        $response = $this->post(route('register.store'), $this->validData);
        
        $response->assertSessionHasErrors('email');
        $this->assertEquals(1, User::where('email', 'murid.baru@example.com')->count());
    }

    #[Test]
    public function registration_fails_with_weak_password(): void
    {
        $this->createPpdbSetting(true);
        Role::findOrCreate('Guest');
        
        $weakPasswordData = array_merge($this->validData, [
            'password' => 'lemah',
            'confirm_password' => 'lemah',
        ]);
        
        $response = $this->post(route('register.store'), $weakPasswordData);
        
        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users', ['email' => 'murid.baru@example.com']);
    }

    #[Test]
    public function registration_fails_if_passwords_do_not_match(): void
    {
        $this->createPpdbSetting(true);
        Role::findOrCreate('Guest');
        
        $mismatchData = array_merge($this->validData, [
            'password' => 'PasswordKuat123',
            'confirm_password' => 'PasswordBeda456',
        ]);
        
        $response = $this->post(route('register.store'), $mismatchData);
        
        $response->assertSessionHasErrors('confirm_password');
        $this->assertDatabaseMissing('users', ['email' => 'murid.baru@example.com']);
    }
}