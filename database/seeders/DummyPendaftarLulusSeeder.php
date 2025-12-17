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

        // Daftar Prodi yang valid (Sesuai database SIAKAD)
        $prodis = [
            'Teknik Informatika',
            'Manajemen Informatika',
            'Teknik Lingkungan',
            'Bisnis Digital',
            'Administrasi Rumah Sakit'
        ];

        // Buat 10 Mahasiswa Lulus
        for ($i = 1; $i <= 10; $i++) {
            
            // 1. Buat User
            $user = User::create([
                'name' => $faker->name,
                'email' => "mhs_lulus{$i}@unmaris.test",
                'nomor_hp' => $faker->phoneNumber,
                'role' => 'camaba',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            // 2. Buat Pendaftar (Status Lulus & Belum Sync)
            Pendaftar::create([
                'user_id' => $user->id,
                'jalur_pendaftaran' => 'reguler',
                'nisn' => $faker->unique()->numerify('00########'),
                'nik' => $faker->unique()->nik(),
                'tempat_lahir' => $faker->city,
                'tgl_lahir' => $faker->date('Y-m-d', '2005-01-01'),
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'alamat' => $faker->address,
                'agama' => $faker->randomElement(['Katolik', 'Kristen', 'Islam', 'Hindu']),
                
                'asal_sekolah' => 'SMA Negeri ' . $faker->numberBetween(1, 5) . ' ' . $faker->city,
                'tahun_lulus' => '2024',
                'nama_ayah' => $faker->name('male'),
                'pekerjaan_ayah' => 'Wiraswasta',
                'nama_ibu' => $faker->name('female'),
                'pekerjaan_ibu' => 'Ibu Rumah Tangga',

                'pilihan_prodi_1' => $faker->randomElement($prodis),
                'pilihan_prodi_2' => null,

                // Status Penting untuk Test Sync
                'status_pendaftaran' => 'lulus',
                'status_pembayaran' => 'lunas',
                'nilai_ujian' => $faker->numberBetween(70, 95),
                'nilai_wawancara' => $faker->numberBetween(75, 98),
                'is_synced' => false, // Set false agar muncul di list siap kirim
                
                // Data dummy lain
                'jadwal_ujian' => Carbon::now()->subDays(5),
                'lokasi_ujian' => 'Lab Komputer 1',
                'foto_path' => null, // Kosongkan atau isi path dummy
                'ijazah_path' => null,
            ]);
        }
    }
}