<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kampus')->default('Universitas Stella Maris Sumba');
            $table->string('singkatan_kampus')->default('UNMARIS');
            $table->string('alamat_kampus')->nullable();
            
            // Info Pendaftaran
            $table->integer('biaya_pendaftaran')->default(200000);
            $table->string('nama_bank')->default('BRI');
            $table->string('nomor_rekening')->default('1234-5678-9000');
            $table->string('atas_nama_rekening')->default('Yayasan UNMARIS');
            
            // Kontak
            $table->string('no_wa_admin')->default('6281234567890');
            $table->string('email_admin')->default('pmb@unmaris.ac.id');
            
            $table->timestamps();
        });

        // Insert Default Data (Hanya 1 baris)
        DB::table('site_settings')->insert([
            'alamat_kampus' => 'Jl. Karya Kasih No. 5, Tambolaka – Kab. Sumba Barat Daya.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};