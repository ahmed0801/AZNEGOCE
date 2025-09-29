<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data; // ton tableau de données (nom, email, sujet, message)
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Nouveau ticket : ' . $this->data['subject'])
                    ->view('emails.support')
                    ->with('data', $this->data);
    }
}
