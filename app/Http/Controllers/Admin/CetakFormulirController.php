<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use App\Services\Logger;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakFormulirController extends Controller
{
    /**
     * Cetak 1 Formulir
     */
    public function cetakSatu($id)
    {
        $pendaftar = Pendaftar::with(['user', 'gelombang'])->findOrFail($id);

        Logger::record('PRINT', 'Cetak Formulir', "Admin mencetak formulir pendaftaran: {$pendaftar->user->name}");

        // Load view dan atur ukuran kertas
        $pdf = Pdf::loadView('admin.cetak.formulir', [
            'pendaftars' => collect([$pendaftar])
        ])->setPaper('a4', 'portrait');

        // Stream langsung membuka PDF di tab baru
        return $pdf->stream('Formulir_' . $pendaftar->user->name . '.pdf');
    }

    /**
     * Cetak Massal (Banyak Formulir Sekaligus)
     */
    public function cetakMassal(Request $request)
    {
        $ids = $request->query('ids'); // Format: 1,2,3,4

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih untuk dicetak.');
        }

        $idArray = explode(',', $ids);
        $pendaftars = Pendaftar::with(['user', 'gelombang'])->whereIn('id', $idArray)->get();

        Logger::record('PRINT', 'Cetak Formulir Massal', "Admin mencetak " . count($pendaftars) . " formulir pendaftaran.");

        $pdf = Pdf::loadView('admin.cetak.formulir', [
            'pendaftars' => $pendaftars
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Formulir_Massal.pdf');
    }
}
