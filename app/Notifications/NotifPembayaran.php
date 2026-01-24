<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\WhatsAppChannel;

class NotifPembayaran extends Notification implements ShouldQueue
{
    use Queueable;

    public $nama_pendaftar;
    public $nominal;

    public function __construct($nama_pendaftar, $nominal = 0)
    {
        $this->nama_pendaftar = $nama_pendaftar;
        $this->nominal = $nominal;
    }

    public function via(object $notifiable): array
    {
        return [WhatsAppChannel::class];
    }

    public function toWhatsApp(object $notifiable): array
    {
        $biaya = "Rp " . number_format($this->nominal, 0, ',', '.');
        $tanggal = now()->format('d-m-Y H:i');

        $pesan = "📢 *INFO PENDAFTARAN BARU*\n\n" .
                 "Halo *{$notifiable->name}*,\n" . // Menyapa nama petugas
                 "Ada Camaba yang telah menyelesaikan pendaftaran dan melakukan pembayaran:\n\n" .
                 "👤 Nama: *{$this->nama_pendaftar}*\n" .
                 "💰 Nominal: {$biaya}\n" .
                 "📅 Waktu: {$tanggal}\n\n" .
                 "Mohon segera cek & verifikasi di dashboard admin.";

        // LOGIC TARGET:
        // 1. Ambil dari kolom 'nomor_hp' di tabel users (jika dikirim ke User Model)
        // 2. Fallback ke .env (jika dikirim manual/testing)
        $target = $notifiable->nomor_hp ?? env('NO_WA_PANITIA');

        return [
            'target' => $target, 
            'message' => $pesan
        ];
    }
}