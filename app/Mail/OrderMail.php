<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $company;
    public $pdfContent;
    public $messageText;

    /**
     * @param \App\Models\SalesOrder $invoice
     * @param \App\Models\CompanyInformation $company
     * @param string $pdfContent  (binary string from ->output())
     * @param string $messageText
     */
    public function __construct($order, $company, $pdfContent, $messageText)
    {
        $this->order = $order;
        $this->company = $company;
        $this->pdfContent = $pdfContent;
        $this->messageText = $messageText;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject("Order {$this->order->numdoc}")
                    ->view('emails.Order')
                    ->attachData($this->pdfContent, "Order_{$this->order->numdoc}.pdf", [
                        'mime' => 'application/pdf',
                    ]);
    }
}
