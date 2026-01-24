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

        if (!empty($settings->bank_accounts)) {
            $this->bank_accounts = $settings->bank_accounts;
        } else {
            $this->bank_accounts = [[
                'bank' => $settings->nama_bank ?? 'Bank Kampus',
                'rekening' => $settings->nomor_rekening ?? '0000-0000-0000',
                'atas_nama' => $settings->atas_nama_rekening ?? 'Yayasan'
            ]];
        }
    }

    public function save()
    {
        // PERBAIKAN VALIDASI: Max 1MB (1024 KB) & Mimes PDF/Images
        $this->validate([
            'bukti_transfer' => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
        ], [
            'bukti_transfer.required' => 'Bukti transfer wajib diunggah.',
            'bukti_transfer.mimes' => 'Format file harus JPG, PNG, atau PDF.',
            'bukti_transfer.max' => 'Ukuran file maksimal 1MB.',
        ]);

        $pendaftar = Auth::user()->pendaftar;

        if ($pendaftar->status_pembayaran === 'lunas') {
            $this->addError('bukti_transfer', 'Pembayaran sudah lunas. Tidak dapat diubah.');
            return;
        }

        $path = $this->bukti_transfer->store('uploads/pembayaran', 'public');

        if ($pendaftar->bukti_pembayaran && Storage::disk('public')->exists($pendaftar->bukti_pembayaran)) {
            Storage::disk('public')->delete($pendaftar->bukti_pembayaran);
        }

        $pendaftar->update([
            'bukti_pembayaran' => $path,
            'status_pembayaran' => 'menunggu_verifikasi'
        ]);


        try {
            // 1. Cari user yang role-nya BUKAN 'admin' (super admin) dan BUKAN 'camaba'
            //    Biasanya ini role 'keuangan', 'akademik', atau 'panitia'
            $panitia = User::whereNotIn('role', ['admin', 'camaba'])
                ->whereNotNull('nomor_hp') // Pastikan punya nomor HP
                ->get();

            if ($panitia->count() > 0) {
                // Kirim ke semua user yang ditemukan
                Notification::send($panitia, new NotifPembayaran(Auth::user()->name, $this->biaya_pendaftaran));
            } else {
                // Fallback: Jika tidak ada user panitia di DB, kirim ke nomor backup di .env
                if (env('NO_WA_PANITIA')) {
                    Notification::route('whatsapp', env('NO_WA_PANITIA'))
                        ->notify(new NotifPembayaran(Auth::user()->name, $this->biaya_pendaftaran));
                }
            }
        } catch (\Exception $e) {
            // Error silent agar user tidak error 500 jika WA gagal
            Log::error('Gagal kirim notif pembayaran: ' . $e->getMessage());
        }



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
