<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\DB;

class RekapLulusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = storage_path('app/rekap_lulus.csv');

        if (!file_exists($csvFile)) {
            $this->command->error('File rekap_lulus.csv tidak ditemukan di folder storage/app!');
            return;
        }

        $fileHandle = fopen($csvFile, 'r');
        
        // Melewati baris pertama (header: EMAIL, PILIHAN PRODI 1, PILIHAN PRODI 2, KET)
        fgetcsv($fileHandle); 

        DB::beginTransaction();

        try {
            $count = 0;
            
            while (($row = fgetcsv($fileHandle, 1000, ',')) !== FALSE) {
                $email   = trim($row[0]);
                $prodi1  = trim($row[1]);
                $prodi2  = trim($row[2]);
                $ket     = strtoupper(trim($row[3]));
                
                if (empty($email)) continue;

                // Cari data user berdasarkan email
                $user = User::where('email', $email)->first();

                if ($user) {
                    $prodiDiterima  = null;
                    $statusPilihan1 = 'pending';
                    $statusPilihan2 = 'pending';

                    // Menentukan status kelulusan berdasarkan kolom KET
                    if ($ket === 'LULUS') {
                        $prodiDiterima  = $prodi1;
                        $statusPilihan1 = 'lulus';
                        $statusPilihan2 = 'tidak_lulus';
                    } elseif (strpos($ket, 'PILIHAN PRODI 2') !== false) {
                        $prodiDiterima  = $prodi2;
                        $statusPilihan1 = 'tidak_lulus';
                        $statusPilihan2 = 'lulus';
                    }

                    // Update data pendaftar
                    Pendaftar::where('user_id', $user->id)->update([
                        'pilihan_prodi_1'    => $prodi1,
                        'pilihan_prodi_2'    => $prodi2,
                        'prodi_diterima'     => $prodiDiterima,
                        'status_pilihan_1'   => $statusPilihan1,
                        'status_pilihan_2'   => $statusPilihan2,
                        'status_pendaftaran' => 'lulus',
                        'jadwal_ujian'       => '2026-06-17 08:00:00',
                        'nilai_ujian'        => rand(75, 100),
                        'jadwal_wawancara'   => '2026-06-17 13:00:00',
                        'nilai_wawancara'    => rand(75, 100),
                    ]);

                    $count++;
                }
            }

            DB::commit();
            $this->command->info("Pembaruan selesai! Berhasil memperbarui data untuk {$count} pendaftar.");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Terjadi kesalahan saat memproses data: ' . $e->getMessage());
        } finally {
            fclose($fileHandle);
        }
    }
}