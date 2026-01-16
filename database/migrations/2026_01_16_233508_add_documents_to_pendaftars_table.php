<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            // Menambahkan kolom untuk file KTP dan Akta setelah foto
            $table->string('ktp_path')->nullable()->after('foto_path');
            $table->string('akta_path')->nullable()->after('ktp_path');
            
            // Menambahkan jenis dokumen (Ijazah/SKL) dan Transkrip
            $table->enum('jenis_dokumen', ['ijazah', 'skl'])->default('ijazah')->after('ijazah_path');
            $table->string('transkrip_path')->nullable()->after('jenis_dokumen');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->dropColumn(['ktp_path', 'akta_path', 'jenis_dokumen', 'transkrip_path']);
        });
    }
};