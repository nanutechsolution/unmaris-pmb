<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\Channels\WhatsAppChannel; // Import channel buatan Anda
use Illuminate\Notifications\Messages\MailMessage;

class ManualRegistrationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $name;
    protected $email;
    protected $password;

    public function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Tentukan channel pengiriman (Mail dan WhatsApp buatan Anda).
     */
    public function via(object $notifiable): array
    {
        return ['mail', WhatsAppChannel::class];
    }

    /**
     * Format untuk Email.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Akun PMB UNMARIS Telah Dibuat')
            ->greeting('Halo, ' . $this->name . '!')
            ->line('Panitia PMB telah membuatkan akun untuk Anda.')
            ->line('Email: ' . $this->email)
            ->line('Password: ' . $this->password)
            ->action('Login ke Dashboard', route('login'))
            ->line('Harap segera login dan lengkapi berkas pendaftaran Anda.');
    }

    /**
     * Format untuk WhatsApp (Sesuai method_exists di WhatsAppChannel Anda).
     */
    public function toWhatsApp(object $notifiable): array
    {
        $appName = config('app.name', 'UNMARIS');
        $loginUrl = route('login');

        $message = "*AKUN PMB {$appName} BERHASIL DIBUAT* 🎓\n\n" .
                   "Halo *{$this->name}*,\n" .
                   "Panitia telah mendaftarkan akun Anda di sistem PMB.\n\n" .
                   "Berikut detail login Anda:\n" .
                   "📧 Email: {$this->email}\n" .
                   "🔑 Password: *{$this->password}*\n\n" .
                   "Silakan login melalui tautan berikut:\n" .
                   "{$loginUrl}\n\n" .
                   "Segera lengkapi data profil dan berkas pendaftaran Anda. Terima kasih.";

        return [
            'message' => $message,
        ];
    }
}