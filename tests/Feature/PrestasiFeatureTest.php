<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Prestasi;
use Spatie\Permission\Models\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;

class PrestasiFeatureTest extends TestCase
{
    use RefreshDatabase;

    // Helper untuk membuat dan mengembalikan objek User Admin
    private function createAdminUser(): User
    {
        Role::findOrCreate('Admin');
        $admin = User::factory()->create(['role' => 'Admin']);
        $admin->assignRole('Admin');
        return $admin; 
    }

    protected function setUp(): void
    {
        parent::setUp();
        // Pastikan role Admin dibuat di setiap setup tes
        Role::findOrCreate('Admin');
    }

    // =========================================================================
    // 1. CREATE (STORE) TESTS
    // =========================================================================

    #[Test]
    public function test_admin_can_view_index_page(): void
    {
        $admin = $this->createAdminUser(); 
        $response = $this->actingAs($admin)->get(route('backend-prestasi.index')); 

        $response->assertStatus(200);
        $response->assertSee('Daftar Prestasi'); 
    }

    #[Test]
    public function test_admin_can_store_prestasi_with_valid_data_and_image()
    {
        // TC-001: Submit semua field dengan data valid
        // 1. Arrange
        $admin = $this->createAdminUser();
        Storage::fake('public'); 
        $image = UploadedFile::fake()->image('gambar.jpg', 600, 400)->size(200); 

        $data = [
            'judul'             => 'Juara Coding Sekolah',
            'deskripsi'         => 'Prestasi luar biasa di bidang programming.',
            'kategori'          => 'Akademik',
            'tingkat'           => 'Sekolah',
            'peraih'            => 'Budi Santoso',
            'penyelenggara'     => 'Dinas Pendidikan',
            'tanggal_prestasi'  => '2025-10-22',
            'gambar'            => $image,
            'status'            => 'aktif',
        ];

        // 2. Act
        $response = $this->actingAs($admin)->post(route('backend-prestasi.store'), $data);

        // 3. Assert
        $response->assertRedirect(route('backend-prestasi.index'));
        // UPDATE: Sesuaikan pesan dengan Controller
        $response->assertSessionHas('success', 'Prestasi berhasil ditambahkan (Auto Convert WebP).');
        
        // Cek database
        $this->assertDatabaseHas('prestasis', ['judul' => 'Juara Coding Sekolah']);
        
        $prestasi = Prestasi::where('judul', 'Juara Coding Sekolah')->first();
        
        // FIX PATH: Memastikan file ada di storage dengan path yang tersimpan di DB
        Storage::disk('public')->assertExists($prestasi->gambar);
    }

    #[Test]
    public function test_store_fails_with_invalid_image_size()
    {
        // TC-009: Upload gambar ukuran lebih dari 2MB (Controller limit is 2048KB)
        // 1. Arrange
        $admin = $this->createAdminUser();
        Storage::fake('public');
        
        // UPDATE: Buat file 3MB (3072 KB) agar melebihi limit 2MB
        $imageTooLarge = UploadedFile::fake()->image('besar.png', 700, 500)->size(3072);

        $data = Prestasi::factory()->make()->toArray();
        $data['gambar'] = $imageTooLarge;
        $data['tanggal_prestasi'] = '2025-01-01';
        $data['status'] = 'aktif';

        // 2. Act
        $response = $this->actingAs($admin)->post(route('backend-prestasi.store'), $data);

        // 3. Assert
        $response->assertSessionHasErrors('gambar');
        // UPDATE: Pesan error harus sesuai dengan PrestasiController
        $response->assertSessionHasErrors(['gambar' => 'Ukuran gambar maksimal adalah 2MB.']);
        $this->assertDatabaseCount('prestasis', 0);
    }
    
    #[Test]
    public function test_store_fails_with_invalid_image_format()
    {
        // TC-008: Upload gambar format tidak sesuai (misal: PDF)
        // 1. Arrange
        $admin = $this->createAdminUser();
        Storage::fake('public');
        
        $invalidFile = UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'); 

        $data = Prestasi::factory()->make()->toArray();
        $data['gambar'] = $invalidFile;
        $data['tanggal_prestasi'] = '2025-01-01';
        $data['status'] = 'aktif';

        // 2. Act
        $response = $this->actingAs($admin)->post(route('backend-prestasi.store'), $data);

        // 3. Assert
        $response->assertSessionHasErrors('gambar');
        $response->assertSessionHasErrors(['gambar' => 'Gambar harus berformat: jpeg, png, atau jpg.']); 
        $this->assertDatabaseCount('prestasis', 0);
    }

    // =========================================================================
    // 2. UPDATE & TOGGLE STATUS TESTS
    // =========================================================================

    #[Test]
    public function test_admin_can_update_prestasi_title()
    {
        // 1. Arrange
        $admin = $this->createAdminUser();
        $prestasi = Prestasi::factory()->create(['judul' => 'Judul Lama', 'status' => 'aktif']);

        $newTitle = 'Judul Baru Yang Keren';
        $expectedSlug = Str::slug($newTitle);

        // 2. Act
        $response = $this->actingAs($admin)->put(route('backend-prestasi.update', $prestasi->slug), [
            'judul'             => $newTitle,
            'deskripsi'         => $prestasi->deskripsi,
            'kategori'          => $prestasi->kategori,
            'tingkat'           => $prestasi->tingkat,
            'peraih'            => $prestasi->peraih,
            'tanggal_prestasi'  => $prestasi->tanggal_prestasi->format('Y-m-d'),
            'status'            => $prestasi->status,
            'action'            => 'publish' // Tambahkan action publish
        ]);

        // 3. Assert
        $response->assertRedirect(route('backend-prestasi.index'));
        $this->assertDatabaseHas('prestasis', [ 
            'id' => $prestasi->id,
            'judul' => $newTitle,
            'slug' => $expectedSlug,
        ]);
    }
    
    #[Test]
    public function test_admin_can_toggle_status_via_api_route()
    {
        $admin = $this->createAdminUser();
        $prestasi = Prestasi::factory()->create(['status' => 'nonaktif']);

        $response = $this->actingAs($admin)->patch(route('backend-prestasi.indextoggle-status', $prestasi->slug), [
            'status' => 'aktif'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('prestasis', [
            'id' => $prestasi->id,
            'status' => 'aktif',
        ]);
    }

    // =========================================================================
    // 3. DELETE TESTS
    // =========================================================================

    #[Test]
    public function test_admin_can_delete_prestasi()
    {
        $admin = $this->createAdminUser();
        Storage::fake('public');
        $imageName = 'prestasi/test.jpg';
        Storage::disk('public')->put($imageName, 'dummy content');
        
        $prestasi = Prestasi::factory()->create(['gambar' => $imageName]);
        
        $response = $this->actingAs($admin)->delete(route('backend-prestasi.destroy', $prestasi->slug));

        $response->assertRedirect(route('backend-prestasi.index'));
        $response->assertSessionHas('success', 'Prestasi berhasil dihapus');
        
        $this->assertDatabaseMissing('prestasis', [
            'id' => $prestasi->id,
        ]);
        
        Storage::disk('public')->assertMissing($imageName);
    }
}