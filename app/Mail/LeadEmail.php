<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class LeadEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;
    public $customMessage;

    public $enterpriser;

    /**
     * Create a new message instance.
     *
     * @param $lead
     * @param $customMessage
     * @param $enterpriser
     */
    public function __construct($lead, $customMessage, $enterpriser)
    {
        $this->lead = $lead;
        $this->customMessage = $customMessage;
        $this->enterpriser = $enterpriser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->enterpriser->email, $this->enterpriser->name)
            ->view('emails.lead')
            ->subject('Important Information for You')
            ->with([
                'leadName' => $this->lead->name,
                'customMessage' => $this->customMessage,
                'logo' => asset($this->enterpriser->enterpriser_logo && file_exists(public_path('storage/' . $this->enterpriser->enterpriser_logo)) ? Storage::url($this->enterpriser->enterpriser_logo) : 'logo.png')
            ]);
    }
}