<?php

namespace App\Http\Controllers\Camaba;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        // Tentukan Progress Bar (0 - 100%)
        $progress = 0;
        $currentStage = 'Belum Daftar';

        if ($pendaftar) {
            $progress = 25; // Sudah isi biodata
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

        return view('camaba.dashboard', compact('user', 'pendaftar', 'progress', 'currentStage'));
    }
}