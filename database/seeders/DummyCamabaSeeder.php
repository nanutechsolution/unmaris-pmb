<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DummyCamabaSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        // 1. Akun Camaba Spesifik (Mudah diingat untuk Login)
        User::create([
            'name' => 'Andi Calon Mahasiswa',
            'email' => 'andi@unmaris.test', // Login pakai ini
            'nomor_hp' => '081234567890',
            'role' => 'camaba',
            'password' => Hash::make('password'), // Password: password
            'email_verified_at' => now(), // Langsung verified (bypass email)
        ]);

        User::create([
            'name' => 'Siti Calon Mahasiswa',
            'email' => 'siti@unmaris.test',
            'nomor_hp' => '082198765432',
            'role' => 'camaba',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // 2. Generate 5 Akun Random Tambahan
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => "camaba{$i}@unmaris.test", // camaba1@unmaris.test, dst
                'nomor_hp' => $faker->phoneNumber,
                'role' => 'camaba',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }
    }
}