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
            $table->string('rekomendasi_prodi')->nullable()->after('catatan_wawancara');
            $table->text('catatan_seleksi')->nullable()->after('rekomendasi_prodi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->dropColumn([
                'rekomendasi_prodi',
                'catatan_seleksi',
            ]);
        });
    }
};
