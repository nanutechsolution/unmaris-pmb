<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gelombang extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
        'is_active' => 'boolean',
    ];

    public static function aktifSaatIni()
    {
        return self::where('tgl_mulai', '<=', now())
            ->where('tgl_selesai', '>=', now())
            ->first();
    }
}
