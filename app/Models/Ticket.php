<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }
    
    // Helper untuk warna status
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'open' => 'bg-red-100 text-red-600',
            'answered' => 'bg-green-100 text-green-600',
            'closed' => 'bg-gray-100 text-gray-500',
        };
    }
}