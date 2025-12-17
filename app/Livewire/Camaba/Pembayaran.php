<?php

namespace App\Livewire\Camaba;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\SiteSetting;

class Pembayaran extends Component
{
    use WithFileUploads;

    public $bukti_transfer;
    public $biaya_pendaftaran;
    
    // Ganti properti single menjadi array untuk menampung banyak bank
    public $bank_accounts = [];

    public function mount()
    {
        // Redirect jika belum isi formulir
        if (!Auth::user()->pendaftar) {
            return redirect()->route('camaba.formulir');
        }

        // Ambil data setting
        $settings = SiteSetting::first();
        
        $this->biaya_pendaftaran = $settings->biaya_pendaftaran ?? 250000;

        // LOGIKA BARU: Ambil dari kolom JSON 'bank_accounts'
        if (!empty($settings->bank_accounts)) {
            $this->bank_accounts = $settings->bank_accounts;
        } else {
            // Fallback: Jika JSON kosong (data lama), ambil dari kolom biasa
            $this->bank_accounts = [[
                'bank' => $settings->nama_bank ?? 'Bank Kampus',
                'rekening' => $settings->nomor_rekening ?? '0000-0000-0000',
                'atas_nama' => $settings->atas_nama_rekening ?? 'Yayasan'
            ]];
        }
    }

    public function save()
    {
        $this->validate([
            'bukti_transfer' => 'required|image|max:2048',
        ]);

        $pendaftar = Auth::user()->pendaftar;

        // 1. Guard: Cegah ganti file jika sudah LUNAS (Verified)
        if ($pendaftar->status_pembayaran === 'lunas') {
            $this->addError('bukti_transfer', 'Pembayaran sudah lunas dan diverifikasi. Tidak dapat diubah.');
            return;
        }

        // 2. Simpan File Baru
        $path = $this->bukti_transfer->store('uploads/pembayaran', 'public');

        // 3. Logic Ganti File (Hapus file lama jika ada)
        // Ini yang menangani kasus "Salah Upload". File lama dihapus agar tidak menuhin server.
        if ($pendaftar->bukti_pembayaran && Storage::disk('public')->exists($pendaftar->bukti_pembayaran)) {
            Storage::disk('public')->delete($pendaftar->bukti_pembayaran);
        }

        // 4. Update Database
        $pendaftar->update([
            'bukti_pembayaran' => $path,
            'status_pembayaran' => 'menunggu_verifikasi' // Reset status ke menunggu jika sebelumnya ditolak
        ]);

        $this->reset('bukti_transfer');

        session()->flash('message', 'Bukti pembayaran berhasil diperbarui! Admin akan mengecek ulang.');
    }

    public function render()
    {
        return view('livewire.camaba.pembayaran', [
            'pendaftar' => Auth::user()->pendaftar
        ])->layout('layouts.camaba'); 
    }
}