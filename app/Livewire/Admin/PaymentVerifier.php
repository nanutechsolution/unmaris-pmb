<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Pendaftar;
use App\Services\Logger;

class PaymentVerifier extends Component
{
    public $pendaftar;

    public function mount(Pendaftar $pendaftar)
    {
        $this->pendaftar = $pendaftar;
    }

    // Method untuk tombol "Terima Pembayaran"
    public function approve()
    {
        $this->pendaftar->update([
            'status_pembayaran' => 'lunas'
        ]);

        Logger::record(
            'UPDATE',
            'Keuangan',
            "Memverifikasi pembayaran LUNAS untuk Pendaftar #{$this->pendaftar->id} ({$this->pendaftar->user->name})"
        );

        session()->flash('success', 'Pembayaran berhasil diverifikasi LUNAS.');
        
        // Refresh halaman agar tombol verifikasi berkas di parent component aktif
        return redirect(request()->header('Referer'));
    }

    // Method untuk tombol "Tolak" atau "Batalkan"
    public function reject()
    {
        $this->pendaftar->update([
            'status_pembayaran' => 'ditolak' // Atau 'belum_bayar' jika ingin reset
        ]);

        Logger::record(
            'UPDATE',
            'Keuangan',
            "Membatalkan/Menolak pembayaran Pendaftar #{$this->pendaftar->id} ({$this->pendaftar->user->name})"
        );

        session()->flash('error', 'Status pembayaran diubah menjadi DITOLAK.');
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.admin.payment-verifier');
    }
}