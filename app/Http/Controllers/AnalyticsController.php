<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\DeliveryNote;
use App\Models\SalesReturn;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();
        $today = $now->clone()->startOfDay();
        $thisMonthStart = $now->clone()->startOfMonth();
        $thirtyDaysAgo = $now->clone()->subDays(30);

        // === FILTRE PERSONNALISÉ ===
        $customStart = $request->query('start_date');
        $customEnd = $request->query('end_date');
        $period = $request->query('period', 'last30');

        $start = null;
        $end = null;

        if ($customStart && $customEnd) {
            try {
                $start = Carbon::parse($customStart)->startOfDay();
                $end = Carbon::parse($customEnd)->endOfDay(); // Inclure toute la journée

                if ($start->gt($end)) {
                    [$start, $end] = [$end, $start];
                }
            } catch (\Exception $e) {
                $start = $end = null;
            }
        }

        // Si pas de filtre, utiliser l'onglet actif
        if (!$start || !$end) {
            switch ($period) {
                case 'today':
                    $start = $today;
                    $end = $now;
                    break;
                case 'thisMonth':
                    $start = $thisMonthStart;
                    $end = $now;
                    break;
                case 'last30':
                default:
                    $start = $thirtyDaysAgo;
                    $end = $now;
                    break;
            }
        }

        // === DONNÉES DYNAMIQUES ===
        $metrics = $this->getSalesMetrics($start, $end);
        $salesBySeller = $this->getSalesBySeller($start, $end);
        $returnRateBySeller = $this->getReturnRateBySeller($start, $end);
        $caNetByDay = $this->getCaNetByDay($start, $end);
        $returnRateByDay = $this->getReturnRateByDay($start, $end);
        $statusRepartition = $this->getStatusRepartition($start, $end);
        $topClients = $this->getTopClients($start, $end);

        // === Comparatifs 3 mois ===
        $lastMonthStart = $now->clone()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->clone()->subMonth()->endOfMonth();
        $twoMonthsAgoStart = $now->clone()->subMonths(2)->startOfMonth();
        $twoMonthsAgoEnd = $now->clone()->subMonths(2)->endOfMonth();

        $lastMonthMetrics = $this->getSalesMetrics($lastMonthStart, $lastMonthEnd);
        $twoMonthsAgoMetrics = $this->getSalesMetrics($twoMonthsAgoStart, $twoMonthsAgoEnd);

        $monthsCa = [
            $twoMonthsAgoStart->format('M Y') => $twoMonthsAgoMetrics['caNet'],
            $lastMonthStart->format('M Y') => $lastMonthMetrics['caNet'],
            $thisMonthStart->format('M Y') => $this->getSalesMetrics($thisMonthStart, $now)['caNet'],
        ];

        // === Prévision J+1 ===
        $last7 = array_slice($caNetByDay, -7, 7, true);
        $avg7 = !empty($last7) ? round(array_sum($last7) / count($last7), 0) : 0;
        $forecast = $avg7 > 0 ? round($avg7 * 1.03) : 0;

        // CA Net période précédente (30 jours avant)
        $sixtyDaysAgo = $now->clone()->subDays(60);
        $thirtyOneDaysAgo = $now->clone()->subDays(31);
        $caNetPrev = $this->getSalesMetrics($sixtyDaysAgo, $thirtyOneDaysAgo)['caNet'];

        return view('admin.analytics', compact(
            'metrics', 'salesBySeller', 'returnRateBySeller', 'caNetByDay',
            'returnRateByDay', 'statusRepartition', 'topClients',
            'monthsCa', 'forecast', 'start', 'end', 'period', 'caNetPrev'
        ));
    }

    // === MÉTHODES DE CALCUL (inchangées) ===
    private function getSalesMetrics($start, $end)
    {
        $ventes = DeliveryNote::whereIn('status', ['Expédié', 'en_cours'])
            ->whereBetween('delivery_date', [$start, $end])
            ->selectRaw('COALESCE(SUM(total_ttc), 0) as ca, COUNT(DISTINCT id) as nb_bl')
            ->first();

        $retours = SalesReturn::whereBetween('return_date', [$start, $end])
            ->selectRaw('COALESCE(SUM(total_ttc), 0) as ca_retour')
            ->first();

        $caBrut = (float) $ventes->ca;
        $caRetour = (float) $retours->ca_retour;
        $caNet = $caBrut - $caRetour;
        $nbBl = (int) $ventes->nb_bl;
        $panierMoyen = $nbBl > 0 ? round($caNet / $nbBl, 0) : 0;

        return compact('caNet', 'caBrut', 'caRetour', 'nbBl', 'panierMoyen');
    }

    private function getSalesBySeller($start, $end)
    {
        return DB::table(DB::raw("
            (
                SELECT vendeur, SUM(total_ttc) as ca
                FROM delivery_notes
                WHERE status IN ('Expédié', 'en_cours')
                  AND delivery_date BETWEEN ? AND ?
                  AND vendeur IS NOT NULL
                GROUP BY vendeur

                UNION ALL

                SELECT vendeur, -SUM(total_ttc) as ca
                FROM sales_returns
                WHERE return_date BETWEEN ? AND ?
                  AND vendeur IS NOT NULL
                GROUP BY vendeur
            ) as t
        "))
        ->setBindings([$start, $end, $start, $end])
        ->groupBy('vendeur')
        ->selectRaw('vendeur, SUM(ca) as ca_net')
        ->orderByDesc('ca_net')
        ->get()
        ->map(fn($s) => [
            'vendeur' => $s->vendeur,
            'ca' => (float) $s->ca_net
        ]);
    }

    private function getReturnRateBySeller($start, $end)
    {
        return DB::table(DB::raw("
            (
                SELECT COALESCE(vendeur, 'Inconnu') as vendeur, SUM(total_ttc) as ca_brut
                FROM delivery_notes
                WHERE status IN ('Expédié', 'en_cours')
                  AND delivery_date BETWEEN ? AND ?
                  AND (vendeur IS NOT NULL OR vendeur != '')
                GROUP BY COALESCE(vendeur, 'Inconnu')

                UNION ALL

                SELECT COALESCE(vendeur, 'Inconnu') as vendeur, -SUM(total_ttc) as ca_brut
                FROM sales_returns
                WHERE return_date BETWEEN ? AND ?
                  AND (vendeur IS NOT NULL OR vendeur != '')
                GROUP BY COALESCE(vendeur, 'Inconnu')
            ) as t
        "))
        ->setBindings([$start, $end, $start, $end])
        ->groupBy('vendeur')
        ->selectRaw('
            vendeur,
            SUM(CASE WHEN ca_brut > 0 THEN ca_brut ELSE 0 END) as ventes,
            SUM(CASE WHEN ca_brut < 0 THEN ABS(ca_brut) ELSE 0 END) as retours,
            CASE 
                WHEN SUM(CASE WHEN ca_brut > 0 THEN ca_brut ELSE 0 END) > 0
                THEN ROUND((SUM(CASE WHEN ca_brut < 0 THEN ABS(ca_brut) ELSE 0 END) / SUM(CASE WHEN ca_brut > 0 THEN ca_brut ELSE 0 END)) * 100, 1)
                ELSE 0 
            END as taux_retour
        ')
        ->havingRaw('ventes > 0')
        ->orderByDesc('ventes')
        ->get()
        ->map(fn($s) => [
            'vendeur' => $s->vendeur,
            'ventes' => (float) $s->ventes,
            'retours' => (float) $s->retours,
            'taux_retour' => (float) $s->taux_retour
        ]);
    }

    private function getCaNetByDay($start, $end)
    {
        return DB::table(DB::raw("
            (
                SELECT DATE(delivery_date) as date, SUM(total_ttc) as montant
                FROM delivery_notes
                WHERE status IN ('Expédié', 'en_cours')
                  AND delivery_date BETWEEN ? AND ?
                GROUP BY DATE(delivery_date)

                UNION ALL

                SELECT DATE(return_date) as date, -SUM(total_ttc) as montant
                FROM sales_returns
                WHERE return_date BETWEEN ? AND ?
                GROUP BY DATE(return_date)
            ) as combined
        "))
        ->setBindings([$start, $end, $start, $end])
        ->groupBy('date')
        ->orderBy('date')
        ->pluck(DB::raw('SUM(montant)'), 'date')
        ->map(fn($v) => (float) $v)
        ->toArray();
    }

    private function getReturnRateByDay($start, $end)
    {
        return DB::table(DB::raw("
            (
                SELECT DATE(delivery_date) as date, SUM(total_ttc) as ca_brut
                FROM delivery_notes
                WHERE status IN ('Expédié', 'en_cours') AND delivery_date BETWEEN ? AND ?
                GROUP BY DATE(delivery_date)

                UNION ALL

                SELECT DATE(return_date) as date, -SUM(total_ttc) as ca_brut
                FROM sales_returns
                WHERE return_date BETWEEN ? AND ?
                GROUP BY DATE(return_date)
            ) as t
        "))
        ->setBindings([$start, $end, $start, $end])
        ->groupBy('date')
        ->selectRaw('date, 
            CASE 
                WHEN SUM(CASE WHEN ca_brut > 0 THEN ca_brut ELSE 0 END) > 0 
                THEN ROUND((SUM(CASE WHEN ca_brut < 0 THEN ABS(ca_brut) ELSE 0 END) / SUM(CASE WHEN ca_brut > 0 THEN ca_brut ELSE 0 END)) * 100, 1)
                ELSE 0 
            END as rate')
        ->pluck('rate', 'date')
        ->toArray();
    }

    private function getStatusRepartition($start, $end)
    {
        $repartition = DeliveryNote::whereBetween('delivery_date', [$start, $end])
            ->whereIn('status', ['Expédié', 'en_cours', 'Annulé', 'Retourné'])
            ->selectRaw('
                CASE 
                    WHEN status = "Expédié" THEN "Expédié"
                    WHEN status = "en_cours" THEN "En cours"
                    WHEN status = "Annulé" THEN "Annulé"
                    ELSE "Autre"
                END as label,
                COUNT(*) as count
            ')
            ->groupBy('label')
            ->pluck('count', 'label')
            ->toArray();

        return array_merge([
            'Expédié' => 0,
            'En cours' => 0,
            'Annulé' => 0,
            'Autre' => 0
        ], $repartition);
    }

    private function getTopClients($start, $end)
    {
        return DB::table(DB::raw("
            (
                SELECT numclient, SUM(total_ttc) as ca
                FROM delivery_notes
                WHERE status IN ('Expédié', 'en_cours')
                  AND delivery_date BETWEEN ? AND ?
                GROUP BY numclient

                UNION ALL

                SELECT customer_id as numclient, -SUM(total_ttc) as ca
                FROM sales_returns
                WHERE return_date BETWEEN ? AND ?
                GROUP BY customer_id
            ) as t
        "))
        ->setBindings([$start, $end, $start, $end])
        ->leftJoin('customers as c', 't.numclient', '=', 'c.code')
        ->groupBy('t.numclient', 'c.name')
        ->selectRaw('t.numclient, SUM(t.ca) as ca_net, COALESCE(c.name, CONCAT("Client #", DATE_FORMAT(?, "%Ym"))) as client_name', [$start])
        ->orderByDesc('ca_net')
        ->take(10)
        ->get()
        ->map(fn($item) => [
            'client' => $item->client_name ?? 'Client #' . $item->numclient,
            'ca' => (float) $item->ca_net
        ]);
    }
}