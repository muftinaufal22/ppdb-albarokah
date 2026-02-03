<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware; 
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\StrukturOrganisasi;

class StrukturOrganisasiTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware; 

    protected $user;
    
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->user = User::factory()->create();
    }

    /**
     * 🟢 Skenario Tes TC-001: Sukses Upload
     */
    #[Test]
    public function tc001_admin_can_upload_valid_image()
    {
        $file = UploadedFile::fake()->image('struktur.jpg')->size(450);

        // 2. Aksi: Kirim request POST
        $response = $this->actingAs($this->user)
            ->post(route('backend-struktur-organisasi.store'), [
                'foto' => $file,
            ]);

        // 3. Expected Result:
        $response->assertRedirect(route('backend-struktur-organisasi.index'));
        // UPDATE: Sesuaikan string dengan Controller
        $response->assertSessionHas('success', 'Foto struktur organisasi berhasil ditambahkan (Auto WebP).');
        
        // Pastikan data ada di DB
        $this->assertDatabaseCount('struktur_organisasi', 1);

        // Pastikan file ada di Storage
        $struktur = StrukturOrganisasi::first();
        Storage::disk('public')->assertExists($struktur->foto);
    }

    /**
     * 🔴 Skenario Tes TC-002: Gagal (Ukuran Terlalu Besar > 2MB)
     */
    #[Test]
    public function tc002_admin_cannot_upload_image_larger_than_2mb()
    {
        // 1. Input: Buat file palsu 3MB (3072KB) agar > 2048KB
        $file = UploadedFile::fake()->image('struktur_besar.png')->size(3072);

        // 2. Aksi: Kirim request POST
        $response = $this->actingAs($this->user)
            ->post(route('backend-struktur-organisasi.store'), [
                'foto' => $file,
            ]);

        // 3. Expected Result:
        // UPDATE: Pesan error harus sesuai dengan StrukturOrganisasiController
        $response->assertSessionHasErrors([
            'foto' => 'Ukuran foto tidak boleh melebihi 2 MB.'
        ]);
        
        // Pastikan TIDAK ADA data yang masuk ke DB
        $this->assertDatabaseCount('struktur_organisasi', 0);
    }

    /**
     * 🔴 Skenario Tes TC-003: Gagal (File Wajib Diisi)
     */
    #[Test]
    public function tc003_admin_cannot_upload_without_a_file()
    {
        // 1. Input: Data request kosong
        $data = [];

        // 2. Aksi: Kirim request POST
        $response = $this->actingAs($this->user)
            ->post(route('backend-struktur-organisasi.store'), $data);

        // 3. Expected Result:
        $response->assertSessionHasErrors([
            'foto' => 'Kolom foto wajib diisi.'
        ]);
        
        $this->assertDatabaseCount('struktur_organisasi', 0);
    }

    /**
     * 🔵 Tes Tambahan: Admin bisa update gambar
     */
    #[Test]
    public function admin_can_update_the_image()
    {
        // 1. Buat file & data lama
        $fileLama = UploadedFile::fake()->image('lama.jpg');
        // Simpan manual file lama agar path-nya sesuai
        $pathLama = 'struktur-organisasi/lama.webp'; 
        Storage::disk('public')->put($pathLama, 'content');
        
        StrukturOrganisasi::create(['foto' => $pathLama]);

        // 2. Buat file baru
        $fileBaru = UploadedFile::fake()->image('baru.jpg');

        // 3. Kirim request PUT
        $response = $this->actingAs($this->user)
            ->put(route('backend-struktur-organisasi.update'), [
                'foto' => $fileBaru,
            ]);

        // 4. Pastikan berhasil
        $response->assertRedirect(route('backend-struktur-organisasi.index'));
        // UPDATE: Sesuaikan string dengan Controller
        $response->assertSessionHas('success', 'Foto struktur organisasi berhasil diperbarui (Auto WebP).');

        // 5. Pastikan file LAMA terhapus dari storage
        Storage::disk('public')->assertMissing($pathLama);

        // 6. Pastikan file BARU ada di storage
        $struktur = StrukturOrganisasi::first(); 
        Storage::disk('public')->assertExists($struktur->foto);
    }

    /**
     * 🔵 Tes Tambahan: Admin bisa hapus gambar
     */
    #[Test]
    public function admin_can_delete_the_image()
    {
        // 1. Buat file & data
        $file = UploadedFile::fake()->image('akan_dihapus.jpg');
        $path = 'struktur-organisasi/hapus.webp';
        Storage::disk('public')->put($path, 'content');
        
        StrukturOrganisasi::create(['foto' => $path]);

        // 2. Kirim request DELETE
        $response = $this->actingAs($this->user)
            ->delete(route('backend-struktur-organisasi.destroy'));

        // 3. Pastikan berhasil
        $response->assertRedirect(route('backend-struktur-organisasi.index'));
        $response->assertSessionHas('success', 'Foto struktur organisasi berhasil dihapus.');

        // 4. Pastikan data HILANG dari database
        $this->assertDatabaseCount('struktur_organisasi', 0);

        // 5. Pastikan file HILANG dari storage
        Storage::disk('public')->assertMissing($path);
    }
}