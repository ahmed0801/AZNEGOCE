<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $company;
    public $pdfContent;
    public $messageText;

    /**
     * @param \App\Models\Invoice $invoice
     * @param \App\Models\CompanyInformation $company
     * @param string $pdfContent  (binary string from ->output())
     * @param string $messageText
     */
    public function __construct($invoice, $company, $pdfContent, $messageText)
    {
        $this->invoice = $invoice;
        $this->company = $company;
        $this->pdfContent = $pdfContent;
        $this->messageText = $messageText;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject("Facture {$this->invoice->numdoc}")
                    ->view('emails.invoice')
                    ->attachData($this->pdfContent, "facture_{$this->invoice->numdoc}.pdf", [
                        'mime' => 'application/pdf',
                    ]);
    }
}