<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import semua controller
use App\Http\Controllers\Frontend\IndexController as FrontendIndexController;
use App\Http\Controllers\Frontend\MenuController as FrontendMenuController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
// use App\Http\Controllers\PPDController; // (Opsional: Dinonaktifkan jika diganti PpdController baru)
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\Website\ProfilSekolahController;
use App\Http\Controllers\Backend\Website\VisidanMisiController;
use App\Http\Controllers\Backend\Website\ProgramController;
use App\Http\Controllers\Backend\Website\KegiatanController;
use App\Http\Controllers\Backend\Website\ImageSliderController;
use App\Http\Controllers\Backend\Website\AboutController;
use App\Http\Controllers\Backend\Website\VideoController;
use App\Http\Controllers\Backend\Website\KategoriBeritaController;
use App\Http\Controllers\Backend\Website\BeritaController;
use App\Http\Controllers\Backend\Website\EventsController;
use App\Http\Controllers\Backend\Website\FooterController;
use App\Http\Controllers\Backend\Pengguna\PengajarController;
use App\Http\Controllers\Backend\Pengguna\StafController;
use App\Http\Controllers\Backend\Pengguna\MuridController as BackendMuridController;
use App\Http\Controllers\Backend\Pengguna\PPDBController as BackendPPDBController;
use App\Http\Controllers\Backend\Pengguna\BendaharaController;

// IMPORT CONTROLLER BARU UNTUK PENGATURAN PPDB
use App\Http\Controllers\Backend\Pengguna\PpdController; 

use App\Http\Controllers\MuridController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PPDBController;
use App\Http\Controllers\SPPController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\DataMuridController;
use App\Http\Controllers\PpdbSettingController;
use App\Http\Controllers\MasterSppController;
use App\Http\Controllers\PrestasiController;
use App\Http\Controllers\StrukturOrganisasiController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/debug-user', function () {
    return response()->json([
        'auth_check' => Auth::check(),
        'auth_user' => Auth::user(),
        'session' => session()->all()
    ]);
});

// ======= HALAMAN DEPAN (FRONTEND) ======= \\
Route::get('/', [FrontendIndexController::class, 'index'])->name('frontend.home');

// Frontend Public Routes
Route::get('profile-sekolah', [FrontendIndexController::class, 'profileSekolah'])->name('profile.sekolah');
Route::get('visi-dan-misi', [FrontendIndexController::class, 'visimisi'])->name('visimisi.sekolah');
Route::get('program/{slug}', [FrontendMenuController::class, 'programStudi']);
Route::get('kegiatan/{id}', [FrontendIndexController::class, 'detailKegiatan'])->name('detail.kegiatan');
Route::get('berita', [FrontendIndexController::class, 'berita'])->name('berita');
Route::get('berita/{slug}', [FrontendIndexController::class, 'detailBerita'])->name('detail.berita');
Route::get('event/{slug}', [FrontendIndexController::class, 'detailEvent'])->name('detail.event');
Route::get('event', [FrontendIndexController::class, 'events'])->name('event');

// Public Routes untuk Prestasi dan Struktur Organisasi
Route::get('prestasi-sekolah', [PrestasiController::class, 'publicIndex'])->name('frontend.prestasi');
Route::get('prestasi-sekolah/{slug}', [PrestasiController::class, 'publicShow'])->name('frontend.prestasi.detail');
Route::get('struktur-organisasi', [StrukturOrganisasiController::class, 'publicView'])->name('frontend.struktur');

// Route Otentikasi bawaan Laravel (Login, Logout)
Auth::routes(['register' => false]);


// PPDB Routes - Public (tanpa middleware auth)
Route::prefix('ppdb')->group(function() {
    Route::get('/', [PPDBController::class, 'index']);
    
    // REGISTER
    Route::get('/register', [AuthController::class, 'registerView'])->name('register');
    Route::post('/register', [AuthController::class, 'registerStore'])->name('register.store');
});

// Halaman jika PPDB ditutup
Route::get('/ppd/closed', function() {
    return view('ppd.closed');
})->name('ppd.closed');

