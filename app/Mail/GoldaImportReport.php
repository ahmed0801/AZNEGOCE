<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GoldaImportReport extends Mailable
{
    use Queueable, SerializesModels;

    public $report;
    public $totalActiveItems;
    public $messageText;

    public function __construct($report, $totalActiveItems, $messageText)
    {
        $this->report = $report;
        $this->totalActiveItems = $totalActiveItems;
        $this->messageText = $messageText;
    }

    public function build()
    {
        return $this->subject('Rapport d\'importation GOLDA')
            ->view('emails.golda_import_report', [
                'report' => $this->report,
                'totalActiveItems' => $this->totalActiveItems,
                'messageText' => $this->messageText,
            ]);
    }
}