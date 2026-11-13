<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * « Dernier prix vendu à ce client » : pour chaque référence demandée, les dernières ventes
 * (lignes de bons de livraison) faites à CE client : prix HT, remise, quantité, date.
 * Une seule requête pour tout le document ; PHP 7.4.
 */
class CustomerLastPriceController extends Controller
{
    const KEEP = 3;      // ventes mémorisées par article (la dernière + 2 précédentes)
    const MAX_ROWS = 600;

    /** GET /sales/customers/{customer}/last-prices?codes[]=A&codes[]=B */
    public function index(Request $request, $customer)
    {
        $codes = array_values(array_unique(array_filter(array_map('strval', array_slice((array) $request->input('codes', []), 0, 60)), function ($c) {
            return $c !== '' && $c !== 'CONSIGNE';
        })));
        if (!ctype_digit((string) $customer) || !$codes) return response()->json((object) []);

        try {
            $rows = $this->fetchRows((int) $customer, $codes, $request->input('exclude'));
        } catch (\Throwable $e) {
            \Log::warning('last-prices: ' . $e->getMessage());
            return response()->json((object) []);
        }

        $out = [];
        foreach ($rows as $r) {
            $k = (string) $r->article_code;
            if (!isset($out[$k])) $out[$k] = [];
            if (count($out[$k]) >= self::KEEP) continue;
            $out[$k][] = [
                'price'  => round((float) $r->unit_price_ht, 2),
                'remise' => round((float) $r->remise, 2),
                'qty'    => (float) $r->qty,
                'date'   => $r->sold_at ? date('d/m/Y', strtotime($r->sold_at)) : null,
                'doc_id' => (int) $r->doc_id,
            ];
        }
        return response()->json((object) $out);
    }

    /** Construit la requête selon les colonnes réelles (client sur le BL, ou via la commande liée). */
    private function fetchRows($customerId, array $codes, $exclude)
    {
        $cols = Schema::getColumnListing('delivery_notes');
        $has = function ($c) use ($cols) { return in_array($c, $cols, true); };

        $dateCol = null;
        foreach (['delivery_date', 'date_livraison', 'date', 'created_at'] as $c) {
            if ($has($c)) { $dateCol = $c; break; }
        }

        $q = DB::table('delivery_note_lines as l')
            ->join('delivery_notes as dn', 'dn.id', '=', 'l.delivery_note_id')
            ->whereIn('l.article_code', $codes)
            ->where('l.unit_price_ht', '>', 0);

        // 0) schéma AZNEGOCE : delivery_notes.numclient = customers.code
        if ($has('numclient')) {
            $code = DB::table('customers')->where('id', $customerId)->value('code');
            if ($code === null || $code === '') return collect();
            $q->where('dn.numclient', $code);
            return $this->finish($q, $has, $dateCol, $exclude);
        }

        // 1) le client est directement sur le bon de livraison
        $direct = null;
        foreach (['customer_id', 'client_id', 'id_client', 'customer'] as $c) {
            if ($has($c)) { $direct = $c; break; }
        }
        if ($direct) {
            $q->where('dn.' . $direct, $customerId);
        } else {
            // 2) sinon via la commande liée
            $link = null; $orderTable = null; $orderCust = null;
            foreach (['sales_order_id', 'sale_order_id', 'order_id', 'commande_id', 'commande_vente_id'] as $c) {
                if ($has($c)) { $link = $c; break; }
            }
            if ($link) {
                foreach (['sales_orders', 'sale_orders', 'orders', 'commandes_vente', 'commandes'] as $t) {
                    if (!Schema::hasTable($t)) continue;
                    foreach (['customer_id', 'client_id'] as $c) {
                        if (Schema::hasColumn($t, $c)) { $orderTable = $t; $orderCust = $c; break 2; }
                    }
                }
            }
            if (!$link || !$orderTable) {
                \Log::warning('last-prices: colonne client introuvable dans delivery_notes. Colonnes : ' . implode(',', $cols));
                return collect();
            }
            $q->join($orderTable . ' as o', 'o.id', '=', 'dn.' . $link)->where('o.' . $orderCust, $customerId);
        }

        return $this->finish($q, $has, $dateCol, $exclude);
    }

    private function finish($q, $has, $dateCol, $exclude)
    {
        if ($has('status')) {
            $q->whereNotIn('dn.status', ['cancelled', 'canceled', 'annule', 'annulé', 'draft', 'brouillon']);
        }
        if ($exclude) $q->where('dn.id', '<>', (int) $exclude);

        $select = ['l.article_code', 'l.unit_price_ht', 'l.remise', 'l.delivered_quantity as qty', 'dn.id as doc_id'];
        $select[] = $dateCol ? DB::raw('dn.' . $dateCol . ' as sold_at') : DB::raw('NULL as sold_at');
        return $q->orderByDesc('l.id')->limit(self::MAX_ROWS)->get($select);
    }
}
