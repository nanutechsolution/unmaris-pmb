<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            // Simpan array: [{'name': 'Pak Yolen', 'phone': '628...'}]
            $table->json('admin_contacts')->nullable()->after('no_wa_admin');
        });
    }

    public function down()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('admin_contacts');
        });
    }
};
