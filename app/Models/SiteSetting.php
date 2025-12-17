<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'bank_accounts' => 'array',     // Cast bank_accounts sebagai array
    ];
}
