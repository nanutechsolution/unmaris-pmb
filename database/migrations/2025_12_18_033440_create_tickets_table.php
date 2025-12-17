<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Tiket Utama
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pemilik Tiket
            $table->string('subject'); // Judul Masalah
            $table->enum('category', ['umum', 'pembayaran', 'berkas', 'akun'])->default('umum');
            $table->enum('status', ['open', 'answered', 'closed'])->default('open'); // Open=Baru, Answered=Dibalas Admin, Closed=Selesai
            $table->timestamps();
        });

        // Tabel Percakapan (Balasan)
        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users'); // Pengirim pesan (Bisa Camaba atau Admin)
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('tickets');
    }
};