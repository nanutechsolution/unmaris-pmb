<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UjianSoal extends Model
{
    protected $fillable = [
        'ujian_paket_id', 
        'pertanyaan'
    ];

    public function paket()
    {
        return $this->belongsTo(UjianPaket::class, 'ujian_paket_id');
    }

    public function pilihans()
    {
        return $this->hasMany(UjianPilihan::class);
    }
}