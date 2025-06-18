<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomTemplateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $subjectLine;
    public string $bodyText;
    public ?string $buttonText = null;
    public ?string $buttonUrl = null;
    public string $bgColor = '#ffffff';
    public string $textColor = '#000000';
    public string $textAlign;

    public function __construct(
        $subjectData,
        $bodyText,
        $buttonText = null,
        $buttonUrl = null,
        $bgColor = '#ffffff',
        $textColor = '#000000',
        $textAlign = 'left'
    ) {
        $this->subjectLine = $subjectData;
        $this->bodyText = $bodyText;
        $this->buttonText = $buttonText;
        $this->buttonUrl = $buttonUrl;
        $this->bgColor = $bgColor;
        $this->textColor = $textColor;
        $this->textAlign = $textAlign;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.custom-template',
            with: [
                'subject' => $this->subjectLine,
                'bodyText' => $this->bodyText,
                'buttonText' => $this->buttonText,
                'buttonUrl' => $this->buttonUrl,
                'bgColor' => $this->bgColor,
                'textColor' => $this->textColor,
            ],
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
