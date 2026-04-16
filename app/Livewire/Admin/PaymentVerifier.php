<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads; // Wajib untuk fitur upload
use App\Models\Pendaftar;
use App\Services\Logger;
use App\Notifications\Admin\PaymentStatusNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Wajib untuk menghapus file lama

class PaymentVerifier extends Component
{
    use WithFileUploads;

    public $pendaftar;
    public $reject_reason = ''; 
    
    // Properti baru untuk menampung file upload dari admin
    public $new_proof; 

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
     * 3. Upload Bukti oleh Admin (Bypass)
     */
    public function uploadProof()
    {
        $this->validate([
            'new_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'new_proof.required' => 'Pilih file bukti pembayaran terlebih dahulu.',
            'new_proof.mimes' => 'Format file harus JPG, PNG, atau PDF.',
            'new_proof.max' => 'Ukuran file maksimal 2 MB.',
        ]);

        // Hapus file lama dari Storage jika ada, agar tidak menuhi disk server
        if ($this->pendaftar->bukti_pembayaran && Storage::disk('public')->exists($this->pendaftar->bukti_pembayaran)) {
            Storage::disk('public')->delete($this->pendaftar->bukti_pembayaran);
        }

        // Simpan file baru
        $path = $this->new_proof->store('uploads/pembayaran', 'public');

        $this->pendaftar->update([
            'bukti_pembayaran' => $path
        ]);

        $this->new_proof = null; // Kosongkan state file

        // Karena Admin yang mengunggah, kita asumsikan sudah dicek keabsahannya, jadi langsung Lunas
        $this->updateStatus('lunas', 'Admin mengunggah bukti dan memverifikasi LUNAS');
        
        session()->flash('success', 'Bukti berhasil diunggah oleh Admin dan status otomatis menjadi LUNAS.');
    }

    /**
     * 4. Tolak Pembayaran (Reject)
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

        // Redirect kembali ke halaman asal agar UI terupdate secara total
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.admin.payment-verifier');
    }
}