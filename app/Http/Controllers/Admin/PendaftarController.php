<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use App\Exports\PendaftarExport;
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

    // --- FUNGSI UPDATE STATUS (FIXED) ---
    public function updateStatus(Request $request, $id)
    {
        // 1. Ambil Data Dulu
        $pendaftar = Pendaftar::findOrFail($id);
        $oldStatus = $pendaftar->status_pendaftaran;
        
        // 2. Ambil status dari input
        $newStatus = $request->status;

        // Fallback: Jika input kosong tapi tombol "Verifikasi" diklik (biasanya value 'verifikasi')
        if (!$newStatus) {
            return back()->with('error', 'Gagal: Status tidak terkirim dari formulir.');
        }

        // 3. Validasi Manual (Lebih fleksibel)
        $allowedStatuses = ['verifikasi', 'lulus', 'gagal', 'submit', 'draft'];
        if (!in_array($newStatus, $allowedStatuses)) {
             return back()->with('error', 'Status tidak valid: ' . $newStatus);
        }

        // 4. Cek Syarat Khusus (Jika ingin meluluskan)
        if ($newStatus == 'lulus') {
            if ($pendaftar->status_pembayaran !== 'lunas') {
                return back()->with('error', 'Gagal: Mahasiswa belum lunas pembayaran!');
            }
            // Hapus atau sesuaikan pengecekan nilai jika belum diperlukan saat ini
            if ($pendaftar->nilai_ujian <= 0 && $pendaftar->nilai_wawancara <= 0) {
                 // return back()->with('error', 'Gagal: Nilai Ujian belum diinput!'); // Uncomment jika wajib
            }
        }

        // 5. UPDATE LANGSUNG KE DATABASE (Bypass Model/Eloquent Issues)
        // Kita gunakan Query Builder 'update' agar lebih pasti tereksekusi
        Pendaftar::where('id', $id)->update([
            'status_pendaftaran' => $newStatus,
            'updated_at' => now(), // Update timestamp manual
        ]);

        // 6. Kirim Email Notifikasi (Jika Lulus)
        if ($newStatus == 'lulus') {
            try {
                Mail::to($pendaftar->user->email)->send(new PmbNotification(
                    $pendaftar->user,
                    'Hasil Seleksi PMB',
                    'SELAMAT! ANDA LULUS 🎉',
                    'Selamat bergabung dengan UNMARIS. Anda dinyatakan lulus seleksi. Silakan unduh Surat Kelulusan (LoA) di Dashboard.',
                    'UNDUH SURAT',
                    route('camaba.pengumuman'),
                    'success'
                ));
            } catch (\Exception $e) {
                Logger::record('ERROR', 'Email Notification', "Gagal kirim email lulus ke #{$id}: " . $e->getMessage());
            }
        }

        // 7. Catat Log
        Logger::record(
            'UPDATE',
            'Verifikasi Pendaftar',
            "Admin mengubah status pendaftar #{$pendaftar->id} ({$pendaftar->user->name}) dari $oldStatus menjadi " . $newStatus
        );

        return back()->with('success', 'Status pendaftaran BERHASIL diperbarui menjadi ' . strtoupper($newStatus));
    }

    public function verifyPayment(Request $request, $id)
    {
        $pendaftar = Pendaftar::findOrFail($id);
        
        $pendaftar->status_pembayaran = $request->status_bayar;
        $pendaftar->save();

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

    public function pushToSiakad($id)
    {
        $pendaftar = Pendaftar::with('user')->findOrFail($id);

        if ($pendaftar->status_pendaftaran !== 'lulus') {
            return back()->with('error', 'Gagal: Hanya peserta LULUS yang bisa dikirim ke SIAKAD.');
        }

        if ($pendaftar->is_synced) {
            return back()->with('error', 'Gagal: Data mahasiswa ini SUDAH pernah dikirim ke SIAKAD sebelumnya.');
        }

        $urlBase = env('SIAKAD_API_URL');
        $secretKey = env('SIAKAD_API_SECRET');

        if (!$urlBase || !$secretKey) {
            return back()->with('error', 'Error Config: URL atau Secret Key SIAKAD belum diset di .env PMB.');
        }

        try {
            $urlEndpoint = rtrim($urlBase, '/') . '/api/v1/pmb/sync';
            $tahun = date('Y');
            $generatedNoReg = 'PMB-' . $tahun . '-' . sprintf('%04d', $pendaftar->id);

            $response = Http::timeout(10)->post($urlEndpoint, [
                'secret_key'      => $secretKey,
                'registration_no' => $generatedNoReg,
                'nik'             => $pendaftar->nik,
                'name'            => $pendaftar->user->name,
                'email'           => $pendaftar->user->email,
                'prodi_code'      => $pendaftar->prodi_diterima,
                'entry_year'      => (string) $tahun,
                'mother_name'     => $pendaftar->nama_ibu,
                'school_name'     => $pendaftar->asal_sekolah,
                'nomor_hp_ortu'   => $pendaftar->nomor_hp_ortu ?? '-',
                'jalur_masuk'     => $pendaftar->jalur_pendaftaran,
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] == 'success') {
                $pendaftar->update(['is_synced' => true]);

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
        } catch (\Exception $e) {
            return back()->with('error', 'System Error: ' . $e->getMessage());
        }
    }

    public function lulusPilihan(Request $request, $id)
    {
        $pendaftar = Pendaftar::findOrFail($id);

        if ($pendaftar->is_locked) {
            return back()->with('error', 'Data sudah dikunci. Kelulusan bersifat final.');
        }

        $request->validate([
            'pilihan' => 'required|in:1,2'
        ]);

        $prodi = $request->pilihan == 1
            ? $pendaftar->pilihan_prodi_1
            : $pendaftar->pilihan_prodi_2;

        if (!$prodi) {
            return back()->with('error', 'Pilihan prodi tidak valid.');
        }

        $pendaftar->status_pendaftaran = 'lulus';
        $pendaftar->prodi_diterima = $prodi;
        $pendaftar->is_locked = true;
        $pendaftar->save();

        return back()->with('success', 'Mahasiswa dinyatakan LULUS di ' . $prodi);
    }

    public function simpanRekomendasi(Request $request, $id)
    {
        $request->validate([
            'rekomendasi_prodi' => 'nullable|string',
            'catatan_seleksi'   => 'nullable|string',
        ]);

        $pendaftar = Pendaftar::findOrFail($id);

        if ($pendaftar->status_pendaftaran === 'lulus') {
            return back()->with('error', 'Data sudah final dan tidak dapat diubah.');
        }

        $pendaftar->rekomendasi_prodi = $request->rekomendasi_prodi;
        $pendaftar->catatan_seleksi = $request->catatan_seleksi;
        $pendaftar->save();

        return back()->with('success', 'Rekomendasi & catatan seleksi berhasil disimpan.');
    }

    public function lulusRekomendasi($id)
    {
        $pendaftar = Pendaftar::findOrFail($id);

        if (!$pendaftar->rekomendasi_prodi) {
            return back()->with('error', 'Tidak ada rekomendasi prodi.');
        }

        $pendaftar->status_pendaftaran = 'lulus';
        $pendaftar->prodi_diterima = $pendaftar->rekomendasi_prodi;
        $pendaftar->is_locked = true;
        $pendaftar->save();

        return back()->with('success', 'Pendaftar diluluskan sesuai rekomendasi prodi.');
    }
}