<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FacilitySlide;
use Illuminate\Support\Facades\DB;

class FacilitySlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel sebelum isi ulang (agar tidak duplikat saat seeding ulang)
        DB::table('facility_slides')->truncate();

        $slides = [
            [
                'title' => 'Gedung St. Alexander',
                'description' => 'Gedung perkuliahan 4 lantai terbaru kebanggaan UNMARIS. Dilengkapi ruang kelas ber-AC, lift, dan area diskusi terbuka.',
                'icon' => '🏢',
                'images' => [
                    'https://placehold.co/800x600/1e3a8a/FFF?text=Gedung+Tampak+Depan',
                    'https://placehold.co/800x600/172554/FFF?text=Lobby+Utama',
                    'https://placehold.co/800x600/1e40af/FFF?text=Ruang+Kelas+Modern'
                ],
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Laboratorium Lengkap',
                'description' => 'Fasilitas praktikum terlengkap di Sumba: Lab Multimedia, Hardware, Jaringan, K3, Simulasi RS, dan Teknik Lingkungan.',
                'icon' => '🔬',
                'images' => [
                    'https://placehold.co/800x600/eab308/000?text=Lab+Multimedia',
                    'https://placehold.co/800x600/ca8a04/000?text=Lab+Jaringan+Komputer',
                    'https://placehold.co/800x600/a16207/000?text=Lab+K3+Safety',
                    'https://placehold.co/800x600/854d0e/000?text=Lab+Simulasi+RS'
                ],
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Kampus Digital',
                'description' => 'Sistem akademik terintegrasi (SIAKAD), Perpustakaan Digital, dan Internet Wi-Fi kecepatan tinggi di seluruh area kampus.',
                'icon' => '💻',
                'images' => [
                    'https://placehold.co/800x600/22c55e/FFF?text=Sistem+Akademik',
                    'https://placehold.co/800x600/15803d/FFF?text=Perpustakaan+Digital',
                    'https://placehold.co/800x600/166534/FFF?text=Free+WiFi+Zone'
                ],
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Sarana Olahraga',
                'description' => 'Menjunjung tinggi sportivitas dengan lapangan Futsal, Voli, dan Bulutangkis standar nasional untuk kegiatan mahasiswa.',
                'icon' => '⚽',
                'images' => [
                    'https://placehold.co/800x600/ef4444/FFF?text=Lapangan+Futsal',
                    'https://placehold.co/800x600/b91c1c/FFF?text=Lapangan+Voli',
                    'https://placehold.co/800x600/991b1b/FFF?text=GOR+Serbaguna'
                ],
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $slide) {
            FacilitySlide::create($slide);
        }
    }
}