<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Pendaftar;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class Dashboard extends Component
{
    // Menggunakan Layout Admin yang sudah kita buat sebelumnya
    #[Layout('layouts.admin')]
    public function render()
    {
        // 1. Statistik Utama (KPI Cards)
        $totalPendaftar = Pendaftar::count();
        $menungguVerifikasi = Pendaftar::where('status_pendaftaran', 'submit')->count();
        $sudahLulus = Pendaftar::where('status_pendaftaran', 'lulus')->count();

        // 2. Statistik Per Prodi
        $statsProdi = Pendaftar::select('pilihan_prodi_1', DB::raw('count(*) as total'))
            ->groupBy('pilihan_prodi_1')
            ->orderByDesc('total')
            ->get();

        // 3. Pendaftar Terbaru (5 orang)
        $terbaru = Pendaftar::with('user')->latest()->take(5)->get();

        return view('livewire.admin.dashboard', compact(
            'totalPendaftar',
            'menungguVerifikasi',
            'sudahLulus',
            'statsProdi',
            'terbaru'
        ));
    }
}
