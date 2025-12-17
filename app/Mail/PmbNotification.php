<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class PmbNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $subjectLine;
    public $title;
    public $content;
    public $actionText;
    public $actionUrl;
    public $type; // 'success', 'warning', 'info', 'error'

    /**
     * Create a new message instance.
     */
    public function __construct($user, $subject, $title, $content, $actionText = null, $actionUrl = null, $type = 'info')
    {
        $this->user = $user;
        $this->subjectLine = $subject;
        $this->title = $title;
        $this->content = $content;
        $this->actionText = $actionText;
        $this->actionUrl = $actionUrl;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[PMB UNMARIS] ' . $this->subjectLine,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.notification',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}