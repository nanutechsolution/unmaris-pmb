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
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->string('asal_sekolah')->nullable()->change();
            $table->year('tahun_lulus')->nullable()->change();
            $table->string('nama_ayah')->nullable()->change();
            $table->string('nama_ibu')->nullable()->change();
            $table->string('pilihan_prodi_1')->nullable()->change();
            // Pastikan field lain yang diisi setelah step 1 juga di-set nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
