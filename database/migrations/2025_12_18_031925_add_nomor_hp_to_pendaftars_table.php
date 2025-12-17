<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            // Menambahkan kolom nomor_hp setelah kolom agama
            $table->string('nomor_hp', 20)->nullable()->after('agama');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->dropColumn('nomor_hp');
        });
    }
};