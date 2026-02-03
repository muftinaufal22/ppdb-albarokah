<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware; 
use App\Models\BerkasMurid;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;

class BerkasMuridFeatureTest extends TestCase
{
    use RefreshDatabase; // Gunakan DB testing MySQL

    protected function setUp(): void
    {
        parent::setUp();
        // Pastikan role yang dibutuhkan ada di database tes sebelum menjalankan test
        Role::findOrCreate('Guest');
        Role::findOrCreate('Murid'); 
    }

    // Helper untuk membuat User Guest dan record BerkasMurid awal
    private function createGuestUser(): User
    {
        $user = User::factory()->create(['role' => 'Guest']);
        $user->assignRole('Guest'); // Tugaskan role secara eksplisit
        $user->refresh(); 
        
        // Buat record BerkasMurid awal (sesuai logika controller)
        BerkasMurid::factory()->create(['user_id' => $user->id]);
        
        return $user;
    }

    // ------------------------------------------------------------------

    #[Test]
    public function test_guest_user_can_view_berkas_form()
    {
        // 1. Arrange
        $guestUser = $this->createGuestUser();

        // 2. Act
        $response = $this->actingAs($guestUser)->get('/ppdb/form-berkas');

        // 3. Assert
        $response->assertStatus(200); // Pastikan halaman tampil
        $response->assertSee('Berkas Pendaftaran'); 
    }

   #[Test]
public function test_guest_user_can_upload_valid_berkas()
{
    // 1. Setup storage palsu (mock) agar tidak memenuhi disk asli
    Storage::fake('public'); 
    
    // 2. Buat user role Guest dan simulasikan login
    $guestUser = $this->createGuestUser();

    // 3. Siapkan 4 file dummy yang valid (gambar & ukuran sesuai)
    $kartuKeluarga = UploadedFile::fake()->image('kk.jpg', 600, 400)->size(500);
    $akteKelahiran = UploadedFile::fake()->image('akte.png', 600, 400)->size(500);
    $ktp           = UploadedFile::fake()->image('ktp.jpeg', 600, 400)->size(500);
    $foto          = UploadedFile::fake()->image('foto.jpg', 600, 400)->size(500);

    // 4. Act: Kirim request PUT ke endpoint upload membawa file-file tersebut
    $response = $this->actingAs($guestUser)->put('/ppdb/form-berkas/' . $guestUser->id, [
        'kartu_keluarga' => $kartuKeluarga,
        'akte_kelahiran' => $akteKelahiran,
        'ktp'            => $ktp,
        'foto'           => $foto,
    ]);

    // 5. Assert Response: Pastikan redirect ke home dan session sukses
    $response->assertRedirect('/home'); 
    $response->assertSessionHas('success'); 

    // 6. Assert Database: Ambil data user dan pastikan kolom file terisi (tidak null)
    $berkas = BerkasMurid::where('user_id', $guestUser->id)->first();
    $this->assertNotNull($berkas->kartu_keluarga);
    
    // 7. Assert Storage: Pastikan file fisik benar-benar ada di folder public
    Storage::disk('public')->assertExists('images/berkas_murid/' . $berkas->kartu_keluarga);
    Storage::disk('public')->assertExists('images/berkas_murid/' . $berkas->akte_kelahiran);
    Storage::disk('public')->assertExists('images/berkas_murid/' . $berkas->ktp);
    Storage::disk('public')->assertExists('images/berkas_murid/' . $berkas->foto);
}


    #[Test]
   public function test_guest_user_cannot_upload_invalid_file_type()
    {
        // 1. Arrange
        Storage::fake('public');
        $guestUser = $this->createGuestUser();
        
        // UBAH: Buat file palsu yang BUKAN gambar/pdf (misal: file ZIP)
        $fileSalah = UploadedFile::fake()->create('dokumen.zip', 100, 'application/zip'); // <--- FILE INI TIDAK ADA DI DAFTAR MIMES

        // 2. Act
        $response = $this->actingAs($guestUser)->put('/ppdb/form-berkas/' . $guestUser->id, [
            'kartu_keluarga' => $fileSalah, 
            'akte_kelahiran' => UploadedFile::fake()->image('akte.png'), // Sisanya tetap valid
            'ktp'            => UploadedFile::fake()->image('ktp.jpeg'),
            'foto'           => UploadedFile::fake()->image('foto.jpg'),
        ]);

        // 3. Assert
        $response->assertStatus(302); // Redirect back karena validasi gagal
        // Pastikan ada error validasi untuk field 'kartu_keluarga'
        $response->assertSessionHasErrors('kartu_keluarga'); // Assertion harus lulus sekarang
    }

    #[Test]
    public function test_non_guest_user_cannot_access_berkas_form()
    {
        // 1. Arrange
        $muridUser = User::factory()->create();
        $muridUser->assignRole('Murid');

        // 2. Act
        $response = $this->actingAs($muridUser)->get('/ppdb/form-berkas');

        // 3. Assert
        $response->assertStatus(403); // Akses harus ditolak (Forbidden)
    }
}