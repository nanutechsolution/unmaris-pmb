<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 1 Akun Admin
        User::create([
            'name' => 'Administrator PMB',
            'email' => 'admin@unmaris.ac.id',
            'nomor_hp' => '081234567890',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        // Buat 1 Akun Camaba Dummy
        User::create([
            'name' => 'Calon Mahasiswa 1',
            'email' => 'camaba@gmail.com',
            'nomor_hp' => '089876543210',
            'role' => 'camaba',
            'password' => Hash::make('password'),
        ]);

            $this->call([DummyPendaftarLulusSeeder::class]);
    }
}