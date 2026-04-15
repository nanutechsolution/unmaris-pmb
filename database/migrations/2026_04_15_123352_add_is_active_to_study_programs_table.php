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
    Schema::table('study_programs', function (Blueprint $table) {
        // Menambahkan kolom is_active dengan default true (aktif)
        $table->boolean('is_active')->default(true)->after('degree');
    });
}

public function down()
{
    Schema::table('study_programs', function (Blueprint $table) {
        $table->dropColumn('is_active');
    });
}
};
