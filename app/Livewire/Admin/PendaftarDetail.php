<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Pendaftar;
use App\Models\StudyProgram;
use App\Services\Logger;
use Illuminate\Support\Facades\Mail;
use App\Mail\PmbNotification;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class PendaftarDetail extends Component
{
    public $pendaftar_id;
    public $catatan_seleksi;
    public $rekomendasi_prodi;

    public function mount($id)
    {
        $this->pendaftar_id = $id;
        $pendaftar = Pendaftar::findOrFail($id);
        $this->catatan_seleksi = $pendaftar->catatan_seleksi;
        $this->rekomendasi_prodi = $pendaftar->rekomendasi_prodi;
    }

    public function getPendaftarProperty()
    {
        return Pendaftar::with('user')->findOrFail($this->pendaftar_id);
    }

    public function updateStatus($status)
    {
        $pendaftar = $this->pendaftar;
        $oldStatus = $pendaftar->status_pendaftaran;

        // Validasi Status (Tambahkan 'perbaikan' ke daftar valid)
        if (!in_array($status, ['verifikasi', 'lulus', 'gagal', 'submit', 'draft', 'perbaikan'])) {
            session()->flash('error', 'Status tidak valid.');
            return;
        }

        if ($status == 'lulus' && $pendaftar->status_pembayaran !== 'lunas') {
            session()->flash('error', 'Gagal: Belum lunas pembayaran!');
            return;
        }

        $pendaftar->status_pendaftaran = $status;
        $pendaftar->save();

        if ($status == 'lulus') {
            try {
                Mail::to($pendaftar->user->email)->send(new PmbNotification(
                    $pendaftar->user,
                    'Hasil Seleksi',
                    'SELAMAT! ANDA LULUS 🎉',
                    'Selamat bergabung. Cek dashboard untuk info selanjutnya.',
                    'LOGIN',
                    route('login'),
                    'success'
                ));
            } catch (\Exception $e) {
            }
        }

        Logger::record('UPDATE', 'Verifikasi Pendaftar', "Status #{$pendaftar->id} berubah: $oldStatus -> $status");
        session()->flash('success', 'Status berhasil diperbarui menjadi ' . strtoupper($status));
    }

    public function rejectDocument($docId, $reason)
    {
        if (empty($reason)) {
            session()->flash('error', 'Alasan penolakan wajib diisi.');
            return;
        }

        $map = [
            'ktp' => 'ktp_path',
            'akta' => 'akta_path',
            'ijazah' => 'ijazah_path',
            'transkrip' => 'transkrip_path',
            'beasiswa' => 'file_beasiswa',
        ];

        if (!array_key_exists($docId, $map)) {
            session()->flash('error', 'Dokumen tidak valid.');
            return;
        }

        $field = $map[$docId];
        $pendaftar = $this->pendaftar;
        $path = $pendaftar->$field;

        if ($path) {
            // 1. Hapus File Fisik
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            // 2. Simpan Alasan Penolakan ke JSON
            $currentStatus = $pendaftar->doc_status ?? [];
            $currentStatus[$docId] = [
                'status' => 'rejected',
                'reason' => $reason,
                'date' => now()->toDateTimeString()
            ];

            // 3. Update Data & Ubah Status ke 'perbaikan'
            $pendaftar->doc_status = $currentStatus;
            $pendaftar->$field = null; // Hapus path agar user upload ulang
            $pendaftar->status_pendaftaran = 'perbaikan';
            $pendaftar->save();

            // 4. Kirim Email Notifikasi
            try {
                Mail::to($pendaftar->user->email)->send(new PmbNotification(
                    $pendaftar->user,
                    'Perbaikan Dokumen Diperlukan', // Judul Email
                    'STATUS: PERBAIKAN BERKAS', // Header Email
                    "Dokumen ($docId) Anda ditolak. Alasan: \"$reason\". Status pendaftaran Anda kini 'Perbaikan'. Silakan login dan unggah ulang.",
                    'PERBAIKI SEKARANG',
                    route('camaba.formulir'),
                    'error'
                ));
            } catch (\Exception $e) {
            }

            Logger::record('UPDATE', 'Reject Dokumen', "Menolak dokumen $docId milik #{$pendaftar->id}. Status -> Perbaikan.");

            session()->flash('success', "Dokumen ditolak. Status pendaftaran diubah menjadi PERBAIKAN.");
        } else {
            session()->flash('error', 'File dokumen tidak ditemukan.');
        }
    }

    public function lulusPilihan($pilihan)
    {
        $pendaftar = $this->pendaftar;

        if ($pendaftar->is_locked) {
            session()->flash('error', 'Data sudah dikunci.');
            return;
        }

        $prodi = $pilihan == 1 ? $pendaftar->pilihan_prodi_1 : $pendaftar->pilihan_prodi_2;

        $pendaftar->status_pendaftaran = 'lulus';
        $pendaftar->prodi_diterima = $prodi;
        $pendaftar->is_locked = true;
        $pendaftar->save();

        session()->flash('success', "Mahasiswa dinyatakan LULUS di $prodi");
    }

    public function simpanRekomendasi()
    {
        $pendaftar = $this->pendaftar;
        $pendaftar->rekomendasi_prodi = $this->rekomendasi_prodi;
        $pendaftar->catatan_seleksi = $this->catatan_seleksi;
        $pendaftar->save();

        session()->flash('success', 'Rekomendasi disimpan.');
    }

    public function syncToSiakad()
    {
        $pendaftar = $this->pendaftar;
        $pendaftar->is_synced = true;
        $pendaftar->save();

        session()->flash('success', 'Berhasil sinkron ke SIAKAD (Simulasi).');
    }

    public function render()
    {
        return view('livewire.admin.pendaftar-detail', [
            'pendaftar' => $this->pendaftar,
            'prodiList' => StudyProgram::all()
        ]);
    }
    

    public function lulusRekomendasi()
    {
        $pendaftar = $this->pendaftar;

        // Cek Validasi
        if ($pendaftar->is_locked) {
            session()->flash('error', 'Data sudah dikunci. Kelulusan bersifat final.');
            return;
        }

        if (empty($pendaftar->rekomendasi_prodi)) {
            session()->flash('error', 'Tidak ada prodi rekomendasi yang disimpan.');
            return;
        }

        // Proses Kelulusan
        $pendaftar->status_pendaftaran = 'lulus';
        $pendaftar->prodi_diterima = $pendaftar->rekomendasi_prodi;
        $pendaftar->is_locked = true;
        $pendaftar->save();

        // Kirim Email Notifikasi (Copy dari logic updateStatus)
        try {
            Mail::to($pendaftar->user->email)->send(new PmbNotification(
                $pendaftar->user,
                'Hasil Seleksi',
                'SELAMAT! ANDA LULUS 🎉',
                'Selamat bergabung di prodi rekomendasi kami. Silakan cek dashboard.',
                'LOGIN',
                route('login'),
                'success'
            ));
        } catch (\Exception $e) {
        }

        session()->flash('success', "Mahasiswa dinyatakan LULUS di {$pendaftar->prodi_diterima} (Jalur Rekomendasi)");
    }
}
