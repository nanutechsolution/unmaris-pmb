<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\CetakKartuController;
use App\Http\Controllers\Admin\PendaftarController;
use App\Livewire\Camaba\Pembayaran;

// Import Livewire Components Baru
use App\Livewire\Camaba\Dashboard as CamabaDashboard;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\PendaftarDetail;
use App\Models\FacilitySlide;
use App\Models\Gelombang;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes (Public)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $gelombangs = Gelombang::orderBy('tgl_mulai', 'asc')->get();
    $settings = SiteSetting::first();

    $facilitySlides = FacilitySlide::where('is_active', true)
        ->orderBy('sort_order', 'asc')
        ->get();

    return view('welcome', compact('gelombangs', 'settings', 'facilitySlides'));
});

// Verifikasi Surat Kelulusan (Public)
Route::get('/verifikasi/loa/{id}/{hash}', [PengumumanController::class, 'verifikasiSurat'])
    ->name('verifikasi.surat');


/*
|--------------------------------------------------------------------------
| AREA CAMABA (Calon Mahasiswa)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:camaba'])
    ->prefix('portal')
    ->name('camaba.')
    ->group(function () {
        Route::get('/dashboard', CamabaDashboard::class)->name('dashboard');
        Route::view('/formulir', 'camaba.formulir')->name('formulir');
        Route::get('/pembayaran', Pembayaran::class)->name('pembayaran');
        Route::get('/cetak-kartu', [CetakKartuController::class, 'cetak'])->name('cetak-kartu');
        Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman');
        Route::get('/pengumuman/cetak', [PengumumanController::class, 'cetak'])->name('pengumuman.cetak');
        Route::get('/bantuan', App\Livewire\Camaba\Helpdesk::class)->name('helpdesk');
    });


/*
|--------------------------------------------------------------------------
| AREA ADMIN & PETUGAS KAMPUS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:admin,keuangan,akademik'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // --- 1. SHARED (Akses Semua Petugas) ---
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

        // Pendaftar (View Only / Basic Actions)
        Route::get('/pendaftar', [PendaftarController::class, 'index'])->name('pendaftar.index');
        // Route::get('/pendaftar/{id}', [PendaftarController::class, 'show'])->name('pendaftar.show');
        Route::get('/pendaftar/{id}', PendaftarDetail::class)->name('pendaftar.show');


        // Update Status & Verifikasi Berkas (Bisa diakses Akademik/Admin)
        // Route::patch('/pendaftar/{id}/status', [PendaftarController::class, 'updateStatus'])->name('pendaftar.update-status');
        Route::patch('/pendaftar/{id}/update-status', [PendaftarController::class, 'updateStatus'])->name('pendaftar.update-status');

        // Export Data (Semua Petugas Butuh)
        Route::get('/export', [PendaftarController::class, 'export'])->name('export');


        // --- 2. KHUSUS KEUANGAN ---
        Route::middleware(['role:keuangan,admin'])->group(function () {
            // Verifikasi Pembayaran
            Route::patch('/pendaftar/{id}/verify-payment', [PendaftarController::class, 'verifyPayment'])->name('pendaftar.verify-payment');

            // Laporan Keuangan
            Route::get('/payment-report', \App\Livewire\Admin\PaymentReport::class)->name('payment-report');

            // Laporan Referral (Terkait Komisi)
            Route::get('/referral', \App\Livewire\Admin\ReferralReport::class)->name('referral');
            Route::get('/referral-manager', \App\Livewire\Admin\ReferralManager::class)
                ->name('referral-manager.index');
        });


        // --- 3. KHUSUS AKADEMIK ---
        Route::middleware(['role:akademik,admin'])->group(function () {
            // Seleksi & Nilai
            Route::get('/seleksi', \App\Livewire\Admin\SeleksiManager::class)->name('seleksi.index');

            // Wawancara
            Route::get('/wawancara', \App\Livewire\Admin\WawancaraManager::class)->name('wawancara.index');

            // Manajemen Gelombang
            Route::get('/gelombang', function () {
                return view('admin.gelombang');
            })->name('gelombang.index');

            // Manajemen Prodi
            Route::get('/prodi', \App\Livewire\Admin\ProdiManager::class)->name('prodi.index');
            // Keputusan Kelulusan
            Route::patch('/pendaftar/{id}/lulus-pilihan', [PendaftarController::class, 'lulusPilihan'])->name('pendaftar.lulus-pilihan');
            Route::patch('/pendaftar/{id}/rekomendasi', [PendaftarController::class, 'simpanRekomendasi'])->name('pendaftar.rekomendasi');
            Route::patch('/pendaftar/{pendaftar}/lulus-rekomendasi', [PendaftarController::class, 'lulusRekomendasi'])->name('pendaftar.lulus-rekomendasi');

            // Sync SIAKAD
            Route::post('/pendaftar/{id}/sync', [PendaftarController::class, 'pushToSiakad'])->name('pendaftar.sync');
        });


        // --- 4. KHUSUS SUPER ADMIN (SYSTEM OWNER) ---
        Route::middleware(['role:admin'])->group(function () {
            Route::get('/admin/referral-scheme', \App\Livewire\Admin\ReferralScheme\Index::class)->name('referral-scheme');
            // Manajemen User (Petugas & Camaba)
            Route::get('/users', \App\Livewire\Admin\UserManager::class)->name('users.index');
            // Pengaturan Website (CMS)
            Route::get('/settings', \App\Livewire\Admin\SiteSettings::class)->name('settings.index'); // Alias lama
            Route::get('/settings-panel', \App\Livewire\Admin\SiteSettings::class)->name('settings'); // Alias baru

            // Fasilitas & Slider
            Route::get('/facilities', \App\Livewire\Admin\FacilityManager::class)->name('facilities');

            // System Logs & Geo
            Route::get('/activity-logs', function () {
                return view('admin.logs');
            })->name('logs.index');
            Route::get('/geographic-stats', App\Livewire\Admin\GeographicStats::class)->name('geographic.index');

            // Pengumuman & Helpdesk & Laporan Global
            Route::get('/pengumuman-admin', function () {
                return view('admin.announcements');
            })->name('announcements.index');
            Route::get('/helpdesk', function () {
                return view('admin.helpdesk');
            })->name('helpdesk.index');

            // Laporan PDF
            Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
            Route::get('/laporan/cetak', [\App\Http\Controllers\Admin\LaporanController::class, 'cetak'])->name('laporan.cetak');

            Route::get('/beasiswa', function () {
                return view('admin.beasiswa');
            })->name('beasiswa.index');
        });
    });


/*
|--------------------------------------------------------------------------
| AUTHENTICATION & REDIRECTS
|--------------------------------------------------------------------------
*/

// Redirect Dashboard sesuai Role
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'camaba') {
        return redirect()->route('camaba.dashboard');
    }
    // Semua role selain camaba (admin, keuangan, akademik) ke admin dashboard
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::post('/logout', function () {
    Auth::guard('web')->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('logout');

require __DIR__ . '/auth.php';
