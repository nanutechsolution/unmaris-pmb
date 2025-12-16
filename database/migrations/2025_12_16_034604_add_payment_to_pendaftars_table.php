<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            // Status Pembayaran: belum, review (sudah upload), lunas, tolak
            $table->enum('status_pembayaran', ['belum_bayar', 'menunggu_verifikasi', 'lunas', 'ditolak'])
                  ->default('belum_bayar')
                  ->after('status_pendaftaran');
                  
            $table->string('bukti_pembayaran')->nullable()->after('status_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::table('pendaftars', function (Blueprint $table) {
            $table->dropColumn(['status_pembayaran', 'bukti_pembayaran']);
        });
    }
};