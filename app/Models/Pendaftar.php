<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

      // Tambahkan casting datetime agar mudah diformat
    protected $casts = [
        'jadwal_ujian' => 'datetime',
        'jadwal_wawancara' => 'datetime', 
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


    public function scholarship() { return $this->belongsTo(Scholarship::class); }
}