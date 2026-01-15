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
        Schema::create('facility_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul Fasilitas (Misal: Gedung St. Alexander)
            $table->text('description'); // Deskripsi singkat
            $table->string('icon')->nullable(); // Emoji atau class icon (Misal: 🏢)
            
            // Kolom JSON untuk menyimpan array foto (Unlimited photos)
            $table->json('images')->nullable(); 
            $table->integer('sort_order')->default(0); // Untuk urutan tampilan
            $table->boolean('is_active')->default(true); // Untuk sembunyikan/tampilkan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facility_slides');
    }
};