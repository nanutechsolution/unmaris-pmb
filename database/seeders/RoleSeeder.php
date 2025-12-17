<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Staff Keuangan
        User::create([
            'name' => 'Staf Keuangan',
            'email' => 'keuangan@unmaris.ac.id',
            'role' => 'keuangan', // Role baru
            'password' => Hash::make('password'),
            'nomor_hp' => '0811111111',
            'email_verified_at' => now(),
        ]);

        // Staff Akademik
        User::create([
            'name' => 'Panitia Seleksi',
            'email' => 'akademik@unmaris.ac.id',
            'role' => 'akademik', // Role baru
            'password' => Hash::make('password'),
            'nomor_hp' => '0822222222',
            'email_verified_at' => now(),
        ]);
    }
} 