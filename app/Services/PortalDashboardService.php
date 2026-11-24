<?php

namespace App\Services;

use App\Models\DeliveryNote;
use App\Models\Invoice;
use App\Models\PortalOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

/** Chiffres du tableau de bord de l'accueil client (légers : quelques requêtes agrégées, uniquement pour ce client). */
class PortalDashboardService
{
    /** Rattache une requête de documents au client (customer_id et/ou numclient selon les colonnes), hors brouillons / annulés. */
    public static function scope($q, $c)
    {
        $table = $q->getModel()->getTable();
        $hasId = Schema::hasColumn($table, 'customer_id'); $hasCode = Schema::hasColumn($table, 'numclient');
        if ($hasId && $hasCode) {
            $q->where(function ($w) use ($c) {
                $w->where('customer_id', $c->id)->orWhere(function ($x) use ($c) { $x->whereNull('customer_id')->where('numclient', $c->code); });
            });
        } elseif ($hasCode) {
            $q->where('numclient', $c->code);
        } elseif ($hasId) {
            $q->where('customer_id', $c->id);
        } else {
            $q->whereRaw('1 = 0');
        }
        if (Schema::hasColumn($table, 'status')) {
            $q->where(function ($w) { $w->whereNull('status')->orWhereNotIn('status', ['brouillon', 'annulée', 'annulé', 'annulee']); });
        }
        return $q;
    }

    public function build($customer, $cs)
    {
        $d = ['active' => 0, 'last' => null, 'recent' => [], 'fin' => null];
        $d['active'] = PortalOrder::where('customer_id', $customer->id)->where('status', 'pending')->count();
        $recent = PortalOrder::where('customer_id', $customer->id)->orderByDesc('id')->limit(3)->get(['id', 'number', 'status', 'modified', 'total_ttc', 'created_at', 'lines_count']);
        foreach ($recent as $o) {
            $d['recent'][] = ['id' => $o->id, 'number' => $o->number, 'status' => $o->status, 'label' => $o->status_label, 'mod' => (bool) $o->modified,
                              'total' => (float) $o->total_ttc, 'date' => $o->created_at->format('d/m/Y'), 'lines' => (int) $o->lines_count];
        }
        $d['last'] = $d['recent'][0] ?? null;

        if ($cs->show_finance) {
            try {
                $unpaid = self::scope(Invoice::query(), $customer)->where(function ($w) { $w->whereNull('paid')->orWhere('paid', false); })->get(['id', 'total_ttc', 'due_date']);
                $open = 0.0; $late = 0;
                foreach ($unpaid as $i) { $open += (float) $i->total_ttc; if ($i->due_date && Carbon::parse($i->due_date)->startOfDay()->lt(Carbon::today())) $late++; }
                $dn = self::scope(DeliveryNote::query(), $customer)->where(function ($w) { $w->whereNull('invoiced')->orWhere('invoiced', false); });
                $d['fin'] = ['solde' => (float) ($customer->solde ?? 0), 'unpaid' => $open, 'nUnpaid' => $unpaid->count(), 'late' => $late,
                             'dn' => (float) (clone $dn)->sum('total_ttc'), 'nDn' => (clone $dn)->count()];
            } catch (\Throwable $e) { $d['fin'] = null; }
        }
        return $d;
    }
}
