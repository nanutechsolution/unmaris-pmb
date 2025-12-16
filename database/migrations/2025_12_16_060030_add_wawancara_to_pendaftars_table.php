<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            // Kolom Wawancara
            $table->dateTime('jadwal_wawancara')->nullable()->after('catatan_penguji');
            $table->string('pewawancara')->nullable()->after('jadwal_wawancara'); // Nama Dosen/Staff
            $table->integer('nilai_wawancara')->default(0)->after('pewawancara');
            $table->text('catatan_wawancara')->nullable()->after('nilai_wawancara');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->dropColumn(['jadwal_wawancara', 'pewawancara', 'nilai_wawancara', 'catatan_wawancara']);
        });
    }
};