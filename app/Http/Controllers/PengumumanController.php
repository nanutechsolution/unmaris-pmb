<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Pendaftar;

class PengumumanController extends Controller
{
    /**
     * Menampilkan halaman status kelulusan.
     */
    public function index()
    {
        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        if (!$pendaftar) {
            return redirect()->route('camaba.formulir');
        }

        return view('camaba.pengumuman', compact('pendaftar'));
    }

    /**
     * Download PDF Surat Kelulusan (LoA) dengan QR Code.
     */
    public function cetak()
    {
        $user = Auth::user();
        $pendaftar = $user->pendaftar;

        if (!$pendaftar || $pendaftar->status_pendaftaran !== 'lulus') {
            return redirect()->route('camaba.pengumuman')->with('error', 'Dokumen belum tersedia.');
        }

        $no_surat = 'SKL/UNMARIS/' . date('Y') . '/' . sprintf('%04d', $pendaftar->id);

        // 1. Buat URL Verifikasi Unik (Menggunakan ID dan Hash timestamp agar sulit dipalsukan)
        $hash = md5($pendaftar->created_at . $pendaftar->id . 'secret-key');
        $urlVerifikasi = route('verifikasi.surat', ['id' => $pendaftar->id, 'hash' => $hash]);

        // 2. Generate QR Code ke format Base64 agar bisa dirender di PDF
        // Format SVG paling tajam untuk PDF
        $qrcode = base64_encode(QrCode::format('svg')->size(100)->generate($urlVerifikasi));

        $pdf = Pdf::loadView('pdf.surat-lulus', [
            'user' => $user,
            'pendaftar' => $pendaftar,
            'no_surat' => $no_surat,
            'qrcode' => $qrcode // Kirim variable QR ke view
        ]);

        return $pdf->download('LoA_UNMARIS_' . str_replace(' ', '_', $user->name) . '.pdf');
    }

    /**
     * Halaman Publik untuk memverifikasi keaslian surat saat QR discan.
     */
    public function verifikasiSurat($id, $hash)
    {
        $pendaftar = Pendaftar::with('user')->findOrFail($id);
        
        // Cek validitas hash
        $validHash = md5($pendaftar->created_at . $pendaftar->id . 'secret-key');
        
        if ($hash !== $validHash || $pendaftar->status_pendaftaran !== 'lulus') {
            abort(404, 'Dokumen Tidak Valid atau Tidak Ditemukan.');
        }

        // Tampilkan halaman verifikasi sederhana
        return view('verifikasi-loa', compact('pendaftar'));
    }
}