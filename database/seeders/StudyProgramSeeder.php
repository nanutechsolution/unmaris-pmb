<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudyProgram;

class StudyProgramSeeder extends Seeder
{
    public function run(): void
    {
        $prodis = [
            ['name' => 'Teknik Informatika', 'degree' => 'S1'],
            ['name' => 'Manajemen Informatika', 'degree' => 'D3'],
            ['name' => 'Teknik Lingkungan', 'degree' => 'S1'],
            ['name' => 'Bisnis Digital', 'degree' => 'S1'],
            ['name' => 'Administrasi Rumah Sakit', 'degree' => 'S1'],
            ['name' => 'Keselamatan dan Kesehatan Kerja', 'degree' => 'S1'],
            ['name' => 'Pendidikan Teknologi Informasi', 'degree' => 'S1'],
        ];

        foreach ($prodis as $prodi) {
            StudyProgram::create($prodi);
        }
    }
}