<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UjianJawaban extends Model
{
    protected $fillable = [
        'ujian_peserta_id', 
        'ujian_soal_id', 
        'ujian_pilihan_id'
    ];

    public function peserta()
    {
        return $this->belongsTo(UjianPeserta::class, 'ujian_peserta_id');
    }

    public function soal()
    {
        return $this->belongsTo(UjianSoal::class, 'ujian_soal_id');
    }

    public function pilihan()
    {
        return $this->belongsTo(UjianPilihan::class, 'ujian_pilihan_id');
    }
}