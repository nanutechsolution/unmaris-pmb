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

            $message = "*PEMBAYARAN PMB UNMARIS BERHASIL DIVERIFIKASI* ✅\n\n" .
                "Halo *{$this->name}*,\n\n" .

                "Panitia PMB Universitas Stella Maris Sumba (*{$appName}*) menginformasikan bahwa pembayaran pendaftaran Anda telah berhasil diverifikasi dan dinyatakan *LUNAS*.\n\n" .

                "*Status pendaftaran Anda saat ini:*\n" .
                "✅ Pembayaran: Lunas\n" .
                "✅ Upload Berkas: Selesai\n" .
                "⏳ Verifikasi Administrasi: Sedang Diproses Panitia PMB\n" .
                "⏳ Tahap Seleksi/Pengumuman: Menunggu Tahap Berikutnya\n\n" .

                "Selanjutnya, panitia PMB akan melakukan pemeriksaan data dan berkas pendaftaran Anda.\n\n" .

                "Apabila terdapat berkas yang belum sesuai, kurang lengkap, atau perlu diperbaiki, informasi revisi akan disampaikan melalui Portal PMB {$appName}.\n\n" .

                "Silakan memantau akun PMB Anda secara berkala untuk melihat perkembangan status pendaftaran.\n\n" .

                "*Login Portal PMB:*\n" .
                route('login');
        } else {

            $message = "*PEMBAYARAN PMB UNMARIS DITOLAK* ⚠️\n\n" .
                "Halo *{$this->name}*,\n\n" .

                "Bukti pembayaran pendaftaran yang Anda unggah belum dapat diverifikasi oleh admin keuangan PMB {$appName}.\n\n" .

                "*Alasan penolakan:*\n" .
                ($this->reason ?? 'Bukti pembayaran tidak sesuai, tidak jelas, atau tidak terbaca.') . "\n\n" .

                "Silakan melakukan upload ulang bukti pembayaran yang valid dan dapat terbaca dengan jelas melalui Portal PMB.\n\n" .

                "*Upload Bukti Pembayaran:*\n" .
                route('camaba.pembayaran');
        }

        return [
            'message' => $message,
        ];
    }
}
