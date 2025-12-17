<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;

class CustomVerifyEmail extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // 1. Generate Link Verifikasi Manual
        $verificationUrl = $this->verificationUrl($notifiable);

        // 2. Kirim menggunakan Template Custom (Gen Z Style)
        return (new MailMessage)
            ->subject('[PENTING] Verifikasi Email Kamu! 📧')
            ->view('emails.verify-email', [
                'url' => $verificationUrl,
                'user' => $notifiable
            ]);
    }

    // Helper untuk membuat URL Verifikasi yang aman
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}