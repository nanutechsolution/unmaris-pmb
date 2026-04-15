<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Pendaftar;
use App\Services\Logger;
use App\Notifications\Admin\PaymentStatusNotification;
use Illuminate\Support\Facades\DB;

class PaymentVerifier extends Component
{
    public $pendaftar;
    public $reject_reason = ''; // Opsi untuk memberikan alasan penolakan

    public function mount(Pendaftar $pendaftar)
    {
        $this->pendaftar = $pendaftar;
    }

    /**
     * 1. Verifikasi Transfer (Approve)
     */
    public function approve()
    {
        if (!$this->pendaftar->bukti_pembayaran) {
            session()->flash('error', 'Tidak ada bukti pembayaran untuk diverifikasi.');
            return;
        }

        $this->updateStatus('lunas', 'Memverifikasi Transfer LUNAS');
        session()->flash('success', 'Pembayaran berhasil diverifikasi.');
    }

    /**
     * 2. Terima Tunai (Manual Cash)
     */
    public function payCash()
    {
        $this->updateStatus('lunas', 'Menerima Pembayaran TUNAI');
        session()->flash('success', 'Pembayaran Tunai tercatat. Status: LUNAS.');
    }

    /**
     * 3. Tolak Pembayaran (Reject)
     */
    public function reject($reason = null)
    {
        $this->reject_reason = $reason ?? 'Bukti pembayaran tidak terbaca atau tidak sesuai.';
        
        $this->updateStatus('ditolak', 'Menolak bukti pembayaran', $this->reject_reason);
        
        session()->flash('error', 'Pembayaran ditolak. Pendaftar diminta upload ulang.');
    }

    /**
     * Helper Method untuk Update & Notify (Best Practice)
     */
    private function updateStatus($status, $logTitle, $reason = null)
    {
        $this->pendaftar->update([
            'status_pembayaran' => $status
        ]);

        Logger::record('UPDATE', 'Keuangan', "$logTitle: {$this->pendaftar->user->name}");

        // KIRIM NOTIFIKASI (WA & EMAIL)
        try {
            $this->pendaftar->user->notify(new PaymentStatusNotification(
                $this->pendaftar->user->name,
                $status,
                $reason
            ));
        } catch (\Exception $e) {
            // Catat error notifikasi tapi jangan gagalkan proses utama
            \Illuminate\Support\Facades\Log::error("Gagal kirim notifikasi pembayaran: " . $e->getMessage());
        }

        // Redirect kembali ke halaman asal agar UI terupdate
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.admin.payment-verifier');
    }
}