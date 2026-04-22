<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeliveryNoteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $deliveryNote;
    public $company;
    public $pdfContent;
    public $messageText;

    /**
     * @param \App\Models\DeliveryNote $deliveryNote
     * @param \App\Models\CompanyInformation $company
     * @param string $pdfContent
     * @param string $messageText
     */
    public function __construct($deliveryNote, $company, $pdfContent, $messageText)
    {
        $this->deliveryNote = $deliveryNote;
        $this->company = $company;
        $this->pdfContent = $pdfContent;
        $this->messageText = $messageText;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject("Bon de livraison {$this->deliveryNote->numdoc}")
                    ->view('emails.delivery_note')
                    ->attachData($this->pdfContent, "BL_{$this->deliveryNote->numdoc}.pdf", [
                        'mime' => 'application/pdf',
                    ]);
    }
}