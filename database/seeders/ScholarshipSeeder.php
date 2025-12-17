<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Scholarship;
use Carbon\Carbon;

class ScholarshipSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $scholarships = [
            [
                'name' => 'KIP Kuliah Merdeka 2025',
                'description' => 'Program bantuan biaya pendidikan dari pemerintah bagi lulusan SMA/SMK yang memiliki potensi akademik baik tetapi memiliki keterbatasan ekonomi. Wajib melampirkan Kartu KIP / SKTM.',
                'quota' => 100,
                'start_date' => $now->copy()->startOfYear(), // Mulai awal tahun
                'end_date' => $now->copy()->month(8)->endOfMonth(), // Sampai Agustus
                'is_active' => true,
            ],
            [
                'name' => 'Beasiswa Yayasan UNMARIS',
                'description' => 'Potongan biaya SPP sebesar 50% selama 2 semester pertama bagi putra-putri daerah Sumba yang berprestasi (Ranking 1-5 di Kelas).',
                'quota' => 50,
                'start_date' => $now->copy(),
                'end_date' => $now->copy()->addMonths(3),
                'is_active' => true,
            ],
            [
                'name' => 'Beasiswa Prestasi Non-Akademik',
                'description' => 'Diberikan kepada calon mahasiswa yang memiliki sertifikat juara minimal tingkat Kabupaten/Kota di bidang Olahraga atau Seni.',
                'quota' => 20,
                'start_date' => $now->copy(),
                'end_date' => $now->copy()->addMonths(4),
                'is_active' => true,
            ],
            [
                'name' => 'Beasiswa Khusus Yatim/Piatu',
                'description' => 'Bantuan khusus bagi calon mahasiswa yatim/piatu dengan rekomendasi dari Tokoh Agama setempat.',
                'quota' => 15,
                'start_date' => $now->copy(),
                'end_date' => $now->copy()->addMonths(5),
                'is_active' => false, // Contoh status non-aktif (kuota penuh/tutup)
            ],
        ];

        foreach ($scholarships as $s) {
            Scholarship::create($s);
        }
    }
}