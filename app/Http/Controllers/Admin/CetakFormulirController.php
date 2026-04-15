<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use App\Services\Logger;

class CetakFormulirController extends Controller
{
    /**
     * Cetak 1 Formulir
     */
    public function cetakSatu($id)
    {
        $pendaftar = Pendaftar::with(['user', 'gelombang'])->findOrFail($id);
        
        Logger::record('PRINT', 'Cetak Formulir', "Admin mencetak formulir pendaftaran: {$pendaftar->user->name}");

        // Kita oper dalam bentuk array/collection agar view cetak bisa dipakai untuk massal juga
        return view('admin.cetak.formulir', [
            'pendaftars' => collect([$pendaftar])
        ]);
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

        return view('admin.cetak.formulir', [
            'pendaftars' => $pendaftars
        ]);
    }
}