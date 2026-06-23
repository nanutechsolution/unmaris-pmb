<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UjianPaket extends Model
{
    protected $fillable = [
        'nama_ujian', 
        'durasi_menit', 
        'is_active'
    ];

    public function soals()
    {
        return $this->hasMany(UjianSoal::class);
    }

    public function pesertas()
    {
        return $this->hasMany(UjianPeserta::class);
    }
}