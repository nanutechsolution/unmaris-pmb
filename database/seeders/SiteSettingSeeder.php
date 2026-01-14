<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel agar data bersih
        DB::table('site_settings')->truncate();

        SiteSetting::create([
            'nama_kampus' => 'Universitas Stella Maris Sumba',
            'singkatan_kampus' => 'UNMARIS',
            'alamat_kampus' => 'Jl. Soekarno Hatta No.05, Tambolaka, NTT',
            'biaya_pendaftaran' => 200000,

            // Set default kontak utama ke Pak Yolen
            'no_wa_admin' => '6281216156883',
            'email_admin' => 'pmb@unmaris.ac.id',

            // Data Bank Default (Placeholder)
            'bank_accounts' => [
                [
                    'bank' => 'BRI',
                    'rekening' => '0000-0000-0000',
                    'atas_nama' => 'Yayasan Pendidikan St. Yosef Freinademetz'
                ]
            ],

            // KONTAK PANITIA (DATA REAL)
            'admin_contacts' => [
                [
                    'name' => 'Pak Yolen',
                    'phone' => '6281216156883'
                ],
                [
                    'name' => 'Ibu Ritha',
                    'phone' => '6281237584754'
                ],
                [
                    'name' => 'Pak Alex Popo',
                    'phone' => '6282122139190'
                ],
                [
                    'name' => 'Ibu Ningsi',
                    'phone' => '6282139649085'
                ]
            ],

            // Legacy Data Support
            'nama_bank' => 'BRI',
            'nomor_rekening' => '0000-0000-0000',
            'atas_nama_rekening' => 'Yayasan Pendidikan St. Yosef Freinademetz',
        ]);
    }
}
