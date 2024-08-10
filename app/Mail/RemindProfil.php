<?php

namespace App\Mail;

use App\Models\Tag;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\pivot\UsersTags;
use App\Models\pivot\UsersEmails;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class RemindProfil extends Mailable
{
    use Queueable, SerializesModels;

    public $usersEmails;

    /**
     * Create a new message instance.
     */
    public function __construct(UsersEmails $UsersEmails)
    {
        $this->usersEmails = $UsersEmails;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('User remind'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.remind-profil',
            with: [
                'url' => route('user.profil', $this->usersEmails->uuid),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}