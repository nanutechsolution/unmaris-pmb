<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class GeographicStats extends Component
{
    #[Layout('layouts.admin')] 
    public function render()
    {
        // 1. Statistik Berdasarkan Kota (Tempat Lahir)
        // Kita ambil top 10 kota terbanyak
        $topCities = Pendaftar::select('tempat_lahir', DB::raw('count(*) as total'))
            ->groupBy('tempat_lahir')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        // 2. Statistik Berdasarkan Asal Sekolah
        // Ini juga indikator geografis yang baik
        $topSchools = Pendaftar::select('asal_sekolah', DB::raw('count(*) as total'))
            ->groupBy('asal_sekolah')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        // 3. Total Pendaftar untuk persentase
        $totalPendaftar = Pendaftar::count();

        return view('livewire.admin.geographic-stats', compact('topCities', 'topSchools', 'totalPendaftar'));
    }
}