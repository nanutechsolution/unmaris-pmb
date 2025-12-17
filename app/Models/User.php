<?php

namespace App\Models;

use App\Notifications\CustomResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomVerifyEmail; // <--- 1. TAMBAHKAN INI DI ATAS

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nomor_hp',
        'role',     // 'admin', 'camaba', 'keuangan', 'akademik'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ====================================================
    // 📧 CUSTOM EMAIL VERIFICATION (TAMBAHAN)
    // ====================================================

    /**
     * Override method bawaan Laravel untuk kirim email verifikasi.
     * Menggunakan template Gen Z kita, bukan bawaan Laravel.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }
    /**
     * Override method bawaan Laravel untuk kirim email reset password.
     * Ini akan dipanggil otomatis saat user minta reset password.
     */
    public function sendPasswordResetNotification($token) 
    {
        $this->notify(new CustomResetPassword($token));
    }
    // ====================================================
    // 🔗 RELASI DATABASE (WAJIB ADA)
    // ====================================================

    /**
     * Relasi ke tabel Data Pendaftar (Biodata, Berkas, dll)
     * Satu User hanya punya Satu Data Pendaftar
     */
    public function pendaftar()
    {
        return $this->hasOne(Pendaftar::class);
    }

    // ====================================================
    // 🛡️ HELPER ROLE (UNTUK MIDDLEWARE & BLADE)
    // ====================================================

    /**
     * Cek apakah user adalah Super Admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah Calon Mahasiswa
     */
    public function isCamaba(): bool
    {
        return $this->role === 'camaba';
    }

    /**
     * Cek apakah user adalah Staff Keuangan
     */
    public function isKeuangan(): bool
    {
        return $this->role === 'keuangan';
    }

    /**
     * Cek apakah user adalah Staff Akademik
     */
    public function isAkademik(): bool
    {
        return $this->role === 'akademik';
    }
}
