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

    public $enterpriser;

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
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->enterpriser->email, $this->enterpriser->name)
            ->view('emails.compaign-email')
            ->subject($this->subject)
            ->with([
                'customMessage' => $this->customMessage,
                'logo' => asset($this->enterpriser->enterpriser_logo && file_exists(public_path('storage/' . $this->enterpriser->enterpriser_logo)) ? Storage::url($this->enterpriser->enterpriser_logo) : 'logo.png')
            ]);
    }
}
