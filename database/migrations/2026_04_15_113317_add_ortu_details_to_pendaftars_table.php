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
            $table->string('nik_ayah', 20)->nullable()->after('nama_ayah');
            $table->enum('status_ayah', ['Hidup', 'Meninggal'])->default('Hidup')->after('nik_ayah');
            $table->string('nik_ibu', 20)->nullable()->after('pekerjaan_ayah');
            $table->enum('status_ibu', ['Hidup', 'Meninggal'])->default('Hidup')->after('nik_ibu');
            $table->string('pendidikan_ayah', 50)->nullable()->after('status_ayah');
            $table->string('pendidikan_ibu', 50)->nullable()->after('status_ibu');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->dropColumn([
                'nik_ayah',
                'status_ayah',
                'pendidikan_ayah',
                'nik_ibu',
                'status_ibu',
                'pendidikan_ibu'
            ]);
        });
    }
};
