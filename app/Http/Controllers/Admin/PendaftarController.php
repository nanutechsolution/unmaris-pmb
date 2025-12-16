<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use App\Exports\PendaftarExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class PendaftarController extends Controller
{
    public function dashboard()
    {
        // 1. Statistik Utama (KPI Cards)
        $totalPendaftar = Pendaftar::count();
        $menungguVerifikasi = Pendaftar::where('status_pendaftaran', 'submit')->count();
        $sudahLulus = Pendaftar::where('status_pendaftaran', 'lulus')->count();

        // 2. Statistik Per Prodi (Untuk Grafik/List)
        // Menghitung jumlah pendaftar berdasarkan Pilihan Prodi 1
        $statsProdi = Pendaftar::select('pilihan_prodi_1', DB::raw('count(*) as total'))
            ->groupBy('pilihan_prodi_1')
            ->orderByDesc('total')
            ->get();

        // 3. Pendaftar Terbaru (5 orang)
        $terbaru = Pendaftar::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalPendaftar',
            'menungguVerifikasi',
            'sudahLulus',
            'statsProdi',
            'terbaru'
        ));
    }

    public function index()
    {
        return view('admin.pendaftar-list');
    }

    public function show($id)
    {
        $pendaftar = Pendaftar::with('user')->findOrFail($id);
        return view('admin.pendaftar-detail', compact('pendaftar'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:verifikasi,lulus,gagal,submit',
        ]);

        $pendaftar = Pendaftar::findOrFail($id);
        $pendaftar->update(['status_pendaftaran' => $request->status]);

        return back()->with('success', 'Status berhasil diupdate!');
    }

    // FITUR BARU: Export Excel
    public function export()
    {
        return Excel::download(new PendaftarExport, 'data_pendaftar_unmaris_' . date('Y-m-d') . '.xlsx');
    }

    public function verifyPayment(Request $request, $id)
    {
        $pendaftar = Pendaftar::findOrFail($id);
        $pendaftar->update(['status_pembayaran' => $request->status_bayar]);
        return back()->with('success', 'Status pembayaran diperbarui.');
    }
}
