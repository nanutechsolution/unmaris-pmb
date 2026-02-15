<?php

namespace App\Services;

use App\Models\Pendaftar;
use App\Models\ReferralScheme;
use App\Models\ReferralReward;
use Illuminate\Support\Facades\DB;

class ReferralManager
{
    /*
    |--------------------------------------------------------------------------
    | HITUNG SUMMARY REFERRAL
    |--------------------------------------------------------------------------
    */

    public function getSummary($search = null, $filterSumber = null)
    {
        return Pendaftar::select(
                'nama_referensi',
                'nomor_hp_referensi',
                'sumber_informasi',
                DB::raw('count(*) as total_rekrut')
            )
            ->whereNotNull('nama_referensi')
            ->where('nama_referensi', '!=', '')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama_referensi', 'like', "%{$search}%")
                        ->orWhere('nomor_hp_referensi', 'like', "%{$search}%");
                });
            })
            ->when($filterSumber, function ($q) use ($filterSumber) {
                $q->where('sumber_informasi', $filterSumber);
            })
            ->groupBy('nama_referensi', 'nomor_hp_referensi', 'sumber_informasi')
            ->orderByDesc('total_rekrut');
    }

    /*
    |--------------------------------------------------------------------------
    | TOP REFERRAL
    |--------------------------------------------------------------------------
    */

    public function getTopReferrer($filterSumber = null)
    {
        return Pendaftar::select(
                'nama_referensi',
                DB::raw('count(*) as total_rekrut')
            )
            ->whereNotNull('nama_referensi')
            ->where('nama_referensi', '!=', '')
            ->when($filterSumber, function ($q) use ($filterSumber) {
                $q->where('sumber_informasi', $filterSumber);
            })
            ->groupBy('nama_referensi', 'nomor_hp_referensi')
            ->orderByDesc('total_rekrut')
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE REWARD SAAT LUNAS
    |--------------------------------------------------------------------------
    */

    public function generateReward($pendaftar)
    {
        if (!$pendaftar->nama_referensi) {
            return null;
        }

        $scheme = ReferralScheme::active()
            ->forJalur($pendaftar->jalur_pendaftaran)
            ->first();

        if (!$scheme) {
            return null;
        }

        return ReferralReward::firstOrCreate(
            ['pendaftar_id' => $pendaftar->id],
            [
                'referral_scheme_id' => $scheme->id,
                'reward_amount' => $scheme->reward_amount,
                'status' => 'eligible'
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HITUNG TOTAL KOMISI
    |--------------------------------------------------------------------------
    */

    public function getTotalKomisi($nama, $hp = null)
    {
        return ReferralReward::whereHas('pendaftar', function ($q) use ($nama, $hp) {
                if ($hp) {
                    $q->where('nomor_hp_referensi', $hp);
                } else {
                    $q->where('nama_referensi', $nama);
                }
            })
            ->where('status', '!=', 'cancelled')
            ->sum('reward_amount');
    }
}
