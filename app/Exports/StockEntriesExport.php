<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StockEntriesExport implements FromView
{
    protected $entries;

    public function __construct($entries)
    {
        $this->entries = $entries;
    }

    public function view(): View
    {
        return view('stock_export', [
            'entries' => $this->entries
        ]);
    }
}
