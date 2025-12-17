<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // Kolom JSON untuk menyimpan banyak rekening sekaligus
            $table->json('bank_accounts')->nullable()->after('biaya_pendaftaran');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('bank_accounts');
        });
    }
};