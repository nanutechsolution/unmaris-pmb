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
        Schema::create('referral_schemes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('jalur')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('reward_amount');
            $table->integer('target_min')->default(1);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_schemes');
    }
};
