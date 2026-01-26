<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Super Admin (Akses Penuh)
        User::firstOrCreate(
            ['email' => 'admin@unmaris.ac.id'],
            [
                'name' => 'Administrator PMB',
                'nomor_hp' => '081234567890',
                'role' => 'admin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Segera ganti setelah deploy!
            ]
        );

        // 2. Akun Bagian Keuangan (Verifikasi Pembayaran & Laporan)
        User::firstOrCreate(
            ['email' => 'keuangan@unmaris.ac.id'],
            [
                'name' => 'Staf Keuangan',
                'nomor_hp' => '081234567891',
                'role' => 'keuangan',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        // 3. Akun Bagian Akademik (Verifikasi Berkas & Seleksi)
        User::firstOrCreate(
            ['email' => 'akademik@unmaris.ac.id'],
            [
                'name' => 'Staf Akademik',
                'nomor_hp' => '081234567892',
                'role' => 'akademik',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );

        // CATATAN: Akun Dummy Camaba dihapus untuk persiapan Live/Production.

        // Panggil Seeder Master Data (Wajib Ada untuk Operasional)
        $this->call([
            SiteSettingSeeder::class,           // Data Setting Website (Nama Kampus, Biaya, Kontak)
            GelombangSeeder::class,             // Data Jadwal Pendaftaran
            FacilitySlideSeeder::class,         // Data Slider Fasilitas
            DummyCamabaSeeder::class,        // Dihapus (Non-aktifkan)
            DummyPendaftarLulusSeeder::class,// Dihapus (Non-aktifkan)
        ]);
    }
}
