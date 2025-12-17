<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa pelakunya
            $table->string('action'); // Apa tindakannya (UPDATE, CREATE, DELETE, LOGIN)
            $table->string('subject'); // Objeknya (Misal: Pendaftar #1001)
            $table->text('description')->nullable(); // Detail perubahan (Misal: Status Gagal -> Lulus)
            $table->string('ip_address')->nullable(); // IP Address pelakunya
            $table->string('user_agent')->nullable(); // Browser/Device
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};