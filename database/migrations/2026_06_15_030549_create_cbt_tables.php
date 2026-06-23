<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel Pengaturan Ujian
        Schema::create('ujian_pakets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ujian');
            $table->integer('durasi_menit');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Tabel Bank Soal
        Schema::create('ujian_soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_paket_id')->constrained()->cascadeOnDelete();
            $table->text('pertanyaan');
            $table->timestamps();
        });

        // 3. Tabel Pilihan Jawaban
        Schema::create('ujian_pilihans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_soal_id')->constrained()->cascadeOnDelete();
            $table->string('teks_pilihan');
            $table->boolean('is_benar')->default(false);
            $table->timestamps();
        });

        // 4. Tabel Sesi Peserta (Relasi ke pendaftars)
        Schema::create('ujian_pesertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftars')->cascadeOnDelete();
            $table->foreignId('ujian_paket_id')->constrained('ujian_pakets');
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->enum('status', ['belum', 'mengerjakan', 'selesai'])->default('belum');
            $table->integer('skor_akhir')->default(0);
            $table->timestamps();
        });

        // 5. Tabel Jawaban Realtime
        Schema::create('ujian_jawabans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_peserta_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ujian_soal_id')->constrained();
            $table->foreignId('ujian_pilihan_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_tables');
    }
};
