<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Channels\WhatsAppChannel;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $name;
    protected $status;
    protected $reason;

    /**
     * @param string $name Nama pendaftar
     * @param string $status Status baru (lunas/ditolak)
     * @param string|null $reason Alasan jika ditolak
     */
    public function __construct($name, $status, $reason = null)
    {
        $this->name = $name;
        $this->status = $status;
        $this->reason = $reason;
    }

    public function via(object $notifiable): array
    {
        // Kirim via Email dan WhatsApp
        return ['mail', WhatsAppChannel::class];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Update Status Pembayaran PMB')
            ->greeting('Halo, ' . $this->name . '!');

        if ($this->status === 'lunas') {
            $mail->line('Pembayaran pendaftaran Anda telah BERHASIL diverifikasi.')
                 ->line('Status Anda sekarang: LUNAS.')
                 ->action('Lengkapi Berkas Sekarang', route('camaba.formulir'));
        } else {
            $mail->error()
                 ->line('Mohon maaf, bukti pembayaran Anda ditolak.')
                 ->line('Alasan: ' . ($this->reason ?? 'Bukti tidak terbaca/salah.'))
                 ->action('Upload Ulang Bukti', route('camaba.pembayaran'));
        }

        return $mail;
    }

    public function toWhatsApp(object $notifiable): array
    {
        $appName = config('app.name', 'UNMARIS');
        
        if ($this->status === 'lunas') {
            $message = "*PEMBAYARAN DIVERIFIKASI* ✅\n\n" .
                       "Halo *{$this->name}*,\n" .
                       "Pembayaran pendaftaran Anda di *{$appName}* telah dinyatakan *LUNAS*.\n\n" .
                       "Langkah selanjutnya:\n" .
                       "Silakan login ke portal dan lengkapi berkas pendaftaran Anda untuk masuk ke tahap seleksi.\n\n" .
                       route('login');
        } else {
            $message = "*PEMBAYARAN DITOLAK* ⚠️\n\n" .
                       "Halo *{$this->name}*,\n" .
                       "Bukti pembayaran yang Anda unggah ditolak oleh admin keuangan.\n\n" .
                       "*Alasan:* " . ($this->reason ?? 'Bukti tidak sesuai/tidak terbaca.') . "\n\n" .
                       "Silakan unggah kembali bukti transfer yang sah melalui tautan berikut:\n" .
                       route('camaba.pembayaran');
        }

        return ['message' => $message];
    }
}