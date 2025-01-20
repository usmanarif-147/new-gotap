<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CompaignEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $customMessage;

    public $enterpriser, $logo;

    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $customMessage, $enterpriser)
    {
        $this->customMessage = $customMessage;
        $this->enterpriser = $enterpriser;
        $this->subject = $subject;
        $this->logo = asset($this->enterpriser->enterpriser_logo && file_exists(public_path('storage/' . $this->enterpriser->enterpriser_logo)) ? Storage::url($this->enterpriser->enterpriser_logo) : 'logo.png');
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.compaign-email',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }


}
