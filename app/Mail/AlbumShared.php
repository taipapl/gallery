<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Pivot\UsersTags;
use App\Models\Tag;

class AlbumShared extends Mailable
{
    use Queueable, SerializesModels;

    public $tag;
    public $usersTags;

    /**
     * Create a new message instance.
     */
    public function __construct(Tag $tag, UsersTags $usersTags)
    {
        $this->tag = $tag;
        $this->usersTags = $usersTags;
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
                'url' => route('user_album', $this->usersTags->id)
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