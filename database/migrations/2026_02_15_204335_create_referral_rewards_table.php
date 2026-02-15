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
        Schema::create('referral_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained()->onDelete('cascade');
            $table->foreignId('referral_scheme_id')->constrained()->onDelete('cascade');
            $table->integer('reward_amount');
            $table->enum('status', ['eligible', 'paid', 'cancelled'])->default('eligible');
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_rewards');
    }
};
