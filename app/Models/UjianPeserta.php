<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UjianPeserta extends Model
{
    protected $fillable = [
        'pendaftar_id', 
        'ujian_paket_id', 
        'waktu_mulai', 
        'waktu_selesai', 
        'status', 
        'skor_akhir'
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    // Relasi ke tabel bawaan sistem PMB Anda
    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'pendaftar_id');
    }

    public function paket()
    {
        return $this->belongsTo(UjianPaket::class, 'ujian_paket_id');
    }

    public function jawabans()
    {
        return $this->hasMany(UjianJawaban::class);
    }
}