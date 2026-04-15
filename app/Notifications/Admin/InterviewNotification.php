<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Channels\WhatsAppChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class InterviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $type;
    protected $pendaftar;

    /**
     * @param string $type 'schedule' atau 'score'
     * @param object $pendaftar Model Pendaftar
     */
    public function __construct($type, $pendaftar)
    {
        $this->type = $type;
        $this->pendaftar = $pendaftar;
    }

    public function via(object $notifiable): array
    {
        return ['mail', WhatsAppChannel::class];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)->greeting('Halo, ' . $notifiable->name . '!');

        if ($this->type == 'schedule') {
            $jadwal = Carbon::parse($this->pendaftar->jadwal_wawancara)->translatedFormat('l, d F Y H:i');
            $mail->subject('Jadwal Wawancara PMB Ditetapkan')
                ->line('Jadwal tes wawancara seleksi masuk Anda telah ditetapkan:')
                ->line('Tanggal & Waktu: ' . $jadwal)
                ->line('Pewawancara: ' . $this->pendaftar->pewawancara)
                ->line('Harap bersiap sedia dan hadir tepat waktu.')
                ->action('Lihat Detail di Portal', route('camaba.dashboard'));
        } elseif ($this->type == 'score') {
            $mail->subject('Pengumuman Nilai Wawancara PMB')
                ->line('Proses penilaian tes wawancara PMB Anda telah selesai.')
                ->line('Silakan login ke portal untuk mengecek tahapan kelulusan Anda.')
                ->action('Cek Status', route('camaba.dashboard'));
        }

        return $mail;
    }

    public function toWhatsApp(object $notifiable): array
    {
        $appName = config('app.name', 'UNMARIS');

        if ($this->type == 'schedule') {
            $jadwal = Carbon::parse($this->pendaftar->jadwal_wawancara)->translatedFormat('l, d F Y - H:i');
            $message = "*JADWAL TES WAWANCARA* 🎤\n\n" .
                "Halo *{$notifiable->name}*,\n" .
                "Jadwal wawancara seleksi PMB *{$appName}* Anda telah ditetapkan:\n\n" .
                "📅 *Waktu:* {$jadwal} WITA\n" .
                "👨‍🏫 *Pewawancara:* {$this->pendaftar->pewawancara}\n\n" .
                "Mohon bersiap sedia 15 menit sebelum waktu yang ditentukan dan pastikan Anda berpakaian rapi.\n\n" .
                "Cek portal: " . route('login');
        } elseif ($this->type == 'score') {
            $message = "*HASIL TES WAWANCARA* 🎯\n\n" .
                "Halo *{$notifiable->name}*,\n" .
                "Data penilaian tes wawancara Anda telah diinput oleh panitia.\n\n" .
                "Silakan login ke dashboard PMB untuk memantau status kelulusan akhir Anda:\n" .
                route('login');
        }

        return ['message' => $message];
    }
}
