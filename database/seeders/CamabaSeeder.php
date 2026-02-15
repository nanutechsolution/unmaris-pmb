<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Pendaftar;
use App\Models\ReferralScheme;
use App\Models\ReferralReward;

class CamabaSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | 1. REFERRAL SCHEME (PERIODE AKTIF)
            |--------------------------------------------------------------------------
            */

            $scheme = ReferralScheme::updateOrCreate(
                ['name' => 'Reward Reguler 2026'],
                [
                    'reward_amount' => 100000,
                    'start_date' => now()->startOfYear(),
                    'end_date' => now()->endOfYear(),
                    'is_active' => true,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | 2. CAMABA 1
            |--------------------------------------------------------------------------
            */

            $user1 = User::updateOrCreate(
                ['email' => 'ahmad.fauzan@example.com'],
                [
                    'name' => 'Ahmad Fauzan',
                    'password' => Hash::make('password'),
                    'role' => 'camaba',
                    'nomor_hp' => '081234567890',
                ]
            );

            $pendaftar1 = Pendaftar::updateOrCreate(
                ['user_id' => $user1->id],
                [
                    'jalur_pendaftaran' => 'reguler',
                    'tempat_lahir' => 'Makassar',
                    'tgl_lahir' => '2004-05-10',
                    'jenis_kelamin' => 'L',
                    'alamat' => 'Jl. Veteran No. 10',
                    'agama' => 'Islam',
                    'asal_sekolah' => 'SMA Negeri 1 Makassar',
                    'tahun_lulus' => 2023,
                    'nama_ayah' => 'Budi Santoso',
                    'nama_ibu' => 'Siti Aminah',
                    'pilihan_prodi_1' => 'Teknik Informatika',
                    'status_pendaftaran' => 'submit',
                    'status_pembayaran' => 'lunas',
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | 3. CAMABA 2
            |--------------------------------------------------------------------------
            */

            $user2 = User::updateOrCreate(
                ['email' => 'siti.rahma@example.com'],
                [
                    'name' => 'Siti Rahma',
                    'password' => Hash::make('password'),
                    'role' => 'camaba',
                    'nomor_hp' => '089876543210',
                ]
            );

            $pendaftar2 = Pendaftar::updateOrCreate(
                ['user_id' => $user2->id],
                [
                    'jalur_pendaftaran' => 'reguler',
                    'tempat_lahir' => 'Parepare',
                    'tgl_lahir' => '2005-01-15',
                    'jenis_kelamin' => 'P',
                    'alamat' => 'Jl. Ahmad Yani No. 5',
                    'agama' => 'Islam',
                    'asal_sekolah' => 'SMA Negeri 2 Parepare',
                    'tahun_lulus' => 2023,
                    'nama_ayah' => 'Haris Abdullah',
                    'nama_ibu' => 'Nurhayati',
                    'pilihan_prodi_1' => 'Manajemen',
                    'status_pendaftaran' => 'submit',
                    'status_pembayaran' => 'belum_bayar',
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | 4. REFERRAL REWARDS
            |--------------------------------------------------------------------------
            */

            // Reward 1 - SUDAH DIBAYAR
            ReferralReward::updateOrCreate(
                ['pendaftar_id' => $pendaftar1->id],
                [
                    'referral_scheme_id' => $scheme->id,
                    'reward_amount' => 100000,
                    'status' => 'paid',
                    'paid_at' => now(),
                    'processed_by' => 1, // pastikan admin id=1 ada
                ]
            );

            // Reward 2 - MASIH ELIGIBLE
            ReferralReward::updateOrCreate(
                ['pendaftar_id' => $pendaftar2->id],
                [
                    'referral_scheme_id' => $scheme->id,
                    'reward_amount' => 100000,
                    'status' => 'eligible',
                    'paid_at' => null,
                    'processed_by' => null,
                ]
            );

        });
    }
}
