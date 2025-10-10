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

    public function __construct($report, $totalActiveItems)
    {
        $this->report = $report;
        $this->totalActiveItems = $totalActiveItems;
    }

    public function build()
    {
        return $this->subject('Rapport d\'importation GOLDA')
            ->markdown('emails.golda_import_report');
    }
}