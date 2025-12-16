<?php

namespace App\Livewire\Camaba;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class Dashboard extends Component
{
    #[Layout('layouts.camaba')] // Layout khusus Camaba
    public function render()
    {
        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        // Hitung Progress & Stage
        $progress = 0;
        $currentStage = 'Belum Daftar';

        if ($pendaftar) {
            $progress = 25; 
            $currentStage = 'Verifikasi Berkas';

            if ($pendaftar->status_pendaftaran == 'submit') {
                $progress = 50; 
                $currentStage = 'Menunggu Verifikasi';
            }
            
            if ($pendaftar->status_pembayaran == 'lunas') {
                $progress = 75;
                $currentStage = 'Siap Ujian';
            }

            if ($pendaftar->status_pendaftaran == 'lulus') {
                $progress = 100;
                $currentStage = 'Lulus Seleksi';
            }
        }

        return view('livewire.camaba.dashboard', compact('user', 'pendaftar', 'progress', 'currentStage'));
    }
}