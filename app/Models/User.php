<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'nomor_hp',
        'role', // Tambahkan ini
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Helper untuk cek role di Blade / Controller
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCamaba(): bool
    {
        return $this->role === 'camaba';
    }
    public function pendaftar()
    {
        return $this->hasOne(Pendaftar::class);
    }
}