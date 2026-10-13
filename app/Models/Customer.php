<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Customer extends Model
{
    protected $fillable = [
        'code','name', 'email', 'phone1', 'phone2', 'address', 'address_delivery', 'city', 'country',
        'solde', 'plafond', 'risque','matfiscal','bank_no','tva_group_id', 'discount_group_id','payment_mode_id','payment_term_id','blocked','type'
    ];

    public function discountGroup()
    {
        return $this->belongsTo(DiscountGroup::class);
    }
    public function paymentMode()
{
    return $this->belongsTo(PaymentMode::class);
}

public function paymentTerm()
{
    return $this->belongsTo(PaymentTerm::class);
}

    public function tvaGroup()
    {
        return $this->belongsTo(TvaGroup::class, 'tva_group_id');
    }
    

     public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
    






    const LOCK_TABLES = [
    'quotes' => 'devis', 'sales_orders' => 'commandes', 'delivery_notes' => 'bons de livraison',
    'sales_invoices' => 'factures', 'invoices' => 'factures', 'sales_returns' => 'retours',
    'sales_notes' => 'avoirs', 'payments' => 'règlements',
];

/** IDs (parmi $ids) non supprimables : clé = id. */
public static function lockedIds(array $ids): array
{
    $locked = [];
    if (!$ids) return $locked;
    foreach (array_keys(self::LOCK_TABLES) as $t) {
        if (Schema::hasTable($t) && Schema::hasColumn($t, 'customer_id')) {
            foreach (DB::table($t)->whereIn('customer_id', $ids)->distinct()->pluck('customer_id') as $id) $locked[$id] = true;
        }
    }
    foreach (self::whereIn('id', $ids)->where('solde', '<>', 0)->pluck('id') as $id) $locked[$id] = true;
    return $locked;
}

public function lockReasons(): array
{
    $r = [];
    if ((float) $this->solde != 0) $r[] = 'solde non nul (' . number_format($this->solde, 2, ',', ' ') . ' €)';
    $docs = [];
    foreach (self::LOCK_TABLES as $t => $label) {
        if (Schema::hasTable($t) && Schema::hasColumn($t, 'customer_id') && DB::table($t)->where('customer_id', $this->id)->exists()) $docs[$label] = true;
    }
    if ($docs) $r[] = 'utilisé dans : ' . implode(', ', array_keys($docs));
    return $r;
}



}
