<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class CetakKartuController extends Controller
{
    public function cetak()
    {
        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        // Security check: Hanya yang sudah submit yang boleh cetak
        if (!$pendaftar || $pendaftar->status_pendaftaran == 'draft') {
            return redirect()->route('camaba.dashboard')->with('error', 'Anda belum menyelesaikan pendaftaran.');
        }

        // Generate Nomor Peserta (Logic sederhana: TAHUN-ID-URUT)
        // Di sistem nyata biasanya ada tabel khusus nomor ujian
        $no_peserta = date('Y') . sprintf('%04d', $pendaftar->id);

        $pdf = Pdf::loadView('pdf.kartu-ujian', [
            'user' => $user,
            'pendaftar' => $pendaftar,
            'no_peserta' => $no_peserta
        ]);

        return $pdf->download('KARTU_UJIAN_UNMARIS_' . $no_peserta . '.pdf');
        // Gunakan ->stream() jika ingin preview di browser dulu
    }
}
