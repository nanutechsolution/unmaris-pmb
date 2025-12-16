<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftar;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    // Halaman Filter Laporan
    public function index()
    {
        return view('admin.laporan');
    }

    // Generate PDF
    public function cetak(Request $request)
    {
        $query = Pendaftar::with('user')->latest();

        // Filter Prodi
        if ($request->prodi) {
            $query->where('pilihan_prodi_1', $request->prodi);
        }

        // Filter Status
        if ($request->status) {
            $query->where('status_pendaftaran', $request->status);
        }

        // Filter Jalur
        if ($request->jalur) {
            $query->where('jalur_pendaftaran', $request->jalur);
        }

        $data = $query->get();

        $pdf = Pdf::loadView('pdf.laporan-rekap', [
            'data' => $data,
            'filter' => $request->all()
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_PMB_UNMARIS.pdf');
    }
}