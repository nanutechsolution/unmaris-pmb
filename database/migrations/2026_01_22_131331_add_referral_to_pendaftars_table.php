<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            // Dari mana tahu info kampus? (Medsos, Teman, Brosur, dll)
            $table->string('sumber_informasi')->nullable()->after('nomor_hp');

            // Siapa yang merekomendasikan? (Untuk perhitungan komisi)
            $table->string('nama_referensi')->nullable()->after('sumber_informasi');
        });
    }

    public function down()
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->dropColumn(['sumber_informasi', 'nama_referensi']);
        });
    }
};
