<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\CetakKartuController;
use App\Http\Controllers\Admin\PendaftarController;
use App\Livewire\Camaba\Pembayaran;

// Import Livewire Components Baru
use App\Livewire\Camaba\Dashboard as CamabaDashboard;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Models\Gelombang;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // Ambil semua gelombang, urutkan dari yang terdekat
    $gelombangs = Gelombang::orderBy('tgl_mulai', 'asc')->get();
    $settings = SiteSetting::first();

    // Kirim variable $settings ke view
    return view('welcome', compact('gelombangs', 'settings'));
});
Route::post('/admin/pendaftar/{id}/sync', [\App\Http\Controllers\Admin\PendaftarController::class, 'pushToSiakad'])
    ->name('admin.pendaftar.sync');


Route::get('/verifikasi/loa/{id}/{hash}', [PengumumanController::class, 'verifikasiSurat'])
    ->name('verifikasi.surat');

// ====================================================
// AREA CAMABA (Calon Mahasiswa)
// ====================================================
Route::middleware(['auth', 'verified', 'role:camaba'])
    ->prefix('portal')
    ->name('camaba.')
    ->group(function () {

        // 1. Dashboard Utama (Livewire)
        Route::get('/dashboard', CamabaDashboard::class)->name('dashboard');

        // 2. Formulir Pendaftaran
        Route::view('/formulir', 'camaba.formulir')->name('formulir');

        // 3. Pembayaran
        Route::get('/pembayaran', Pembayaran::class)->name('pembayaran');

        // 4. Cetak Kartu Ujian
        Route::get('/cetak-kartu', [CetakKartuController::class, 'cetak'])->name('cetak-kartu');

        // 5. Pengumuman Kelulusan
        Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman');
        Route::get('/pengumuman/cetak', [PengumumanController::class, 'cetak'])->name('pengumuman.cetak');
    });

// ====================================================
// AREA ADMIN KAMPUS
// ====================================================
Route::middleware(['auth', 'verified', 'role:admin,keuangan,akademik'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // 1. Command Center (Livewire)
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        // 2. Export Data Excel
        Route::get('/export', [PendaftarController::class, 'export'])->name('export');
        Route::middleware(['role:keuangan'])->group(function () {
            Route::patch('/pendaftar/{id}/verify-payment', [PendaftarController::class, 'verifyPayment'])->name('pendaftar.verify-payment');
        });
        // 3. Manajemen Pendaftar
        Route::get('/pendaftar', [PendaftarController::class, 'index'])->name('pendaftar.index');
        Route::get('/pendaftar/{id}', [PendaftarController::class, 'show'])->name('pendaftar.show');
        Route::patch('/pendaftar/{id}/status', [PendaftarController::class, 'updateStatus'])->name('pendaftar.update-status');

        // 4. Manajemen Seleksi
        Route::get('/seleksi', function () {
            return view('admin.seleksi');
        })->name('seleksi.index');

        Route::get('/wawancara', function () {
            return view('admin.wawancara');
        })->name('wawancara.index');


        // 5. Manajemen Gelombang
        Route::get('/gelombang', function () {
            return view('admin.gelombang');
        })->name('gelombang.index');

        Route::get('/users', function () {
            return view('admin.users');
        })->name('users.index');


        // 7. Pengaturan Website (CMS)
        Route::get('/settings', function () {
            return view('admin.settings');
        })->name('settings.index');

        // 8. Laporan PDF
        Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/cetak', [\App\Http\Controllers\Admin\LaporanController::class, 'cetak'])->name('laporan.cetak');
        Route::get('/pengumuman-admin', function () {
            return view('admin.announcements');
        })->name('announcements.index');

        Route::get('/activity-logs', function () {
            return view('admin.logs');
        })->name('logs.index');
    });

// ====================================================
// REDIRECT DASHBOARD
// ====================================================
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('camaba.dashboard');
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
