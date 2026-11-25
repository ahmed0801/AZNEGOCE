<?php

namespace App\Services;

use App\Models\B2bSetting;
use App\Models\PortalQuote;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Ce que le client doit voir à propos de ses devis (pastille du menu + bandeau du tableau de bord) :
 *  - ses demandes dont le devis est prêt (« answered ») ;
 *  - les devis créés dans l'ERP par un vendeur pour lui (statut « Devis »), tant qu'il n'a pas ouvert leur PDF.
 * Toujours limité au client ; ne lève jamais d'exception.
 */
class PortalQuoteNotices
{
    private static $memo = [];

    /** Devis ERP (statut « Devis ») visibles pour ce client, hors devis issus d'une demande portail. */
    public static function erpQuery($customerId)
    {
        $linked = DB::table('portal_quotes')->whereNotNull('sales_order_id')->select('sales_order_id');
        return SalesOrder::where('customer_id', $customerId)->where('status', 'Devis')->whereNotIn('id', $linked);
    }

    public static function showErp(B2bSetting $s = null)
    {
        $s = $s ?: B2bSetting::current();
        return (bool) ($s->portal_show_erp_quotes ?? true);
    }

    public static function for($customerId)
    {
        if (isset(self::$memo[$customerId])) return self::$memo[$customerId];
        $out = ['answered' => [], 'erp' => [], 'count' => 0];
        try {
            if (!Schema::hasTable('portal_quotes')) return self::$memo[$customerId] = $out;
            $out['answered'] = PortalQuote::where('customer_id', $customerId)->where('status', 'answered')->orderByDesc('id')->limit(5)
                ->get(['id', 'number', 'total_ttc', 'valid_until', 'vehicle_text'])->all();
            if (self::showErp() && Schema::hasTable('portal_erp_quote_views')) {
                $seen = DB::table('portal_erp_quote_views')->where('customer_id', $customerId)->select('sales_order_id');
                $out['erp'] = self::erpQuery($customerId)->whereNotIn('id', $seen)->where('created_at', '>=', now()->subDays(45))
                    ->orderByDesc('id')->limit(5)->get(['id', 'numdoc', 'total_ttc', 'order_date'])->all();
            }
            $out['count'] = count($out['answered']) + count($out['erp']);
        } catch (\Throwable $e) { }
        return self::$memo[$customerId] = $out;
    }
}
