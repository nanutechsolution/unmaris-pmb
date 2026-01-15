<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FacilitySlide extends Model
{
    protected $guarded = ['id'];

    // WAJIB: Agar kolom JSON otomatis jadi Array PHP
    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
    ];

    // Helper untuk hapus file fisik saat data dihapus
    protected static function booted()
    {
        static::deleted(function ($slide) {
            if ($slide->images) {
                foreach ($slide->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
        });
    }
}