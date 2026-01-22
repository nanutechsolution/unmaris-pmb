<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\DB;
use App\Exports\ReferralExport;
use Maatwebsite\Excel\Facades\Excel;

class ReferralReport extends Component
{
    use WithPagination;

    public $search = '';
    public $filterSumber = ''; 
    public $rewardPerSiswa = 50000; 

    public function render()
    {
        // Query Grouping: Siapa merekomendasikan berapa orang
        // UPDATED: Group by nomor_hp_referensi juga
        $referrals = Pendaftar::select(
                'nama_referensi', 
                'nomor_hp_referensi', // <--- Tambahan
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
            ->groupBy('nama_referensi', 'nomor_hp_referensi', 'sumber_informasi') // <--- Group by HP juga
            ->orderByDesc('total_rekrut')
            ->paginate(15);

        // Statistik Global
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
}