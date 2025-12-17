<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Master Beasiswa
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: KIP-Kuliah
            $table->text('description')->nullable(); // Syarat & Ketentuan
            $table->integer('quota')->default(0); // Kuota Penerima
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tambah Relasi di Tabel Pendaftar
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->foreignId('scholarship_id')->nullable()->after('jalur_pendaftaran')
                  ->constrained('scholarships')->nullOnDelete();
            
            // File Pendukung Khusus Beasiswa (Slip Gaji / SKTM)
            $table->string('file_beasiswa')->nullable()->after('ijazah_path');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->dropForeign(['scholarship_id']);
            $table->dropColumn(['scholarship_id', 'file_beasiswa']);
        });
        Schema::dropIfExists('scholarships');
    }
};