// ======= AREA BACKEND (SETELAH LOGIN) ======= \\
Route::middleware('auth')->group(function () {
    
    // Halaman home universal setelah login
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Pengaturan Profil & Ganti Password (untuk semua role)
    Route::resource('profile-settings', ProfileController::class);
    Route::put('profile-settings/change-password/{id}', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    
    // ======= GRUP KHUSUS ADMIN ======= \\
    Route::group(['middleware' => 'role:Admin'], function() {
        
        // Pengelolaan Website
        Route::resources([
            'backend-profile-sekolah'   => ProfilSekolahController::class,
            'backend-visimisi'          => VisidanMisiController::class,
            'program-studi'             => ProgramController::class,
            'backend-kegiatan'          => KegiatanController::class,
            'backend-imageslider'       => ImageSliderController::class,
            'backend-about'             => AboutController::class,
            'backend-video'             => VideoController::class,
            'backend-kategori-berita'   => KategoriBeritaController::class,
            'backend-berita'            => BeritaController::class,
            'backend-event'             => EventsController::class,
            'backend-footer'            => FooterController::class,
        ]);

        // Prestasi Routes (Backend Admin)
        Route::resource('backend-prestasi', PrestasiController::class)->parameters([
            'backend-prestasi' => 'slug'
        ]);
        Route::patch('backend-prestasi/{slug}/toggle-status', [PrestasiController::class, 'toggleStatus'])
            ->name('backend-prestasi.indextoggle-status');
        
       // Rute untuk Struktur Organisasi (TANPA ID)
        Route::get('backend-struktur-organisasi', [StrukturOrganisasiController::class, 'index'])->name('backend-struktur-organisasi.index');
        Route::get('backend-struktur-organisasi/create', [StrukturOrganisasiController::class, 'create'])->name('backend-struktur-organisasi.create');
        Route::post('backend-struktur-organisasi', [StrukturOrganisasiController::class, 'store'])->name('backend-struktur-organisasi.store');
        Route::get('backend-struktur-organisasi/edit', [StrukturOrganisasiController::class, 'edit'])->name('backend-struktur-organisasi.edit');
        Route::put('backend-struktur-organisasi', [StrukturOrganisasiController::class, 'update'])->name('backend-struktur-organisasi.update');
        Route::delete('backend-struktur-organisasi', [StrukturOrganisasiController::class, 'destroy'])->name('backend-struktur-organisasi.destroy');


        // Pengelolaan Pengguna
        Route::resources([
            'backend-pengguna-pengajar'   => PengajarController::class,
            'backend-pengguna-staf'       => StafController::class,
            'backend-pengguna-murid'      => BackendMuridController::class,
            'backend-pengguna-ppdb'       => BackendPPDBController::class,
            'backend-pengguna-bendahara'  => BendaharaController::class,
        ]);
        
        // [CATATAN]: Route PPD Lama saya komentar agar tidak bentrok dengan yang baru di bawah
        /* Route::get('/ppd', [PPDController::class, 'index'])->name('ppdb.buka_tutup');
        Route::post('/ppd/open', [PPDController::class, 'openPPD'])->name('ppd.open');
        Route::post('/ppd/close', [PPDController::class, 'closePPD'])->name('ppd.close');
        */
    });
    
    // ======= GRUP KHUSUS GURU ======= \\
    Route::group(['middleware' => 'role:Guru'], function () {
        Route::get('daftar-murid-A', [ProgramController::class, 'muridA'])->name('muridA');
        Route::get('daftar-murid-B', [ProgramController::class, 'muridB'])->name('muridB');
        Route::get('detail-murid-guru/{id}', [ProgramController::class, 'showMuridDetail'])->name('guru.detail.murid');
        Route::get('jadwal-pelajaranA', [ProgramController::class, 'showKelasA'])->name('kelasA');
        Route::get('jadwal-pelajaranB', [ProgramController::class, 'showKelasB'])->name('kelasB');
    });

    // ======= RUTE UNTUK MURID ======= //
    Route::middleware(['role:Murid'])->group(function () {
        // Jadwal Pelajaran
        Route::get('/murid/jurusan/{kelas_id}', [ProgramController::class, 'showForMurid'])->name('murid.jurusan');
    });

    // ======= ROLE GUEST (PPDB) ======= \\
    Route::prefix('/ppdb')->middleware('role:Guest')->group(function(){
        // DATA MURID
        Route::get('form-pendaftaran', [PendaftaranController::class, 'index'])->name('ppdb.form-pendaftaran');
        Route::put('form-pendaftaran/{id}', [PendaftaranController::class, 'update']);

        // DATA ORANG TUA
        Route::get('form-data-orangtua', [PendaftaranController::class, 'dataOrtuView']);
        Route::put('form-data-orangtua/{id}', [PendaftaranController::class, 'updateOrtu']);

        // BERKAS MURID
        Route::get('form-berkas', [PendaftaranController::class, 'berkasView']);
        Route::put('form-berkas/{id}', [PendaftaranController::class, 'berkasStore']);
    });

    // ======= ROLE PPDB (ADMIN & PETUGAS PPDB) ======= \\
    Route::prefix('/ppdb')->middleware('role:PPDB|Admin')->group(function(){
        // DATA MURID
        Route::resource('data-murid', DataMuridController::class);
        
        // PPDB SETTINGS LAMA (Bisa dihapus jika tidak pakai PpdbSettingController)
        Route::get('settings', [PpdbSettingController::class, 'index'])->name('ppdb.settings');
        Route::put('settings', [PpdbSettingController::class, 'update'])->name('ppdb.settings.update');

        // [BARU] Route Buka/Tutup PPDB (PpdController)
        // URL akses: /ppdb/buka-tutup-ppd
        Route::get('buka-tutup-ppd', [PpdController::class, 'index'])->name('ppd.index');
        Route::put('buka-tutup-ppd', [PpdController::class, 'update'])->name('ppd.update');
    });
});

// ======= GRUP UNTUK MURID (PEMBAYARAN) ======= \\
Route::group(['prefix' => 'murid', 'middleware' => ['auth', 'role:Murid'], 'as' => 'murid.'], function() {
    // Route untuk dashboard murid
    Route::get('/', [MuridController::class, 'index'])->name('dashboard');

    // Resource Controller untuk Pembayaran (hanya create & show, sisanya di handle manual)
    Route::resource('pembayaran', PembayaranController::class)->except([
        'index', 'store', 'edit', 'update'
    ]);
    
    // Route untuk jadwal
    Route::get('/jadwal', [ProgramController::class, 'showForMurid'])->name('jadwal.index');
});

// ======= GRUP UNTUK ADMIN DAN BENDAHARA (SETTINGS) ======= \\
Route::group(['middleware' => ['auth', 'role:Admin|Bendahara'], 'prefix' => 'backend', 'as' => 'backend.'], function () {
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    
    Route::post('/settings/spp', [SettingController::class, 'storeSppSetting'])->name('settings.spp.store');
    Route::put('/settings/spp/{id}', [SettingController::class, 'updateSppSetting'])->name('settings.spp.update');
    Route::delete('/settings/spp/{id}', [SettingController::class, 'deleteSppSetting'])->name('settings.spp.delete');
    
    Route::post('/settings/bank', [SettingController::class, 'addBank'])->name('settings.bank.store');
    Route::put('/settings/bank/{id}', [SettingController::class, 'updateBank'])->name('settings.bank.update');
    Route::delete('/settings/bank/{id}', [SettingController::class, 'deleteBank'])->name('settings.bank.delete');
});

// ======= RUTE SPP UNTUK ADMIN DAN BENDAHARA ======= \\
Route::group(['middleware' => ['auth', 'role:Admin|Bendahara'], 'prefix' => 'spp', 'as' => 'spp.'], function () {
    
    // Halaman daftar murid
    Route::get('/murid', [SPPController::class, 'murid'])->name('murid.index');
    
    // Menyetujui pembayaran
    Route::post('/payment/{id}/accept', [SPPController::class, 'acceptPayment'])->name('payment.accept');

    // Menolak pembayaran
    Route::post('/payment/{id}/reject', [SPPController::class, 'rejectPayment'])->name('payment.reject');

    // Detail pembayaran berdasarkan user_id
    Route::get('/murid/{userId}/detail', [SPPController::class, 'detail'])->name('murid.detail');
    
    // Update status pembayaran
    Route::post('/murid/update-pembayaran', [SPPController::class, 'updatePembayaran'])->name('murid.update.pembayaran');
    
    // Route untuk konfirmasi pembayaran via AJAX
    Route::post('/pembayaran/{id}/confirm', [SPPController::class, 'confirmPayment'])->name('payment.confirm');

    // Master SPP
    Route::resource('master-spp', MasterSppController::class)->except('show');
});

// ======= RUTE SPP UNTUK MURID ======= \\
Route::group(['middleware' => ['auth', 'role:Murid'], 'prefix' => 'murid/pembayaran', 'as' => 'murid.pembayaran.'], function(){
    
    // Halaman Index (Daftar Tagihan)
    Route::get('/', [SPPController::class, 'tagihanMurid'])->name('index');

    // Proses Simpan Baru (via Modal)
    Route::post('/store', [SPPController::class, 'createPayment'])->name('store');

    // Halaman Edit (Form Upload Ulang)
    Route::get('/{id}/edit', [SPPController::class, 'editPayment'])->name('edit');

    // Proses Update (Simpan Upload Ulang) -> WAJIB PUT
    Route::put('/{id}', [SPPController::class, 'updatePayment'])->name('update');
});

// ======= RUTE SPP UNTUK GURU ======= \\
Route::group(['middleware' => ['auth', 'role:Guru'], 'prefix' => 'spp', 'as' => 'spp.'], function () {
    Route::get('/kelas', [SPPController::class, 'showForGuru'])->name('guru.kelas');
});

// ======= API ROUTES UNTUK AJAX/STATISTICS ======= \\
Route::group(['middleware' => ['auth', 'role:Admin'], 'prefix' => 'api', 'as' => 'api.'], function () {
    Route::get('prestasi/statistics', [PrestasiController::class, 'statistics'])->name('prestasi.statistics');
    Route::get('struktur/statistics', [StrukturOrganisasiController::class, 'statistics'])->name('struktur.statistics');
});