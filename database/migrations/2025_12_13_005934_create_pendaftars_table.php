<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Step 1: Biodata Diri
            $table->string('nisn', 20)->unique()->nullable();
            $table->string('nik', 20)->nullable();
            $table->string('tempat_lahir');
            $table->date('tgl_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat');
            $table->string('agama');

            // Step 2: Asal Sekolah & Orang Tua
            $table->string('asal_sekolah');
            $table->year('tahun_lulus');
            $table->string('nama_ayah');
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('nama_ibu');
            $table->string('pekerjaan_ibu')->nullable();

            // Step 3: Pilihan Prodi
            // Nanti bisa direlasikan ke tabel prodi, sekarang kita string dulu biar cepat
            $table->string('pilihan_prodi_1'); 
            $table->string('pilihan_prodi_2')->nullable();

            // System Status
            $table->enum('status_pendaftaran', ['draft', 'submit', 'verifikasi', 'lulus', 'gagal'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftars');
    }
};