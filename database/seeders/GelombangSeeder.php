<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gelombang;
use Carbon\Carbon;

class GelombangSeeder extends Seeder
{
    public function run()
    {
        $gelombangs = [
            [
                'nama' => 'Gelombang 1',
                'tgl_mulai' => Carbon::create(2026, 1, 5),
                'tgl_selesai' => Carbon::create(2026, 3, 30),
                'is_active' => true, // gelombang saat ini aktif
            ],
            [
                'nama' => 'Gelombang 2',
                'tgl_mulai' => Carbon::create(2026, 4, 1),
                'tgl_selesai' => Carbon::create(2026, 6, 30),
                'is_active' => false,
            ],
            [
                'nama' => 'Gelombang 3',
                'tgl_mulai' => Carbon::create(2026, 7, 1),
                'tgl_selesai' => Carbon::create(2026, 8, 30),
                'is_active' => false,
            ],
        ];

        foreach ($gelombangs as $gelombang) {
            Gelombang::create($gelombang);
        }
    }
}
