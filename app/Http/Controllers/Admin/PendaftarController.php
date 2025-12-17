<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use App\Exports\PendaftarExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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
            'status' => 'required|in:verifikasi,lulus,gagal,submit,draft',
        ]);

        $pendaftar = Pendaftar::findOrFail($id);

        // SECURITY CHECK: Jika mau meluluskan, cek pembayaran & nilai
        if ($request->status == 'lulus') {
            if ($pendaftar->status_pembayaran !== 'lunas') {
                return back()->with('error', 'Gagal: Mahasiswa belum lunas pembayaran!');
            }
            if ($pendaftar->nilai_ujian <= 0 || $pendaftar->nilai_wawancara <= 0) {
                return back()->with('error', 'Gagal: Nilai Ujian/Wawancara belum diinput!');
            }
        }

        $pendaftar->update(['status_pendaftaran' => $request->status]);

        return back()->with('success', 'Status pendaftaran diperbarui.');
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


    public function pushToSiakad($id)
    {
        $pendaftar = Pendaftar::with('user')->findOrFail($id);

        // 1. Cek Status Kelulusan
        if ($pendaftar->status_pendaftaran !== 'lulus') {
            return back()->with('error', 'Gagal: Hanya peserta LULUS yang bisa dikirim ke SIAKAD.');
        }

        // 2. Cek Apakah Sudah Pernah Dikirim (Mencegah Duplikat)
        if ($pendaftar->is_synced) {
            return back()->with('error', 'Gagal: Data mahasiswa ini SUDAH pernah dikirim ke SIAKAD sebelumnya.');
        }

        try {
            // URL API SIAKAD
            $urlSiakad =  env('SIAKAD_API_URL') .
                ' /api/v1/pmb/sync';

            // Kirim Request
            $response = Http::timeout(10)->post($urlSiakad, [ // Tambah timeout agar tidak loading selamanya
                'name'            => $pendaftar->user->name,
                'email'           => $pendaftar->user->email,
                'nomor_hp'        => $pendaftar->user->nomor_hp,
                'nik'             => $pendaftar->nik,
                'nisn'            => $pendaftar->nisn,
                'asal_sekolah'    => $pendaftar->asal_sekolah,
                'tahun_lulus'     => (int) $pendaftar->tahun_lulus,
                'nama_ayah'       => $pendaftar->nama_ayah,
                'nama_ibu'        => $pendaftar->nama_ibu,
                'pilihan_prodi_1' => $pendaftar->pilihan_prodi_1,
                'pilihan_prodi_2' => $pendaftar->pilihan_prodi_2,
                'jalur_masuk'     => $pendaftar->jalur_pendaftaran,
                'secret_key'      => env('SIAKAD_API_SECRET'),
            ]);

            $result = $response->json();

            // 3. Cek Respon API
            if ($response->successful() && isset($result['status']) && $result['status'] == 'success') {
                // Update Status Sync di Database PMB agar tidak dikirim ulang
                $pendaftar->update(['is_synced' => true]);

                return back()->with('success', 'Berhasil! Data terkirim ke SIAKAD. NIM Sementara: ' . ($result['data']['nim_sementara'] ?? '-'));
            } else {
                // Ambil pesan error dari API SIAKAD jika ada
                $errorMessage = $result['message'] ?? 'Terjadi kesalahan tidak diketahui pada SIAKAD.';

                // Jika ada detail error validasi
                if (isset($result['errors'])) {
                    $errorMessage .= ' Detail: ' . json_encode($result['errors']);
                }

                return back()->with('error', 'Gagal kirim ke SIAKAD: ' . $errorMessage);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return back()->with('error', 'Koneksi Gagal: Server SIAKAD tidak dapat dijangkau. Pastikan server SIAKAD menyala.');
        } catch (\Exception $e) {
            return back()->with('error', 'System Error: ' . $e->getMessage());
        }
    }
}
