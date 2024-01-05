<?php

namespace App\Mail;

use App\Models\NewsletterList;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OptInMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public NewsletterList $newsletterList,
        public string $temporaryUrl
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Venture - Planner Download',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.opt-in-email',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
