<?php

namespace App\Livewire\Camaba;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Pembayaran extends Component
{
    use WithFileUploads;

    public $bukti_transfer;
    // Biaya Pendaftaran (Bisa diambil dari config/database nanti)
    public $biaya_pendaftaran = 250000;

    public function mount()
    {
        // Redirect jika belum isi formulir
        if (!Auth::user()->pendaftar) {
            return redirect()->route('camaba.formulir');
        }
    }

    public function save()
    {
        $this->validate([
            'bukti_transfer' => 'required|image|max:2048', // Max 2MB
        ]);

        $pendaftar = Auth::user()->pendaftar;

        // Simpan File
        $path = $this->bukti_transfer->store('uploads/pembayaran', 'public');

        // Hapus file lama jika ada (agar storage hemat)
        if ($pendaftar->bukti_pembayaran && Storage::disk('public')->exists($pendaftar->bukti_pembayaran)) {
            Storage::disk('public')->delete($pendaftar->bukti_pembayaran);
        }

        // Update DB
        $pendaftar->update([
            'bukti_pembayaran' => $path,
            'status_pembayaran' => 'menunggu_verifikasi'
        ]);

        // Reset input file
        $this->reset('bukti_transfer');

        session()->flash('message', 'Bukti pembayaran berhasil diunggah! Mohon tunggu verifikasi admin.');
    }

    public function render()
    {
        return view('livewire.camaba.pembayaran', [
            'pendaftar' => Auth::user()->pendaftar
        ])->layout('layouts.camaba');
    }
}
