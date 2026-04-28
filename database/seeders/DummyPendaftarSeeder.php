<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pendaftar;
use Carbon\Carbon;

class DummyPendaftarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('password123'); // Password default untuk testing
        $now = Carbon::now();

        // Data 5 Calon Mahasiswa Siap Sync
        $camabas = [
            [
                'user' => [
                    'name' => 'Budi Santoso',
                    'email' => 'budi.santoso@email.com',
                    'nomor_hp' => '081234567801',
                    'role' => 'camaba',
                    'password' => $password,
                ],
                'pendaftar' => [
                    'jalur_pendaftaran' => 'reguler',
                    'nisn' => '0081234501',
                    'nik' => '5301012345670001',
                    'tempat_lahir' => 'Kupang',
                    'tgl_lahir' => '2008-05-15',
                    'jenis_kelamin' => 'L',
                    'alamat' => 'Jl. El Tari No. 10, Oebobo, Kupang',
                    'agama' => 'Kristen Protestan',
                    'nomor_hp' => '081234567801',
                    'asal_sekolah' => 'SMAN 1 Kupang',
                    'tahun_lulus' => 2026,
                    'nama_ayah' => 'Agus Santoso',
                    'status_ayah' => 'Hidup',
                    'pekerjaan_ayah' => 'PNS',
                    'nama_ibu' => 'Lina Marlina',
                    'status_ibu' => 'Hidup',
                    'pekerjaan_ibu' => 'Guru',
                    'pilihan_prodi_1' => 'TI',
                    'pilihan_prodi_2' => 'SI',
                    'prodi_diterima' => 'TI',
                    'status_pilihan_1' => 'lulus',
                    'status_pendaftaran' => 'lulus',
                    'status_pembayaran' => 'lunas',
                    'is_locked' => 1,
                    'is_synced' => 0,
                    'nilai_ujian' => 85,
                ]
            ],
            [
                'user' => [
                    'name' => 'Siti Aminah',
                    'email' => 'siti.aminah@email.com',
                    'nomor_hp' => '081234567802',
                    'role' => 'camaba',
                    'password' => $password,
                ],
                'pendaftar' => [
                    'jalur_pendaftaran' => 'prestasi',
                    'nisn' => '0081234502',
                    'nik' => '5301012345670002',
                    'tempat_lahir' => 'Waingapu',
                    'tgl_lahir' => '2008-08-20',
                    'jenis_kelamin' => 'P',
                    'alamat' => 'Jl. Ahmad Yani No. 5, Waingapu',
                    'agama' => 'Islam',
                    'nomor_hp' => '081234567802',
                    'asal_sekolah' => 'SMAN 2 Waingapu',
                    'tahun_lulus' => 2026,
                    'nama_ayah' => 'Ahmad Fauzi',
                    'status_ayah' => 'Hidup',
                    'pekerjaan_ayah' => 'Wiraswasta',
                    'nama_ibu' => 'Nurhayati',
                    'status_ibu' => 'Hidup',
                    'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                    'pilihan_prodi_1' => 'SI',
                    'pilihan_prodi_2' => 'MI',
                    'prodi_diterima' => 'SI',
                    'status_pilihan_1' => 'lulus',
                    'status_pendaftaran' => 'lulus',
                    'status_pembayaran' => 'lunas',
                    'is_locked' => 1,
                    'is_synced' => 0,
                    'nilai_ujian' => 92,
                ]
            ],
            [
                'user' => [
                    'name' => 'Yohanes Raba',
                    'email' => 'yohanes.raba@email.com',
                    'nomor_hp' => '081234567803',
                    'role' => 'camaba',
                    'password' => $password,
                ],
                'pendaftar' => [
                    'jalur_pendaftaran' => 'mandiri',
                    'nisn' => '0081234503',
                    'nik' => '5301012345670003',
                    'tempat_lahir' => 'Tambolaka',
                    'tgl_lahir' => '2007-12-10',
                    'jenis_kelamin' => 'L',
                    'alamat' => 'Jl. Karya Kasih, Tambolaka',
                    'agama' => 'Katolik',
                    'nomor_hp' => '081234567803',
                    'asal_sekolah' => 'SMKN 1 Tambolaka',
                    'tahun_lulus' => 2025,
                    'nama_ayah' => 'Markus Raba',
                    'status_ayah' => 'Hidup',
                    'pekerjaan_ayah' => 'Petani',
                    'nama_ibu' => 'Yosefina Bili',
                    'status_ibu' => 'Hidup',
                    'pekerjaan_ibu' => 'Petani',
                    'pilihan_prodi_1' => 'BD',
                    'pilihan_prodi_2' => 'TI',
                    'prodi_diterima' => 'BD',
                    'status_pilihan_1' => 'lulus',
                    'status_pendaftaran' => 'lulus',
                    'status_pembayaran' => 'lunas',
                    'is_locked' => 1,
                    'is_synced' => 0,
                    'nilai_ujian' => 78,
                ]
            ],
            [
                'user' => [
                    'name' => 'Maria Goreti',
                    'email' => 'maria.goreti@email.com',
                    'nomor_hp' => '081234567804',
                    'role' => 'camaba',
                    'password' => $password,
                ],
                'pendaftar' => [
                    'jalur_pendaftaran' => 'reguler',
                    'nisn' => '0081234504',
                    'nik' => '5301012345670004',
                    'tempat_lahir' => 'Waikabubak',
                    'tgl_lahir' => '2008-03-25',
                    'jenis_kelamin' => 'P',
                    'alamat' => 'Kampung Baru, Waikabubak',
                    'agama' => 'Kristen Protestan',
                    'nomor_hp' => '081234567804',
                    'asal_sekolah' => 'SMA Katolik Andaluri',
                    'tahun_lulus' => 2026,
                    'nama_ayah' => 'Stevanus Kulla',
                    'status_ayah' => 'Meninggal',
                    'pekerjaan_ayah' => null,
                    'nama_ibu' => 'Marlina Dappa',
                    'status_ibu' => 'Hidup',
                    'pekerjaan_ibu' => 'Pedagang',
                    'pilihan_prodi_1' => 'K3',
                    'pilihan_prodi_2' => 'ARS',
                    'prodi_diterima' => 'K3',
                    'status_pilihan_1' => 'lulus',
                    'status_pendaftaran' => 'lulus',
                    'status_pembayaran' => 'lunas',
                    'is_locked' => 1,
                    'is_synced' => 0,
                    'nilai_ujian' => 88,
                ]
            ],
            [
                'user' => [
                    'name' => 'Petrus Lende',
                    'email' => 'petrus.lende@email.com',
                    'nomor_hp' => '081234567805',
                    'role' => 'camaba',
                    'password' => $password,
                ],
                'pendaftar' => [
                    'jalur_pendaftaran' => 'reguler',
                    'nisn' => '0081234505',
                    'nik' => '5301012345670005',
                    'tempat_lahir' => 'Waikabubak',
                    'tgl_lahir' => '2008-07-07',
                    'jenis_kelamin' => 'L',
                    'alamat' => 'Jl. Bhayangkara, Waikabubak',
                    'agama' => 'Kristen Protestan',
                    'nomor_hp' => '081234567805',
                    'asal_sekolah' => 'SMAN 1 Waikabubak',
                    'tahun_lulus' => 2026,
                    'nama_ayah' => 'Daud Lende',
                    'status_ayah' => 'Hidup',
                    'pekerjaan_ayah' => 'PNS',
                    'nama_ibu' => 'Rambu Milla',
                    'status_ibu' => 'Hidup',
                    'pekerjaan_ibu' => 'Perawat',
                    'pilihan_prodi_1' => 'TI',
                    'pilihan_prodi_2' => 'SI',
                    'prodi_diterima' => 'TI',
                    'status_pilihan_1' => 'lulus',
                    'status_pendaftaran' => 'lulus',
                    'status_pembayaran' => 'lunas',
                    'is_locked' => 1,
                    'is_synced' => 0,
                    'nilai_ujian' => 81,
                ]
            ]
        ];

        DB::beginTransaction();
        try {
            foreach ($camabas as $data) {
                // 1. Buat User menggunakan updateOrCreate agar tidak duplikat jika di-run 2x
                $user = User::updateOrCreate(
                    ['email' => $data['user']['email']],
                    $data['user']
                );

                // 2. Siapkan data Pendaftar
                $pendaftarData = $data['pendaftar'];
                $pendaftarData['user_id'] = $user->id;
                $pendaftarData['created_at'] = $now;
                $pendaftarData['updated_at'] = $now;

                // 3. Masukkan ke tabel Pendaftars
                DB::table('pendaftars')->updateOrInsert(
                    ['user_id' => $user->id],
                    $pendaftarData
                );
            }
            DB::commit();
            $this->command->info('Berhasil menginjeksi 5 data dummy Calon Mahasiswa Lulus & Lunas!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Gagal menjalankan Seeder: ' . $e->getMessage());
        }
    }
}