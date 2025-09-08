<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesNoteLine extends Model
{
    protected $fillable = [
        'sales_note_id',
        'sales_invoice_id',
        'sales_return_id',
        'article_code',
        'description',
        'quantity',
        'unit_price_ht',
        'remise',
        'total_ligne_ht',
        'total_ligne_ttc',
    ];

    public function salesNote()
    {
        return $this->belongsTo(SalesNote::class);
    }

    public function salesInvoice()
    {
        return $this->belongsTo(Invoice::class, 'sales_invoice_id');
    }

    public function salesReturn()
    {
        return $this->belongsTo(SalesReturn::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'article_code', 'code');
    }
}