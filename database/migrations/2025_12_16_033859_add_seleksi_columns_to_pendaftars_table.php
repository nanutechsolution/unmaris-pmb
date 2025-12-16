<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            // Kolom untuk Jadwal Ujian
            $table->dateTime('jadwal_ujian')->nullable()->after('status_pendaftaran');
            $table->string('lokasi_ujian')->nullable()->after('jadwal_ujian'); // Misal: "Lab Komputer 1" atau "Online"
            
            // Kolom untuk Hasil Seleksi
            $table->integer('nilai_ujian')->default(0)->after('lokasi_ujian');
            $table->text('catatan_penguji')->nullable()->after('nilai_ujian');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->dropColumn(['jadwal_ujian', 'lokasi_ujian', 'nilai_ujian', 'catatan_penguji']);
        });
    }
};