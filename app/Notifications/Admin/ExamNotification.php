<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Channels\WhatsAppChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class ExamNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $type;
    protected $pendaftar;

    /**
     * @param string $type Tipe notifikasi: 'schedule' atau 'score'
     * @param object $pendaftar Model Pendaftar
     */
    public function __construct($type, $pendaftar)
    {
        $this->type = $type;
        $this->pendaftar = $pendaftar;
    }

    public function via(object $notifiable): array
    {
        // Kirim via Email dan WhatsApp Custom Channel
        return ['mail', WhatsAppChannel::class];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)->greeting('Halo, ' . $notifiable->name . '!');

        if ($this->type == 'schedule') {
            $jadwal = Carbon::parse($this->pendaftar->jadwal_ujian)->translatedFormat('l, d F Y H:i');
            $mail->subject('Jadwal Ujian Seleksi PMB Ditetapkan')
                ->line('Jadwal ujian seleksi masuk Anda telah dijadwalkan oleh panitia:')
                ->line('Tanggal & Waktu: ' . $jadwal)
                ->line('Lokasi / Ruang: ' . $this->pendaftar->lokasi_ujian)
                ->line('Harap hadir 30 menit sebelum ujian dimulai.')
                ->action('Lihat Detail di Portal', route('camaba.dashboard'));
        } elseif ($this->type == 'score') {
            $mail->subject('Pengumuman Nilai Ujian PMB')
                ->line('Nilai ujian seleksi PMB Anda telah berhasil diinput.')
                ->line('Silakan login ke portal untuk melihat status tahapan kelulusan Anda selanjutnya.')
                ->action('Cek Status', route('camaba.dashboard'));
        }

        return $mail;
    }

    public function toWhatsApp(object $notifiable): array
    {
        $appName = config('app.name', 'UNMARIS');

        if ($this->type == 'schedule') {
            $jadwal = Carbon::parse($this->pendaftar->jadwal_ujian)->translatedFormat('l, d F Y - H:i');
            $message = "*JADWAL UJIAN SELEKSI* 📝\n\n" .
                "Halo *{$notifiable->name}*,\n" .
                "Jadwal ujian seleksi PMB *{$appName}* Anda telah ditetapkan:\n\n" .
                "📅 *Waktu:* {$jadwal} WITA\n" .
                "📍 *Lokasi:* {$this->pendaftar->lokasi_ujian}\n\n" .
                "Harap persiapkan diri Anda dengan baik dan membawa kartu identitas saat hadir.\n\n" .
                "Cek portal: " . route('login');
        } elseif ($this->type == 'score') {
            $message = "*HASIL UJIAN SELEKSI* 🎯\n\n" .
                "Halo *{$notifiable->name}*,\n" .
                "Nilai ujian seleksi (CBT/Tulis) Anda telah selesai diinput oleh panitia.\n\n" .
                "Silakan segera login ke dashboard PMB untuk mengecek status tahapan Anda selanjutnya:\n" .
                route('login');
        }

        return ['message' => $message];
    }
}
