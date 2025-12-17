<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

class CustomResetPassword extends ResetPassword
{
    use Queueable;

    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        // Generate URL Reset Password yang aman (sama seperti logic default Laravel)
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('🔐 Reset Password Akun PMB')
            ->view('emails.reset-password', [
                'url' => $url,
                'user' => $notifiable
            ]);
    }
}