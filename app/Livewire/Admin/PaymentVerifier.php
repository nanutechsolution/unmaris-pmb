<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Pendaftar;

class PaymentVerifier extends Component
{
    public $pendaftar;

    public function mount(Pendaftar $pendaftar)
    {
        $this->pendaftar = $pendaftar;
    }

    public function verify($status)
    {
        // Update database
        $this->pendaftar->update([
            'status_pembayaran' => $status
        ]);

        // Kirim notifikasi sukses
        session()->flash('message', 'Status pembayaran berhasil diubah menjadi: ' . strtoupper($status));
    }

    public function render()
    {
        return view('livewire.admin.payment-verifier');
    }
}