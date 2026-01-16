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
            $table->enum('status_pilihan_1', ['pending', 'lulus', 'tidak_lulus'])
                ->default('pending')
                ->after('pilihan_prodi_1');

            $table->enum('status_pilihan_2', ['pending', 'lulus', 'tidak_lulus'])
                ->default('pending')
                ->after('pilihan_prodi_2');

            $table->string('prodi_diterima')
                ->nullable()
                ->after('pilihan_prodi_2');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->dropColumn([
                'status_pilihan_1',
                'status_pilihan_2',
                'prodi_diterima'
            ]);
        });
    }
};
