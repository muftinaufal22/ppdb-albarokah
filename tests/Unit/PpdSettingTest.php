<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\PpdbSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
// FIX KRITIS: Tambahkan use statement untuk Factory
use Database\Factories\PpdbSettingFactory;
class PpdSettingTest extends TestCase
{
    use RefreshDatabase;

    // Gunakan fungsi setup Carbon untuk mengontrol waktu
    protected function setUp(): void
    {
        parent::setUp();
        // Atur waktu ke hari ini untuk konsistensi testing
        Carbon::setTestNow(Carbon::create(2025, 11, 06)); 
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Carbon::setTestNow(); // Reset waktu setelah tes selesai
    }

    // =========================================================
    // TES UNTUK LOGIKA isOpen()
    // =========================================================

    #[Test]
    public function test_is_open_is_false_if_no_setting_exists()
    {
        // Assert: Harus false jika tidak ada record di database
        $this->assertFalse(PpdbSetting::isOpen());
    }

    #[Test]
    public function test_is_open_is_false_if_is_active_is_false()
    {
        // Arrange: Buat setting, tapi nonaktif
        PpdbSettingFactory::new()->inactive()->create(); // FIX: Gunakan Factory Class
        
        // Assert: Harus false meskipun tanggal valid
        $this->assertFalse(PpdbSetting::isOpen());
    }

    #[Test]
    public function test_is_open_is_true_if_within_date_range()
    {
        // Arrange: Buat setting yang aktif dan tanggalnya mencakup hari ini (2025-11-06)
        PpdbSettingFactory::new()->create([ // FIX: Gunakan Factory Class
            'tanggal_buka' => '2025-11-01',
            'tanggal_tutup' => '2025-11-30',
        ]);

        // Assert: Harus true
        $this->assertTrue(PpdbSetting::isOpen());
    }

    #[Test]
    public function test_is_open_is_false_if_closing_date_is_in_past()
    {
        // Arrange: Tutup kemarin (2025-11-05)
        PpdbSettingFactory::new()->closedByDate()->create(); // FIX: Gunakan Factory Class

        // Assert: Harus false
        $this->assertFalse(PpdbSetting::isOpen());
    }

    #[Test]
    public function test_is_open_is_true_if_only_opening_date_is_set_and_passed()
    {
        // Arrange: Buka hari ini, tidak ada tanggal tutup
        PpdbSettingFactory::new()->create([ // FIX: Gunakan Factory Class
            'tanggal_buka' => '2025-11-06',
            'tanggal_tutup' => null,
        ]);

        // Assert: Harus true
        $this->assertTrue(PpdbSetting::isOpen());
    }
    
    // =========================================================
    // TES UNTUK LOGIKA getClosedMessage()
    // =========================================================

    #[Test]
    public function test_get_closed_message_returns_custom_message()
    {
        // Arrange: Buat setting dengan pesan kustom
        PpdbSettingFactory::new()->create([ // FIX: Gunakan Factory Class
            'pesan_nonaktif' => 'Pendaftaran diundur bulan depan.'
        ]);
        
        // Assert: Harus mengembalikan pesan kustom
        $this->assertEquals('Pendaftaran diundur bulan depan.', PpdbSetting::getClosedMessage());
    }
    
    #[Test]
    public function test_get_closed_message_returns_default_message()
    {
        // Arrange: Buat setting dengan pesan nonaktif kosong (null)
        PpdbSettingFactory::new()->create(['pesan_nonaktif' => null]); // FIX: Gunakan Factory Class
        
        // Assert: Harus mengembalikan pesan default
        $this->assertEquals('Pendaftaran PPDB saat ini ditutup.', PpdbSetting::getClosedMessage());
    }
}