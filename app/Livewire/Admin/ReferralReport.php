<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\DB;
use App\Exports\ReferralExport;
use App\Exports\ReferralDetailExport;
use Maatwebsite\Excel\Facades\Excel;

class ReferralReport extends Component
{
    use WithPagination;

    public $search = '';
    public $filterSumber = ''; 
    public $rewardPerSiswa = 50000; 

    // --- PROPERTI UNTUK MODAL DETAIL ---
    public $showDetailModal = false;
    public $detailList = [];
    public $detailReferrerName = '';
    public $detailReferrerHp = '';

    public function render()
    {
        $referrals = Pendaftar::select(
                'nama_referensi', 
                'nomor_hp_referensi',
                'sumber_informasi', 
                DB::raw('count(*) as total_rekrut')
            )
            ->whereNotNull('nama_referensi')
            ->where('nama_referensi', '!=', '')
            ->where(function($q) {
                $q->where('nama_referensi', 'like', '%' . $this->search . '%')
                  ->orWhere('nomor_hp_referensi', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterSumber, function($q) {
                $q->where('sumber_informasi', $this->filterSumber);
            })
            ->groupBy('nama_referensi', 'nomor_hp_referensi', 'sumber_informasi')
            ->orderByDesc('total_rekrut')
            ->paginate(15);

        $totalReferral = Pendaftar::whereNotNull('nama_referensi')->where('nama_referensi', '!=', '')->count();
        $topReferral = $referrals->first();

        return view('livewire.admin.referral-report', [
            'referrals' => $referrals,
            'totalReferral' => $totalReferral,
            'topReferralName' => $topReferral ? $topReferral->nama_referensi : '-'
        ])->layout('layouts.admin');
    }

    public function export()
    {
        $fileName = 'Laporan_Referral_PMB_'.date('d-m-Y').'.xlsx';
        return Excel::download(new ReferralExport($this->search, $this->filterSumber, $this->rewardPerSiswa), $fileName);
    }

    public function exportDetail($nama, $hp = null)
    {
        $safeName = preg_replace('/[^A-Za-z0-9\-]/', '_', $nama);
        $fileName = 'Detail_Rekrut_' . $safeName . '.xlsx';
        
        return Excel::download(new ReferralDetailExport($nama, $hp), $fileName);
    }

    // --- UPDATED: PENCARIAN DETAIL LEBIH KETAT ---
    public function showDetails($nama, $hp = null)
    {
        $this->detailReferrerName = $nama;
        $this->detailReferrerHp = $hp; 
        
        $this->detailList = Pendaftar::with('user')
            ->where('nama_referensi', $nama)
            // Logic pencocokan HP yang ketat
            ->where(function($q) use ($hp) {
                if ($hp) {
                    $q->where('nomor_hp_referensi', $hp);
                } else {
                    // Jika di list HP kosong, di detail juga cari yang kosong
                    $q->whereNull('nomor_hp_referensi')->orWhere('nomor_hp_referensi', '');
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->detailList = [];
        $this->detailReferrerHp = '';
    }
}