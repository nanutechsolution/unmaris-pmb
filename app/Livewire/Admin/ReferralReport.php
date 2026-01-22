<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\DB;

class ReferralReport extends Component
{
    use WithPagination;

    public $search = '';
    public $filterSumber = ''; // Filter: Mahasiswa, Dosen, Alumni, dll
    public $rewardPerSiswa = 50000; // Contoh: Komisi Rp 50.000 per siswa

    public function render()
    {
        // Query Grouping: Siapa merekomendasikan berapa orang
        $referrals = Pendaftar::select(
                'nama_referensi', 
                'sumber_informasi', 
                DB::raw('count(*) as total_rekrut'),
                // Ambil ID pendaftar yang direkrut (group_concat di MySQL)
                DB::raw('GROUP_CONCAT(user_id) as users_ids') 
            )
            ->whereNotNull('nama_referensi')
            ->where('nama_referensi', '!=', '') // Hindari data kosong
            ->where('nama_referensi', 'like', '%' . $this->search . '%')
            ->when($this->filterSumber, function($q) {
                $q->where('sumber_informasi', $this->filterSumber);
            })
            ->groupBy('nama_referensi', 'sumber_informasi')
            ->orderByDesc('total_rekrut')
            ->paginate(15);

        // Ambil detail pendaftar (untuk menampilkan nama-nama yang direkrut)
        // Kita loop manual di view nanti atau ambil relation jika perlu
        
        // Statistik Global
        $totalReferral = Pendaftar::whereNotNull('nama_referensi')->where('nama_referensi', '!=', '')->count();
        $topReferral = $referrals->first();

        return view('livewire.admin.referral-report', [
            'referrals' => $referrals,
            'totalReferral' => $totalReferral,
            'topReferralName' => $topReferral ? $topReferral->nama_referensi : '-'
        ])->layout('layouts.admin');
    }
}