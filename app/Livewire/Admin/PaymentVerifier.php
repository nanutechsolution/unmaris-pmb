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

    public function verify($status)
    {
        // Simpan status lama untuk log
        $oldStatus = $this->pendaftar->status_pembayaran;

        // Update database
        $this->pendaftar->update([
            'status_pembayaran' => $status
        ]);

        // 2. Implementasi Logger Audit
        Logger::record(
            'UPDATE', // Action
            'Keuangan', // Module
            "Mengubah status pembayaran Pendaftar #{$this->pendaftar->id} ({$this->pendaftar->user->name}) dari '$oldStatus' menjadi '$status'" // Message
        );

        // Kirim notifikasi sukses
        session()->flash('message', 'Status pembayaran berhasil diubah menjadi: ' . strtoupper($status));
    }

    public function render()
    {
        return view('livewire.admin.payment-verifier');
    }
}