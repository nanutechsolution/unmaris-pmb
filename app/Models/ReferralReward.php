<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReferralReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'pendaftar_id',
        'referral_scheme_id',
        'reward_amount',
        'status',
        'paid_at',
        'processed_by'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function scheme()
    {
        return $this->belongsTo(ReferralScheme::class, 'referral_scheme_id');
    }

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */

    public function markAsPaid($userId = null)
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'processed_by' => $userId
        ]);
    }

    public function cancel()
    {
        $this->update([
            'status' => 'cancelled'
        ]);
    }
}
