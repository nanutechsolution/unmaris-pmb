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

Route::get('/', function () {
    // Ambil semua gelombang, urutkan dari yang terdekat
    $gelombangs = Gelombang::orderBy('tgl_mulai', 'asc')->get();

    return view('welcome', compact('gelombangs'));
});



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
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // 1. Command Center (Livewire)
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

        // 2. Export Data Excel
        Route::get('/export', [PendaftarController::class, 'export'])->name('export');

        // 3. Manajemen Pendaftar
        Route::get('/pendaftar', [PendaftarController::class, 'index'])->name('pendaftar.index');
        Route::get('/pendaftar/{id}', [PendaftarController::class, 'show'])->name('pendaftar.show');
        Route::patch('/pendaftar/{id}/status', [PendaftarController::class, 'updateStatus'])->name('pendaftar.update-status');
        Route::patch('/pendaftar/{id}/verify-payment', [PendaftarController::class, 'verifyPayment'])->name('pendaftar.verify-payment');

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

require __DIR__ . '/auth.php';
