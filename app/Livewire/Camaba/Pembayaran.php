<?php

namespace App\Livewire\Camaba;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\SiteSetting;
use App\Models\User;
use App\Notifications\NotifPembayaran;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class Pembayaran extends Component
{
    use WithFileUploads;

    public $bukti_transfer;
    public $biaya_pendaftaran;
    public $bank_accounts = [];

    public function mount()
    {
        if (!Auth::user()->pendaftar) {
            return redirect()->route('camaba.formulir');
        }

        $settings = SiteSetting::first();
        $this->biaya_pendaftaran = $settings->biaya_pendaftaran ?? 250000;

        // Fallback jika setting bank kosong
        $this->bank_accounts = $settings->bank_accounts ?? [[
            'bank' => 'Bank Mandiri',
            'rekening' => '123-456-7890',
            'atas_nama' => 'Yayasan Universitas'
        ]];
    }

    public function save()
    {
        // VALIDASI: Wajib Gambar, Max 2MB
        $this->validate([
            'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'bukti_transfer.required' => 'Mohon unggah foto bukti transfer.',
            'bukti_transfer.image' => 'File harus berupa gambar (JPG/PNG).',
            'bukti_transfer.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $pendaftar = Auth::user()->pendaftar;

        // Security Check
        if ($pendaftar->status_pembayaran === 'lunas') {
            $this->addError('bukti_transfer', 'Pembayaran sudah lunas. Data terkunci.');
            return;
        }

        // Hapus file lama jika ada
        if ($pendaftar->bukti_pembayaran && Storage::disk('public')->exists($pendaftar->bukti_pembayaran)) {
            Storage::disk('public')->delete($pendaftar->bukti_pembayaran);
        }

        // Simpan file baru
        $path = $this->bukti_transfer->store('uploads/pembayaran', 'public');

        $pendaftar->update([
            'bukti_pembayaran' => $path,
            'status_pembayaran' => 'menunggu_verifikasi'
        ]);

        // Kirim Notifikasi ke Panitia
        $this->notifyAdmin();

        $this->reset('bukti_transfer');
        session()->flash('message', 'Bukti pembayaran berhasil dikirim! Mohon tunggu verifikasi admin 1x24 jam.');
    }

    private function notifyAdmin()
    {
        try {
            $panitia = User::whereIn('role', ['keuangan', 'admin','akademik'])
                ->whereNotNull('nomor_hp')
                ->get();

            if ($panitia->count() > 0) {
                Notification::send($panitia, new NotifPembayaran(Auth::user()->name, $this->biaya_pendaftaran));
            } elseif (env('NO_WA_PANITIA')) {
                Notification::route('whatsapp', env('NO_WA_PANITIA'))
                    ->notify(new NotifPembayaran(Auth::user()->name, $this->biaya_pendaftaran));
            }
        } catch (\Exception $e) {
            Log::error('Notif Pembayaran Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.camaba.pembayaran', [
            'pendaftar' => Auth::user()->pendaftar
        ])->layout('layouts.camaba');
    }
}