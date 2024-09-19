<?php

namespace App\Mail;

use App\Models\pivot\UsersEmails;
use App\Models\pivot\UsersTags;
use App\Models\Tag;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AlbumShared extends Mailable
{
    use Queueable, SerializesModels;

    public $tag;
    public $usersTags;
    public $usersEmails;

    /**
     * Create a new message instance.
     */
    public function __construct(Tag $tag, UsersTags $usersTags)
    {
        $this->tag = $tag;
        $this->usersTags = $usersTags;

        $ue = UsersEmails::where('user_id', $usersTags->user_id)
            ->where('email_id', $usersTags->email_id)
            ->first();

        if (isset($ue->share_blog) && $ue->share_blog === true) {
            $this->usersEmails = $ue;
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('Album') . ': ' . $this->tag->name . ' ' . __('shared with you'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.albumShared',
            with: [
                'url' => route('user.album', $this->usersTags->uuid),
                'profile_url' => ($this->usersEmails) ? route('user.profil', $this->usersEmails->uuid) : '',
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
