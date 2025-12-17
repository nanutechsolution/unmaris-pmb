<?php

namespace App\Livewire\Camaba;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\SiteSetting; // Import Model Setting

class Pembayaran extends Component
{
    use WithFileUploads;

    public $bukti_transfer;
    
    // Variable untuk menampung data dinamis
    public $biaya_pendaftaran;
    public $nama_bank;
    public $nomor_rekening;
    public $atas_nama;

    public function mount()
    {
        // Redirect jika belum isi formulir
        if (!Auth::user()->pendaftar) {
            return redirect()->route('camaba.formulir');
        }

        // Ambil data setting dari database
        $settings = SiteSetting::first();
        
        // Isi properti public dengan data dari DB (atau default jika null)
        $this->biaya_pendaftaran = $settings->biaya_pendaftaran ?? 250000;
        $this->nama_bank = $settings->nama_bank ?? 'Bank Kampus';
        $this->nomor_rekening = $settings->nomor_rekening ?? '0000-0000-0000';
        $this->atas_nama = $settings->atas_nama_rekening ?? 'Yayasan';
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