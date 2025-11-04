<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\DeliveryNote;
use App\Models\SalesReturn;

class AnalyticsController extends Controller
{
    public function index()
{
    $thirtyDaysAgo = Carbon::now()->subDays(30);
    $sixtyDaysAgo = Carbon::now()->subDays(60);
    $thirtyOneDaysAgo = Carbon::now()->subDays(31);

    // === 1. VENTES BRUTES (30j) ===
    $ventes = DeliveryNote::whereIn('status', ['Expédié', 'en_cours'])
        ->where('delivery_date', '>=', $thirtyDaysAgo)
        ->selectRaw('COALESCE(SUM(total_ttc), 0) as ca, COUNT(DISTINCT id) as nb_bl')
        ->first();

    // === 2. RETOURS (30j) ===
    $retours = SalesReturn::where('return_date', '>=', $thirtyDaysAgo)
        ->selectRaw('COALESCE(SUM(total_ttc), 0) as ca_retour')
        ->first();

    // === 3. CA NET ===
    $caBrut = (float) $ventes->ca;
    $caRetour = (float) $retours->ca_retour;
    $caNet = $caBrut - $caRetour;
    $nbBl = (int) $ventes->nb_bl;
    $panierMoyen = $nbBl > 0 ? round($caNet / $nbBl, 0) : 0;
    $caParJour = round($caNet / 30, 0);

    // === 8. CA NET MOIS PRÉCÉDENT ===
    $ventesPrev = DeliveryNote::whereIn('status', ['Expédié', 'en_cours'])
        ->whereBetween('delivery_date', [$sixtyDaysAgo, $thirtyOneDaysAgo])
        ->selectRaw('COALESCE(SUM(total_ttc), 0) as ca')
        ->first();

    $retoursPrev = SalesReturn::whereBetween('return_date', [$sixtyDaysAgo, $thirtyOneDaysAgo])
        ->selectRaw('COALESCE(SUM(total_ttc), 0) as ca_retour')
        ->first();

    $caNetPrev = (float) $ventesPrev->ca - (float) $retoursPrev->ca_retour;
    $caNetPrev = $caNetPrev > 0 ? $caNetPrev : 1; // Éviter division par 0

    // === 4. CA NET par Jour ===
    $caNetByDay = DB::table(DB::raw("
        (
            SELECT DATE(delivery_date) as date, SUM(total_ttc) as montant
            FROM delivery_notes
            WHERE status IN ('Expédié', 'en_cours')
              AND delivery_date >= ?
            GROUP BY DATE(delivery_date)

            UNION ALL

            SELECT DATE(return_date) as date, -SUM(total_ttc) as montant
            FROM sales_returns
            WHERE return_date >= ?
            GROUP BY DATE(return_date)
        ) as combined
    "))
    ->setBindings([$thirtyDaysAgo, $thirtyDaysAgo])
    ->groupBy('date')
    ->orderBy('date')
    ->pluck(DB::raw('SUM(montant)'), 'date')
    ->map(fn($v) => (float) $v)
    ->toArray();

    // === 5. Top 10 Clients ===
    $topClients = DB::table(DB::raw("
        (
            SELECT numclient, SUM(total_ttc) as ca
            FROM delivery_notes
            WHERE status IN ('Expédié', 'en_cours')
              AND delivery_date >= ?
            GROUP BY numclient

            UNION ALL

            SELECT customer_id as numclient, -SUM(total_ttc) as ca
            FROM sales_returns
            WHERE return_date >= ?
            GROUP BY customer_id
        ) as t
    "))
    ->setBindings([$thirtyDaysAgo, $thirtyDaysAgo])
    ->leftJoin('customers as c', 't.numclient', '=', 'c.code')
    ->groupBy('t.numclient', 'c.name')
    ->selectRaw('t.numclient, SUM(t.ca) as ca_net, COALESCE(c.name, ?) as client_name', ['Client #' . $thirtyDaysAgo->format('Ym')])
    ->orderByDesc('ca_net')
    ->take(10)
    ->get()
    ->map(fn($item) => [
        'client' => $item->client_name ?? 'Client #' . $item->numclient,
        'ca' => (float) $item->ca_net
    ]);

    // === 6. CA NET par Vendeur ===
    $salesBySeller = DB::table(DB::raw("
        (
            SELECT vendeur, SUM(total_ttc) as ca
            FROM delivery_notes
            WHERE status IN ('Expédié', 'en_cours')
              AND delivery_date >= ?
              AND vendeur IS NOT NULL
            GROUP BY vendeur

            UNION ALL

            SELECT vendeur, -SUM(total_ttc) as ca
            FROM sales_returns
            WHERE return_date >= ?
              AND vendeur IS NOT NULL
            GROUP BY vendeur
        ) as t
    "))
    ->setBindings([$thirtyDaysAgo, $thirtyDaysAgo])
    ->groupBy('vendeur')
    ->selectRaw('vendeur, SUM(ca) as ca_net')
    ->orderByDesc('ca_net')
    ->take(5)
    ->get()
    ->map(fn($s) => [
        'vendeur' => $s->vendeur,
        'ca' => (float) $s->ca_net
    ]);

    // === 7. Prévision CA NET ===
    $last7 = array_slice($caNetByDay, -7, 7, true);
    $avg7 = !empty($last7) ? round(array_sum($last7) / count($last7), 0) : 0;
    $forecast = $avg7 > 0 ? round($avg7 * 1.03) : 0;

    // === 9. Taux de retour par jour ===
    $returnRateByDay = DB::table(DB::raw("
        (
            SELECT DATE(delivery_date) as date, SUM(total_ttc) as ca_brut
            FROM delivery_notes
            WHERE status IN ('Expédié', 'en_cours') AND delivery_date >= ?
            GROUP BY DATE(delivery_date)

            UNION ALL

            SELECT DATE(return_date) as date, -SUM(total_ttc) as ca_brut
            FROM sales_returns
            WHERE return_date >= ?
            GROUP BY DATE(return_date)
        ) as t
    "))
    ->setBindings([$thirtyDaysAgo, $thirtyDaysAgo])
    ->groupBy('date')
    ->selectRaw('date, 
        CASE 
            WHEN SUM(CASE WHEN ca_brut > 0 THEN ca_brut ELSE 0 END) > 0 
            THEN ROUND((SUM(CASE WHEN ca_brut < 0 THEN ABS(ca_brut) ELSE 0 END) / SUM(CASE WHEN ca_brut > 0 THEN ca_brut ELSE 0 END)) * 100, 1)
            ELSE 0 
        END as rate')
    ->pluck('rate', 'date')
    ->toArray();



    // === 10. RÉPARTITION STATUT BL ===
$statusRepartition = DeliveryNote::where('delivery_date', '>=', $thirtyDaysAgo)
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

// Valeurs par défaut si vide
$statusRepartition = array_merge([
    'Expédié' => 0,
    'En cours' => 0,
    'Annulé' => 0,
    'Autre' => 0
], $statusRepartition);



return view('admin.analytics', compact(
    'caNet', 'caBrut', 'caRetour', 'nbBl', 'panierMoyen', 'caParJour',
    'caNetByDay', 'topClients', 'salesBySeller', 'forecast',
    'caNetPrev', 'returnRateByDay', 'statusRepartition'
));
}

}