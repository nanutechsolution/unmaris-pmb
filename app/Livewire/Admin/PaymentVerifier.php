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

    // 1. Verifikasi Transfer (Approve)
    public function approve()
    {
        if (!$this->pendaftar->bukti_pembayaran) {
            session()->flash('error', 'Tidak ada bukti pembayaran untuk diverifikasi.');
            return;
        }

        $this->pendaftar->update([
            'status_pembayaran' => 'lunas'
        ]);

        Logger::record(
            'UPDATE',
            'Keuangan',
            "Memverifikasi Transfer LUNAS: {$this->pendaftar->user->name}"
        );

        session()->flash('success', 'Pembayaran berhasil diverifikasi.');
        return redirect(request()->header('Referer'));
    }

    // 2. Terima Tunai (Manual Cash)
    public function payCash()
    {
        $this->pendaftar->update([
            'status_pembayaran' => 'lunas'
        ]);

        Logger::record(
            'UPDATE',
            'Keuangan',
            "Menerima Pembayaran TUNAI: {$this->pendaftar->user->name}"
        );

        session()->flash('success', 'Pembayaran Tunai tercatat. Status: LUNAS.');
        return redirect(request()->header('Referer'));
    }

    // 3. Tolak Pembayaran (Reject)
    public function reject()
    {
        $this->pendaftar->update([
            'status_pembayaran' => 'ditolak'
        ]);

        Logger::record(
            'UPDATE',
            'Keuangan',
            "Menolak bukti pembayaran: {$this->pendaftar->user->name}"
        );

        session()->flash('error', 'Pembayaran ditolak. Peserta diminta upload ulang.');
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.admin.payment-verifier');
    }
}