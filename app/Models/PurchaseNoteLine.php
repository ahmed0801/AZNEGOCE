<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseNoteLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_note_id',
        'article_code',
        'purchase_return_id',
        'purchase_invoice_id',
        'quantity',
        'unit_price_ht',
        'remise',
        'total_ligne_ht',
        'tva',
        'prix_ttc',
        'description',
    ];

    public function purchaseNote()
    {
        return $this->belongsTo(PurchaseNote::class);
    }

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class);
    }

    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'article_code', 'code');
    }
}