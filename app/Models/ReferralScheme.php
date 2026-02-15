<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReferralScheme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'jalur',
        'start_date',
        'end_date',
        'reward_amount',
        'target_min',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function rewards()
    {
        return $this->hasMany(ReferralReward::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    // Scope untuk ambil skema aktif
    public function scopeActive($query)
    {
        return $query
            ->whereDate('start_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhereDate('end_date', '>=', now());
            });
    }

    // Scope berdasarkan jalur
    public function scopeForJalur($query, $jalur)
    {
        return $query->where(function ($q) use ($jalur) {
            $q->whereNull('jalur')
              ->orWhere('jalur', $jalur);
        });
    }
}
