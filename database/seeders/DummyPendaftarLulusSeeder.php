<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyPendaftarLulusSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        // Daftar Prodi (Sesuaikan dengan data riil di tabel study_programs jika ada)
        $prodis = [
            'Teknik Informatika',
            'Manajemen Informatika',
            'Teknik Lingkungan',
            'Bisnis Digital',
            'Administrasi Rumah Sakit'
        ];

        // Buat 5 Mahasiswa Dummy yang LULUS
        for ($i = 1; $i <= 5; $i++) {
            
            // 1. Buat User Camaba
            $user = User::create([
                'name' => $faker->name,
                'email' => "mhs_lulus{$i}@unmaris.test", // Email dummy yang mudah diingat
                'nomor_hp' => $faker->phoneNumber,
                'role' => 'camaba',
                'password' => Hash::make('password'), // Password default
                'email_verified_at' => now(),
            ]);

            // Pilih Prodi Acak
            $prodiPilihan1 = $faker->randomElement($prodis);
            
            // 2. Buat Data Pendaftar Lengkap
            Pendaftar::create([
                'user_id' => $user->id,
                
                // Biodata & Kontak
                'jalur_pendaftaran' => 'reguler',
                'scholarship_id' => null,
                'nisn' => $faker->unique()->numerify('00########'),
                'nik' => $faker->unique()->nik(),
                'tempat_lahir' => $faker->city,
                'tgl_lahir' => $faker->date('Y-m-d', '2005-01-01'),
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'alamat' => $faker->address,
                'agama' => $faker->randomElement(['Katolik', 'Kristen', 'Islam', 'Hindu']),
                'nomor_hp' => $user->nomor_hp, // Samakan dengan user

                // Data Sekolah
                'asal_sekolah' => 'SMA Negeri ' . $faker->numberBetween(1, 5) . ' ' . $faker->city,
                'tahun_lulus' => '2024',

                // Data Orang Tua
                'nama_ayah' => $faker->name('male'),
                'pekerjaan_ayah' => 'Wiraswasta',
                'nama_ibu' => $faker->name('female'),
                'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                // 'nomor_hp_ortu' => $faker->phoneNumber, // Aktifkan jika kolom ini ada di migrasi Anda

                // Pilihan Prodi
                'pilihan_prodi_1' => $prodiPilihan1,
                'pilihan_prodi_2' => null, // Opsional

                // Status & Seleksi (PENTING UNTUK TES FITUR LULUS)
                'status_pendaftaran' => 'lulus', // Status LULUS
                'prodi_diterima' => $prodiPilihan1, // Diterima di pilihan 1
                'status_pilihan_1' => 'lulus',
                'is_locked' => true, // Data terkunci karena sudah lulus
                'is_synced' => false, // Belum dikirim ke SIAKAD (siap untuk ditest sync)

                // Pembayaran (Lunas agar valid)
                'status_pembayaran' => 'lunas',
                'bukti_pembayaran' => 'dummy_bukti.jpg', 

                // Nilai Ujian (Harus ada agar lulus)
                'nilai_ujian' => $faker->numberBetween(75, 95),
                'jadwal_ujian' => Carbon::now()->subDays(5),
                'lokasi_ujian' => 'Lab Komputer 1',
                'catatan_penguji' => 'Peserta sangat kompeten.',

                // Nilai Wawancara (Harus ada agar lulus)
                'nilai_wawancara' => $faker->numberBetween(80, 98),
                'jadwal_wawancara' => Carbon::now()->subDays(4),
                'pewawancara' => 'Dr. ' . $faker->firstName,
                'catatan_wawancara' => 'Direkomendasikan diterima.',

                // Berkas (Null dulu gpp untuk dummy)
                'foto_path' => null,
                'ijazah_path' => null,
                'ktp_path' => null,
                'akta_path' => null,
                'transkrip_path' => null,
                'jenis_dokumen' => 'ijazah',
            ]);
        }
    }
}