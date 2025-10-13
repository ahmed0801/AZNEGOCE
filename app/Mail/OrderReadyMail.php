<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Invoice;
use App\Models\CompanyInformation;

class OrderReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $company;
    public $messageText;

    public function __construct(Invoice $invoice, CompanyInformation $company, string $messageText)
    {
        $this->invoice = $invoice;
        $this->company = $company;
        $this->messageText = $messageText;
    }

    public function build()
    {
        return $this->subject("Votre commande est prête à retirer")
                    ->view('emails.order_ready')
                    ->with([
                        'invoice' => $this->invoice,
                        'company' => $this->company,
                        'messageText' => $this->messageText
                    ]);
    }
}