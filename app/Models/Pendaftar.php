<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Casting tipe data otomatis
    protected $casts = [
        'jadwal_ujian' => 'datetime',
        'jadwal_wawancara' => 'datetime',
        'tgl_lahir' => 'date',
        'is_locked' => 'boolean',
        'is_synced' => 'boolean',
        'nilai_ujian' => 'integer',
        'nilai_wawancara' => 'integer',
        'doc_status' => 'array',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper untuk cek apakah jadwal sudah diset
    public function hasSchedule()
    {
        return !is_null($this->jadwal_ujian);
    }


    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }


    /**
     * Helper: Cek apakah sudah lulus
     */
    public function isLulus()
    {
        return $this->status_pendaftaran === 'lulus';
    }
}
