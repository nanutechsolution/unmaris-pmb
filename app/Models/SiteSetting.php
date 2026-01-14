<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'bank_accounts' => 'array',
        'admin_contacts' => 'array', // Tambahan baru
        'biaya_pendaftaran' => 'integer',
    ];
}
