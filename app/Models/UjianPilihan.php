<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UjianPilihan extends Model
{
    protected $fillable = [
        'ujian_soal_id', 
        'teks_pilihan', 
        'is_benar'
    ];

    // Sembunyikan kolom 'is_benar' secara default saat di-return sebagai array/JSON
    // Ini proteksi ekstra agar kunci jawaban tidak bocor ke frontend
    protected $hidden = [
        'is_benar'
    ];

    // Cast tipe data agar is_benar terbaca sebagai boolean murni
    protected $casts = [
        'is_benar' => 'boolean'
    ];

    public function soal()
    {
        return $this->belongsTo(UjianSoal::class, 'ujian_soal_id');
    }
}