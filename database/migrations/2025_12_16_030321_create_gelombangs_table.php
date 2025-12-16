<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gelombangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_gelombang'); // Contoh: "Gelombang 1 (Dini)"
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->boolean('is_active')->default(false); // Hanya 1 yang boleh true nanti
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gelombangs');
    }
};