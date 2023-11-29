<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class TemporaryPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Student $student,
        public string $temporary_password
    ){}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mindful - Temporary Password Email',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.tempoarary-password-email',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
