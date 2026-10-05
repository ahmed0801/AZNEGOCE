<?php

namespace App\Http\Controllers;

use App\Models\Customer;
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










   public function customerBehavior()
    {
        $now = Carbon::now();

        // Stats de base
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('blocked', 0)->count();
        $avgSolde = Customer::avg('solde');
        $withVehicles = Customer::has('vehicles')->count();
        $topSolde = Customer::orderByDesc('solde')->take(5)->get();
        $types = Customer::groupBy('type')->select('type', DB::raw('count(*) as count'))->get();

        // Comportement clients (RFM simplifié)
        $customersBehavior = DB::table('customers as c')
            ->leftJoin(DB::raw("(
                SELECT numclient, COUNT(id) as purchase_count, SUM(total_ttc) as total_spent, MAX(delivery_date) as last_purchase
                FROM delivery_notes
                WHERE status IN ('Expédié', 'en_cours')
                GROUP BY numclient
            ) as s"), 'c.code', '=', 's.numclient')
            ->leftJoin(DB::raw("(
                SELECT customer_id, SUM(total_ttc) as total_returns
                FROM sales_returns
                GROUP BY customer_id
            ) as r"), 'c.code', '=', 'r.customer_id')
            ->select(
                'c.id', 'c.name', 'c.type', 'c.solde', 'c.city', 'c.blocked',
                DB::raw('COALESCE(s.purchase_count, 0) as purchase_count'),
                DB::raw('COALESCE(s.total_spent - COALESCE(r.total_returns, 0), 0) as total_spent'),
                's.last_purchase'
            )
            ->get();

        $segments = ['new' => 0, 'active' => 0, 'at_risk' => 0, 'lost' => 0];
        foreach ($customersBehavior as $cb) {
            if ($cb->purchase_count == 0) {
                $segments['new']++;
            } elseif ($cb->last_purchase && $now->diffInDays(Carbon::parse($cb->last_purchase)) <= 30) {
                $segments['active']++;
            } elseif ($cb->last_purchase && $now->diffInDays(Carbon::parse($cb->last_purchase)) <= 90) {
                $segments['at_risk']++;
            } else {
                $segments['lost']++;
            }
        }

        // KPIs avancés
        $totalSales = DeliveryNote::whereIn('status', ['Expédié', 'en_cours'])->sum('total_ttc');
        $totalPurchases = DeliveryNote::whereIn('status', ['Expédié', 'en_cours'])->count();
        $uniqueCustomersWithPurchases = DeliveryNote::whereIn('status', ['Expédié', 'en_cours'])->distinct('numclient')->count('numclient');
        $avgOrderValue = $totalPurchases > 0 ? $totalSales / $totalPurchases : 0; // AOV

        $churnRate = $totalCustomers > 0 ? ($segments['lost'] / $totalCustomers) * 100 : 0;
        $retentionRate = $totalCustomers > 0 ? ($segments['active'] / $totalCustomers) * 100 : 0;

        // Nouveaux KPIs
        $purchaseFrequency = $uniqueCustomersWithPurchases > 0 ? $totalPurchases / $uniqueCustomersWithPurchases : 0; // Fréquence d'achat

$repeatCustomersCount = DeliveryNote::whereIn('status', ['Expédié', 'en_cours'])
    ->select('numclient')
    ->groupBy('numclient')
    ->havingRaw('COUNT(*) > 1')
    ->count();

$repeatPurchaseRate = $uniqueCustomersWithPurchases > 0 
    ? ($repeatCustomersCount / $uniqueCustomersWithPurchases) * 100 
    : 0;

        // Temps moyen entre achats (Average Time Between Purchases)
        $avgTimeBetweenPurchases = DB::table('delivery_notes')
            ->whereIn('status', ['Expédié', 'en_cours'])
            ->select('numclient', DB::raw('DATEDIFF(MAX(delivery_date), MIN(delivery_date)) / (COUNT(*) - 1) as avg_days'))
            ->groupBy('numclient')
            ->havingRaw('COUNT(*) > 1')
            ->avg('avg_days') ?? 0;

        // Customer Lifetime Value (CLV simplifié) = AOV * Purchase Frequency * Avg Lifespan (1 / churn rate annualisé approx)
        $avgLifespan = $churnRate > 0 ? 1 / ($churnRate / 100) : 1; // Simplifié en années
        $clv = $avgOrderValue * $purchaseFrequency * $avgLifespan;

        // Recency moyenne (jours depuis dernier achat pour clients actifs)
        $avgRecency = $customersBehavior->where('last_purchase', '!=', null)->avg(function($cb) use ($now) {
            return $now->diffInDays(Carbon::parse($cb->last_purchase));
        }) ?? 0;

        // Cart Abandonment Rate (si tu as une table pour carts, sinon approx via sessions ou assume 0 si pas de data)
        // Pour exemple, assume on a une table 'carts' avec 'abandoned' flag
        // $cartAbandonmentRate = ... ; Pour l'instant, set to 0 or calculate if possible

        $cities = Customer::groupBy('city')->select('city', DB::raw('count(*) as count'))->orderByDesc('count')->take(10)->get();

        // Explications KPIs (array pour vue)
        $kpiExplanations = [
            'totalCustomers' => "Nombre total de clients enregistrés. Indique la taille de la base clients.",
            'activeCustomers' => "Clients non bloqués. Mesure l'activité potentielle de la base.",
            'avgSolde' => "Solde moyen par client. Indique le niveau d'endettement ou crédit moyen.",
            'withVehicles' => "Clients avec véhicules associés. Utile pour personnalisation (e.g., pièces auto).",
            'segments' => "Segmentation RFM: Nouveaux (sans achat), Actifs (achat <30j), À risque (30-90j), Perdus (>90j).",
            'avgOrderValue' => "Valeur moyenne d'une commande. Plus élevé = clients dépensent plus par achat.",
            'churnRate' => "Taux de perte clients (% perdus). Bas = bonne rétention.",
            'retentionRate' => "Taux de rétention (% actifs). Élevé = clients fidèles.",
            'purchaseFrequency' => "Nombre moyen d'achats par client. Indique la fidélité.",
            'repeatPurchaseRate' => "% de clients qui achètent plus d'une fois. Mesure la loyauté.",
            'avgTimeBetweenPurchases' => "Temps moyen (jours) entre achats répétés. Court = engagement élevé.",
            'clv' => "Valeur vie client estimée. Prédit le revenu futur par client.",
            'avgRecency' => "Temps moyen depuis dernier achat. Court = clients récents et engagés.",
            // Ajoute plus si needed
        ];

        // Conseils et recommandations avancés
        $advice = [];
        if ($churnRate > 20) {
            $advice[] = "Taux de churn élevé ({$churnRate}%). Lancez des campagnes de réengagement (emails personnalisés, offres spéciales).";
        }
        if ($retentionRate < 50) {
            $advice[] = "Rétention faible ({$retentionRate}%). Implémentez un programme de fidélité (points, remises).";
        }
        if ($withVehicles / $totalCustomers < 0.5) {
            $advice[] = "Seulement " . round(($withVehicles / $totalCustomers) * 100, 1) . "% avec véhicules. Incitez à l'enregistrement via incentives.";
        }
        $proCount = $types->where('type', 'professionnel')->first()->count ?? 0;
        $proPercent = $totalCustomers > 0 ? ($proCount / $totalCustomers) * 100 : 0;
        if ($proPercent > 40) {
            $advice[] = "Pros : {$proPercent}%. Développez offres B2B (prix volume, crédit).";
        }
        if ($cities->first() && ($cities->first()->count / $totalCustomers > 0.3)) {
            $advice[] = "Concentration à {$cities->first()->city} (" . round(($cities->first()->count / $totalCustomers) * 100, 1) . "%). Événements locaux ou pubs ciblées.";
        }
        if ($purchaseFrequency < 2) {
            $advice[] = "Fréquence d'achat faible ({$purchaseFrequency}). Encouragez upsell/cross-sell.";
        }
        if ($repeatPurchaseRate < 30) {
            $advice[] = "Taux de repeat bas ({$repeatPurchaseRate}%). Améliorez post-achat (follow-up, surveys).";
        }
        if ($avgTimeBetweenPurchases > 60) {
            $advice[] = "Délai entre achats long ({$avgTimeBetweenPurchases} jours). Envoyez rappels périodiques.";
        }
        if ($clv < 500) { // Seuil arbitraire, ajuste
            $advice[] = "CLV bas ({$clv}€). Augmentez AOV via bundles ou premium.";
        }

        $recommendations = [
            "Personnalisez marketing via segments RFM pour booster conversions de 20-30%.",
            "Analysez parcours client pour réduire abandons (e.g., checkout simplifié).",
            "Utilisez data véhicules pour recommandations ciblées, augmentant AOV de 15%.",
            "Implémentez NPS surveys pour mesurer satisfaction et prédire churn.",
            "Cohortes analysis: Comparez comportement par date d'acquisition.",
            "Intégrez AI pour prédictions (e.g., next purchase timing).",
        ];

        return view('admin.customer-behavior', compact(
            'totalCustomers', 'activeCustomers', 'avgSolde', 'withVehicles', 'topSolde', 'types',
            'segments', 'avgOrderValue', 'churnRate', 'retentionRate', 'cities', 'advice', 'recommendations',
            'customersBehavior', 'purchaseFrequency', 'repeatPurchaseRate', 'avgTimeBetweenPurchases',
            'clv', 'avgRecency', 'kpiExplanations'
        ));
    }
}


    
