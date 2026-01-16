<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use App\Exports\PendaftarExport;
use App\Exports\SiakadExport;
use App\Mail\PmbNotification;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Services\Logger; 
use Illuminate\Support\Facades\Mail;

class PendaftarController extends Controller
{
    public function dashboard()
    {
        $totalPendaftar = Pendaftar::count();
        $menungguVerifikasi = Pendaftar::where('status_pendaftaran', 'submit')->count();
        $sudahLulus = Pendaftar::where('status_pendaftaran', 'lulus')->count();

        $statsProdi = Pendaftar::select('pilihan_prodi_1', DB::raw('count(*) as total'))
            ->groupBy('pilihan_prodi_1')
            ->orderByDesc('total')
            ->get();

        $terbaru = Pendaftar::with('user')->latest()->take(5)->get();

        return view('livewire.admin.dashboard', compact(
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

        // SECURITY CHECK
        if ($request->status == 'lulus') {
            if ($pendaftar->status_pembayaran !== 'lunas') {
                return back()->with('error', 'Gagal: Mahasiswa belum lunas pembayaran!');
            }
            if ($pendaftar->nilai_ujian <= 0 || $pendaftar->nilai_wawancara <= 0) {
                return back()->with('error', 'Gagal: Nilai Ujian/Wawancara belum diinput!');
            }
        }

        $oldStatus = $pendaftar->status_pendaftaran;
        $pendaftar->update(['status_pendaftaran' => $request->status]);
        if ($request->status == 'lulus') {
            Mail::to($pendaftar->user->email)->send(new PmbNotification(
                $pendaftar->user,
                'Hasil Seleksi PMB',
                'SELAMAT! ANDA LULUS 🎉',
                'Selamat bergabung dengan UNMARIS. Anda dinyatakan lulus seleksi. Silakan unduh Surat Kelulusan (LoA) di Dashboard.',
                'UNDUH SURAT',
                route('camaba.pengumuman'),
                'success'
            ));
        }

        // LOGGING
        Logger::record(
            'UPDATE',
            'Pendaftar #' . $pendaftar->id . ' (' . $pendaftar->user->name . ')',
            "Mengubah status pendaftaran dari $oldStatus menjadi " . $request->status
        );

        return back()->with('success', 'Status pendaftaran diperbarui.');
    }

    public function verifyPayment(Request $request, $id)
    {
        $pendaftar = Pendaftar::findOrFail($id);
        $pendaftar->update(['status_pembayaran' => $request->status_bayar]);

        // LOGGING
        Logger::record(
            'UPDATE',
            'Keuangan #' . $pendaftar->id,
            "Memverifikasi pembayaran menjadi: " . $request->status_bayar
        );

        return back()->with('success', 'Status pembayaran diperbarui.');
    }

    public function export()
    {
        return Excel::download(new PendaftarExport, 'data_pendaftar_unmaris_' . date('Y-m-d') . '.xlsx');
    }

    // public function exportSiakad() 
    // {
    //     return Excel::download(new SiakadExport, 'DATA_MIGRASI_SIAKAD_'.date('Y-m-d').'.xlsx');
    // }

    public function pushToSiakad($id)
    {
        $pendaftar = Pendaftar::with('user')->findOrFail($id);

        if ($pendaftar->status_pendaftaran !== 'lulus') {
            return back()->with('error', 'Gagal: Hanya peserta LULUS yang bisa dikirim ke SIAKAD.');
        }

        if ($pendaftar->is_synced) {
            return back()->with('error', 'Gagal: Data mahasiswa ini SUDAH pernah dikirim ke SIAKAD sebelumnya.');
        }

        // AMBIL KONFIGURASI DARI .ENV
        $urlBase = env('SIAKAD_API_URL');
        $secretKey = env('SIAKAD_API_SECRET');

        if (!$urlBase || !$secretKey) {
            return back()->with('error', 'Error Config: URL atau Secret Key SIAKAD belum diset di .env PMB.');
        }

        try {
            $urlEndpoint = rtrim($urlBase, '/') . '/api/v1/pmb/sync';

            // Persiapan variabel tahun (bisa ambil tahun sekarang)
            $tahun = date('Y');

            // Format ID agar menjadi 4 digit (misal ID 12 jadi 0012)
            // Hasil akhirnya: "PMB-2025-0012"
            $generatedNoReg = 'PMB-' . $tahun . '-' . sprintf('%04d', $pendaftar->id);

            $response = Http::timeout(10)->post($urlEndpoint, [
                'secret_key'      => $secretKey,

                // Menggunakan ID yang sudah diformat
                'registration_no' => $generatedNoReg,

                'nik'             => $pendaftar->nik,
                'name'            => $pendaftar->user->name,
                'email'           => $pendaftar->user->email,
                // 'prodi_code'      => $pendaftar->pilihan_prodi_1,
                'prodi_code' => $pendaftar->prodi_diterima,
                'entry_year'      => (string) $tahun, // Sesuaikan dengan tahun format di atas
                'mother_name'     => $pendaftar->nama_ibu,
                'school_name'     => $pendaftar->asal_sekolah,
                'nomor_hp_ortu'   => $pendaftar->nomor_hp_ortu,
                'jalur_masuk'     => $pendaftar->jalur_pendaftaran,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] == 'success') {

                $pendaftar->update(['is_synced' => true]);

                // LOGGING (Sekarang aman karena class Logger sudah di-import)
                Logger::record(
                    'SYNC',
                    'SIAKAD Integration',
                    "Mengirim data mahasiswa {$pendaftar->user->name} ke SIAKAD. NIM Sementara: " . ($result['data']['nim_sementara'] ?? '-')
                );

                return back()->with('success', 'Berhasil! Data terkirim ke SIAKAD. NIM Sementara: ' . ($result['data']['nim_sementara'] ?? '-'));
            } else {
                $errorMessage = $result['message'] ?? 'Terjadi kesalahan tidak diketahui pada SIAKAD.';
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

    public function lulusPilihan(Request $request, $id)
    {
        $pendaftar = Pendaftar::findOrFail($id);

        // 🔒 JIKA SUDAH LULUS → STOP
        if ($pendaftar->is_locked) {
            return back()->with('error', 'Data sudah dikunci. Kelulusan bersifat final.');
        }

        $request->validate([
            'pilihan' => 'required|in:1,2'
        ]);

        // Tentukan prodi diterima
        $prodi = $request->pilihan == 1
            ? $pendaftar->pilihan_prodi_1
            : $pendaftar->pilihan_prodi_2;

        if (!$prodi) {
            return back()->with('error', 'Pilihan prodi tidak valid.');
        }

        // ✅ FINALISASI
        $pendaftar->update([
            'status_pendaftaran' => 'lulus',
            'prodi_diterima'     => $prodi,
            'is_locked'          => true,
        ]);

        return back()->with('success', 'Mahasiswa dinyatakan LULUS di ' . $prodi);
    }


    public function simpanRekomendasi(Request $request, $id)
    {
        $request->validate([
            'rekomendasi_prodi' => 'nullable|string',
            'catatan_seleksi'   => 'nullable|string',
        ]);

        $pendaftar = Pendaftar::findOrFail($id);

        // ❌ Kalau sudah lulus, TOLAK
        if ($pendaftar->status_pendaftaran === 'lulus') {
            return back()->with('error', 'Data sudah final dan tidak dapat diubah.');
        }

        $pendaftar->update([
            'rekomendasi_prodi' => $request->rekomendasi_prodi,
            'catatan_seleksi'   => $request->catatan_seleksi,
        ]);

        return back()->with('success', 'Rekomendasi & catatan seleksi berhasil disimpan.');
    }


    public function lulusRekomendasi(Pendaftar $pendaftar)
    {
        if (!$pendaftar->rekomendasi_prodi) {
            return back()->with('error', 'Tidak ada rekomendasi prodi.');
        }

        $pendaftar->update([
            'status_pendaftaran' => 'lulus',
            'prodi_diterima' => $pendaftar->rekomendasi_prodi,
        ]);

        return back()->with(
            'success',
            'Pendaftar diluluskan sesuai rekomendasi prodi.'
        );
    }
}
