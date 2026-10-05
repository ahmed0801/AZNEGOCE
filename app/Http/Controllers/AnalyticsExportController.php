<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\DeliveryNote;
use App\Models\SalesReturn;
use Barryvdh\DomPDF\Facade\Pdf;

class AnalyticsExportController extends Controller
{
    // ══════════════════════════════════════════════════════════
    // ENTRY POINT
    // GET /analytics/export?format=excel|pdf&type=recap|vendeurs|clients|details&period=...
    // ══════════════════════════════════════════════════════════
    public function export(Request $request)
    {
        $format = $request->input('format', 'excel');
        $type   = $request->input('type',   'recap');

        list($start, $end, $periodLabel) = $this->resolvePeriod($request);
        $data = $this->collectData($type, $start, $end);

        if ($format === 'pdf') {
            return $this->exportPdf($type, $data, $start, $end, $periodLabel);
        }
        return $this->exportExcel($type, $data, $start, $end, $periodLabel);
    }

    // ══════════════════════════════════════════════════════════
    // RÉSOUDRE LA PÉRIODE
    // ══════════════════════════════════════════════════════════
    private function resolvePeriod(Request $request)
    {
        $now    = Carbon::now();
        $period = $request->input('period', 'last30');
        $customStart = $request->input('start_date');
        $customEnd   = $request->input('end_date');

        if ($customStart && $customEnd) {
            $start = Carbon::parse($customStart)->startOfDay();
            $end   = Carbon::parse($customEnd)->endOfDay();
            $label = 'Du ' . $start->format('d/m/Y') . ' au ' . $end->format('d/m/Y');
        } else {
            switch ($period) {
                case 'today':
                    $start = $now->clone()->startOfDay();
                    $end   = $now->clone();
                    $label = "Aujourd'hui " . $now->format('d/m/Y');
                    break;
                case 'thisMonth':
                    $start = $now->clone()->startOfMonth();
                    $end   = $now->clone();
                    $label = $now->format('F Y');
                    break;
                case 'thisYear':
                    $start = $now->clone()->startOfYear();
                    $end   = $now->clone();
                    $label = 'Année ' . $now->format('Y');
                    break;
                case 'last30':
                default:
                    $start = $now->clone()->subDays(30);
                    $end   = $now->clone();
                    $label = '30 derniers jours';
                    break;
            }
        }

        return array($start, $end, $label);
    }

    // ══════════════════════════════════════════════════════════
    // COLLECTER LES DONNÉES
    // ══════════════════════════════════════════════════════════
    private function collectData($type, $start, $end)
    {
        switch ($type) {
            case 'vendeurs': return $this->getVendeursData($start, $end);
            case 'clients':  return $this->getClientsData($start, $end);
            case 'details':  return $this->getDetailsData($start, $end);
            default:         return $this->getRecapData($start, $end);
        }
    }

    // ── RECAP ──────────────────────────────────────────────────
    private function getRecapData($start, $end)
    {
        $ventes = DeliveryNote::whereIn('status', ['Expédié', 'en_cours'])
            ->whereBetween('delivery_date', [$start, $end])
            ->selectRaw('COALESCE(SUM(total_ttc), 0) as ca, COUNT(DISTINCT id) as nb_bl')
            ->first();

        $retours = SalesReturn::whereBetween('return_date', [$start, $end])
            ->selectRaw('COALESCE(SUM(total_ttc), 0) as ca_retour')
            ->first();

        $caBrut   = (float) $ventes->ca;
        $caRetour = (float) $retours->ca_retour;
        $caNet    = $caBrut - $caRetour;
        $nbBl     = (int) $ventes->nb_bl;

        $parJour = DB::table(DB::raw("
            (
                SELECT DATE(delivery_date) as date, SUM(total_ttc) as montant
                FROM delivery_notes
                WHERE status IN ('Expédié', 'en_cours') AND delivery_date BETWEEN ? AND ?
                GROUP BY DATE(delivery_date)
                UNION ALL
                SELECT DATE(return_date) as date, -SUM(total_ttc) as montant
                FROM sales_returns
                WHERE return_date BETWEEN ? AND ?
                GROUP BY DATE(return_date)
            ) as combined
        "))
        ->setBindings([$start, $end, $start, $end])
        ->groupBy('date')->orderBy('date')
        ->selectRaw('date, SUM(montant) as ca_net')
        ->get();

        return array(
            'caNet'       => $caNet,
            'caBrut'      => $caBrut,
            'caRetour'    => $caRetour,
            'nbBl'        => $nbBl,
            'panierMoyen' => $nbBl > 0 ? round($caNet / $nbBl, 2) : 0,
            'parJour'     => $parJour,
        );
    }

    // ── VENDEURS — requête corrigée ────────────────────────────
    private function getVendeursData($start, $end)
    {
        // CA brut par vendeur (BL)
        $ventes = DB::table('delivery_notes')
            ->whereIn('status', ['Expédié', 'en_cours'])
            ->whereBetween('delivery_date', [$start, $end])
            ->whereNotNull('vendeur')
            ->where('vendeur', '!=', '')
            ->groupBy('vendeur')
            ->selectRaw('vendeur, SUM(total_ttc) as ca_brut, COUNT(id) as nb_bl')
            ->get()
            ->keyBy('vendeur');

        // Retours par vendeur
        $retours = DB::table('sales_returns')
            ->whereBetween('return_date', [$start, $end])
            ->whereNotNull('vendeur')
            ->where('vendeur', '!=', '')
            ->groupBy('vendeur')
            ->selectRaw('vendeur, SUM(total_ttc) as ca_retour')
            ->get()
            ->keyBy('vendeur');

        // Fusionner
        $vendeurs = collect();
        foreach ($ventes as $nom => $v) {
            $retour   = isset($retours[$nom]) ? (float)$retours[$nom]->ca_retour : 0;
            $caBrut   = (float)$v->ca_brut;
            $caNet    = $caBrut - $retour;
            $taux     = $caBrut > 0 ? round(($retour / $caBrut) * 100, 1) : 0;
            $vendeurs->push((object) array(
                'vendeur'    => $nom,
                'ca_brut'    => $caBrut,
                'ca_retour'  => $retour,
                'ca_net'     => $caNet,
                'nb_bl'      => (int)$v->nb_bl,
                'taux_retour'=> $taux,
            ));
        }

        // Ajouter vendeurs avec retours seulement (pas de BL)
        foreach ($retours as $nom => $r) {
            if (!$ventes->has($nom)) {
                $vendeurs->push((object) array(
                    'vendeur'    => $nom,
                    'ca_brut'    => 0,
                    'ca_retour'  => (float)$r->ca_retour,
                    'ca_net'     => -(float)$r->ca_retour,
                    'nb_bl'      => 0,
                    'taux_retour'=> 0,
                ));
            }
        }

        return array('rows' => $vendeurs->sortByDesc('ca_net')->values());
    }

    // ── CLIENTS — requête corrigée ─────────────────────────────
    private function getClientsData($start, $end)
    {
        // CA brut par client
        $ventes = DB::table('delivery_notes')
            ->whereIn('status', ['Expédié', 'en_cours'])
            ->whereBetween('delivery_date', [$start, $end])
            ->whereNotNull('numclient')
            ->groupBy('numclient')
            ->selectRaw('numclient, SUM(total_ttc) as ca_brut, COUNT(id) as nb_bl')
            ->get()
            ->keyBy('numclient');

        // Retours par client
        $retours = DB::table('sales_returns')
            ->whereBetween('return_date', [$start, $end])
            ->whereNotNull('customer_id')
            ->groupBy('customer_id')
            ->selectRaw('customer_id, SUM(total_ttc) as ca_retour')
            ->get()
            ->keyBy('customer_id');

        // Noms clients
        $clients = DB::table('customers')
            ->whereIn('code', $ventes->keys()->merge($retours->keys())->unique()->toArray())
            ->pluck('name', 'code');

        $rows = collect();
        foreach ($ventes as $code => $v) {
            $retour  = isset($retours[$code]) ? (float)$retours[$code]->ca_retour : 0;
            $caBrut  = (float)$v->ca_brut;
            $rows->push((object) array(
                'numclient'   => $code,
                'client_name' => isset($clients[$code]) ? $clients[$code] : 'Client #' . $code,
                'ca_brut'     => $caBrut,
                'ca_retour'   => $retour,
                'ca_net'      => $caBrut - $retour,
                'nb_bl'       => (int)$v->nb_bl,
            ));
        }

        return array('rows' => $rows->sortByDesc('ca_net')->values());
    }

    // ── DETAILS ────────────────────────────────────────────────
    private function getDetailsData($start, $end)
    {
        $bls = DeliveryNote::whereIn('status', ['Expédié', 'en_cours'])
            ->whereBetween('delivery_date', [$start, $end])
            ->with('customer')
            ->orderBy('delivery_date', 'desc')
            ->get(['id', 'numdoc', 'delivery_date', 'numclient', 'vendeur', 'total_ttc', 'status']);

        return array('rows' => $bls);
    }

    // ══════════════════════════════════════════════════════════
    // EXPORT EXCEL (CSV UTF-8 avec BOM)
    // ══════════════════════════════════════════════════════════
    private function exportExcel($type, $data, $start, $end, $periodLabel)
    {
        $filename = 'analytics_' . $type . '_' . $start->format('Ymd') . '_' . $end->format('Ymd') . '.csv';
        $rows = array();

        // En-tête
        $rows[] = array('RAPPORT ANALYTIQUE AZ NEGOCE');
        $rows[] = array('Période : ' . $periodLabel);
        $rows[] = array('Généré le : ' . now()->format('d/m/Y H:i'));
        $rows[] = array('Type : ' . strtoupper($type));
        $rows[] = array();

        switch ($type) {
            case 'recap':
                $rows[] = array('RÉSUMÉ GÉNÉRAL');
                $rows[] = array('CA Brut TTC',   $this->fmt($data['caBrut'])   . ' €');
                $rows[] = array('Retours TTC',    $this->fmt($data['caRetour']) . ' €');
                $rows[] = array('CA Net TTC',     $this->fmt($data['caNet'])    . ' €');
                $rows[] = array('Nb BL',          $data['nbBl']);
                $rows[] = array('Panier moyen',   $this->fmt($data['panierMoyen']) . ' €');
                $rows[] = array();
                $rows[] = array('DÉTAIL PAR JOUR');
                $rows[] = array('Date', 'CA Net TTC (€)');
                foreach ($data['parJour'] as $j) {
                    $rows[] = array(
                        Carbon::parse($j->date)->format('d/m/Y'),
                        $this->fmt((float)$j->ca_net)
                    );
                }
                break;

            case 'vendeurs':
                $rows[] = array('Rang', 'Vendeur', 'CA Brut (€)', 'Retours (€)', 'CA Net (€)', 'Nb BL', 'Taux retour (%)');
                $rank = 1;
                foreach ($data['rows'] as $r) {
                    $rows[] = array(
                        $rank++,
                        $r->vendeur,
                        $this->fmt($r->ca_brut),
                        $this->fmt($r->ca_retour),
                        $this->fmt($r->ca_net),
                        $r->nb_bl,
                        number_format($r->taux_retour, 1, ',', ' ') . '%',
                    );
                }
                $rows[] = array();
                $rows[] = array(
                    'TOTAL', '',
                    $this->fmt($data['rows']->sum('ca_brut')),
                    $this->fmt($data['rows']->sum('ca_retour')),
                    $this->fmt($data['rows']->sum('ca_net')),
                    $data['rows']->sum('nb_bl'), '',
                );
                break;

            case 'clients':
                $rows[] = array('Rang', 'Client', 'Code', 'CA Brut (€)', 'Retours (€)', 'CA Net (€)', 'Nb BL');
                $rank = 1;
                foreach ($data['rows'] as $r) {
                    $rows[] = array(
                        $rank++,
                        $r->client_name,
                        $r->numclient,
                        $this->fmt($r->ca_brut),
                        $this->fmt($r->ca_retour),
                        $this->fmt($r->ca_net),
                        $r->nb_bl,
                    );
                }
                $rows[] = array();
                $rows[] = array(
                    'TOTAL', '', '',
                    $this->fmt($data['rows']->sum('ca_brut')),
                    $this->fmt($data['rows']->sum('ca_retour')),
                    $this->fmt($data['rows']->sum('ca_net')),
                    $data['rows']->sum('nb_bl'),
                );
                break;

            case 'details':
                $rows[] = array('N° BL', 'Date', 'Client', 'Vendeur', 'Total TTC (€)', 'Statut');
                foreach ($data['rows'] as $bl) {
                    $rows[] = array(
                        $bl->numdoc,
                        Carbon::parse($bl->delivery_date)->format('d/m/Y'),
                        optional($bl->customer)->name ?? $bl->numclient,
                        $bl->vendeur ?? '-',
                        $this->fmt((float)$bl->total_ttc),
                        $bl->status,
                    );
                }
                $rows[] = array();
                $rows[] = array('TOTAL TTC', '', '', '', $this->fmt($data['rows']->sum('total_ttc')) . ' €', '');
                break;
        }

        // Générer CSV avec BOM UTF-8
        $output = "\xEF\xBB\xBF";
        foreach ($rows as $row) {
            $cells = array_map(function($cell) {
                $cell = str_replace('"', '""', (string)$cell);
                return '"' . $cell . '"';
            }, $row);
            $output .= implode(';', $cells) . "\r\n";
        }

        return response($output, 200, array(
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
        ));
    }

    // ══════════════════════════════════════════════════════════
    // EXPORT PDF (Dompdf)
    // ══════════════════════════════════════════════════════════
    private function exportPdf($type, $data, $start, $end, $periodLabel)
    {
        $typeLabels = array(
            'recap'    => 'Récapitulatif',
            'vendeurs' => 'Classement Vendeurs',
            'clients'  => 'Top Clients',
            'details'  => 'Détail des BL',
        );

        $filename  = 'analytics_' . $type . '_' . $start->format('Ymd') . '_' . $end->format('Ymd') . '.pdf';
        $typeLabel = isset($typeLabels[$type]) ? $typeLabels[$type] : $type;
        $generatedAt = now()->format('d/m/Y à H:i');

        $html = $this->buildPdfHtml($type, $data, $periodLabel, $typeLabel, $generatedAt);

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->setOptions(array(
                'defaultFont'    => 'DejaVu Sans',
                'isRemoteEnabled'=> false,
                'isHtml5ParserEnabled' => true,
                'dpi'            => 120,
            ));

        return $pdf->download($filename);
    }

    
    


    private function buildPdfHtml($type, $data, $periodLabel, $typeLabel, $generatedAt)
{
    $css = '
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1a2b4a;
            background: white;
        }
 
        /* ── HEADER ────────────────────────────────────── */
        .header {
            background-color: #1E2D4A;
            padding: 20px 28px 16px;
            margin-bottom: 0;
        }
        .header-top {
            border-bottom: 1px solid #2D4A8A;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }
        .header-company {
            font-size: 9px;
            color: #7FA0C8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .header-title {
            font-size: 20px;
            font-weight: bold;
            color: white;
            margin-bottom: 2px;
        }
        .header-subtitle {
            font-size: 11px;
            color: #90B4D8;
        }
        .header-meta {
            font-size: 9px;
            color: #7FA0C8;
            margin-top: 2px;
        }
        .period-badge {
            display: inline-block;
            background-color: #3B82F6;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 4px 14px;
            border-radius: 20px;
        }
 
        /* ── SEPARATEUR ─────────────────────────────────── */
        .separator {
            height: 4px;
            background-color: #3B82F6;
            margin-bottom: 20px;
        }
 
        /* ── CONTENT WRAPPER ────────────────────────────── */
        .content { padding: 0 24px 24px; }
 
        /* ── HERO STATS (recap) ─────────────────────────── */
        .hero-stats {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .hero-stats td {
            width: 33%;
            padding: 16px 14px;
            border: 2px solid #E2E8F0;
            text-align: center;
            vertical-align: middle;
        }
        .hero-stats .stat-val {
            font-size: 22px;
            font-weight: bold;
            line-height: 1.1;
        }
        .hero-stats .stat-lbl {
            font-size: 9px;
            color: #6B7A99;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-top: 5px;
        }
        .hero-stats .stat-sub {
            font-size: 8px;
            color: #9CA3AF;
            margin-top: 2px;
        }
        .stat-net  { background-color: #F0FDF4; border-color: #6EE7B7 !important; }
        .stat-net .stat-val  { color: #065F46; }
        .stat-brut { background-color: #EFF6FF; border-color: #BFDBFE !important; }
        .stat-brut .stat-val { color: #1D4ED8; }
        .stat-ret  { background-color: #FFF5F5; border-color: #FECACA !important; }
        .stat-ret .stat-val  { color: #DC2626; }
        .stat-kpi  { background-color: #F8FAFF; border-color: #E2E8F0 !important; }
        .stat-kpi .stat-val  { color: #1E2D4A; }
 
        /* ── SECTION TITLE ──────────────────────────────── */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: white;
            background-color: #1E2D4A;
            padding: 7px 12px;
            margin-bottom: 0;
        }
 
        /* ── DATA TABLE ─────────────────────────────────── */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data thead tr {
            background-color: #2D4A8A;
        }
        table.data th {
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: .05em;
        }
        table.data th.right { text-align: right; }
        table.data td {
            padding: 8px 10px;
            font-size: 10.5px;
            border-bottom: 1px solid #E8EFF8;
            vertical-align: middle;
        }
        table.data td.right { text-align: right; }
        table.data tr.even td { background-color: #F8FAFF; }
        table.data tr.top1 td { background-color: #FFFBEB; }
        table.data tr.top2 td { background-color: #F9FAFB; }
        table.data tr.top3 td { background-color: #F9FAFB; }
 
        /* Ligne total */
        table.data tr.total td {
            background-color: #1E2D4A;
            color: white;
            font-weight: bold;
            font-size: 11px;
            padding: 9px 10px;
            border-bottom: none;
        }
        table.data tr.total td.highlight {
            color: #6EE7B7;
            font-size: 13px;
        }
 
        /* Colonne CA Net — mise en avant */
        .ca-net {
            font-weight: bold;
            font-size: 12px;
        }
        .ca-net-pos { color: #065F46; }
        .ca-net-neg { color: #DC2626; }
 
        /* Barres visuelles proportion */
        .bar-wrap {
            background-color: #E2E8F0;
            border-radius: 4px;
            height: 6px;
            min-width: 60px;
        }
        .bar-fill {
            background-color: #3B82F6;
            border-radius: 4px;
            height: 6px;
        }
 
        /* Rang / médaille */
        .rank {
            font-size: 13px;
            text-align: center;
        }
 
        /* Badges statut */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 8.5px;
            font-weight: bold;
        }
        .badge-green { background-color: #D1FAE5; color: #065F46; }
        .badge-red   { background-color: #FEE2E2; color: #DC2626; }
        .badge-blue  { background-color: #DBEAFE; color: #1D4ED8; }
        .badge-warn  { background-color: #FEF3C7; color: #92400E; }
 
        /* Taux retour coloré */
        .taux-ok   { color: #065F46; font-weight: bold; }
        .taux-warn { color: #D97706; font-weight: bold; }
        .taux-bad  { color: #DC2626; font-weight: bold; }
 
        /* Stats secondaires (2 colonnes) */
        .stats-secondary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .stats-secondary td {
            padding: 7px 12px;
            border: 1px solid #E2E8F0;
            font-size: 10.5px;
        }
        .stats-secondary .lbl {
            color: #6B7A99;
            font-size: 9.5px;
            width: 35%;
            background-color: #F8FAFF;
        }
        .stats-secondary .val {
            font-weight: bold;
            color: #1E2D4A;
            font-size: 12px;
        }
 
        /* ── FOOTER ─────────────────────────────────────── */
        .footer {
            text-align: center;
            color: #9CA3AF;
            font-size: 8px;
            margin-top: 24px;
            padding-top: 10px;
            border-top: 1px solid #E2E8F0;
        }
 
        /* ── CALLOUT CA NET ─────────────────────────────── */
        .callout-net {
            background-color: #F0FDF4;
            border-left: 5px solid #10B981;
            padding: 12px 16px;
            margin-bottom: 16px;
        }
        .callout-net .val {
            font-size: 28px;
            font-weight: bold;
            color: #065F46;
        }
        .callout-net .lbl {
            font-size: 10px;
            color: #6B7A99;
            text-transform: uppercase;
            letter-spacing: .05em;
        }
        .callout-net .detail {
            font-size: 9px;
            color: #9CA3AF;
            margin-top: 3px;
        }
    ';
 
    // ── Calcul max CA net pour les barres de proportion ──
    $maxCaNet = 1;
    if (in_array($type, ['vendeurs', 'clients']) && !empty($data['rows'])) {
        foreach ($data['rows'] as $r) {
            $val = isset($r->ca_net) ? (float)$r->ca_net : 0;
            if ($val > $maxCaNet) $maxCaNet = $val;
        }
    }
 
    $body = '';
 
    switch ($type) {
 
        // ════════════════════════════════════════════════
        // RECAP
        // ════════════════════════════════════════════════
        case 'recap':
            // Callout CA Net
            $body .= '
            <div class="callout-net">
                <div class="lbl">CA Net TTC — résultat de la période</div>
                <div class="val">' . $this->fmt($data['caNet']) . ' €</div>
                <div class="detail">
                    CA Brut ' . $this->fmt($data['caBrut']) . ' €
                    &nbsp;—&nbsp;
                    Retours - ' . $this->fmt($data['caRetour']) . ' €
                    &nbsp;=&nbsp;
                    <strong>CA Net ' . $this->fmt($data['caNet']) . ' €</strong>
                </div>
            </div>';
 
            // Hero stats 4 blocs
            $body .= '
            <table class="hero-stats">
                <tr>
                    <td class="stat-brut">
                        <div class="stat-val">' . $this->fmt($data['caBrut']) . ' €</div>
                        <div class="stat-lbl">CA Brut TTC</div>
                    </td>
                    <td class="stat-ret">
                        <div class="stat-val">- ' . $this->fmt($data['caRetour']) . ' €</div>
                        <div class="stat-lbl">Retours TTC</div>
                    </td>
                    <td class="stat-kpi">
                        <div class="stat-val">' . $data['nbBl'] . '</div>
                        <div class="stat-lbl">Bons de livraison</div>
                    </td>
                    <td class="stat-kpi">
                        <div class="stat-val">' . $this->fmt($data['panierMoyen']) . ' €</div>
                        <div class="stat-lbl">Panier moyen</div>
                    </td>
                </tr>
            </table>';
 
            $body .= '<div class="section-title">CA Net par jour</div>';
            $body .= '<table class="data">
                <thead><tr>
                    <th>Date</th>
                    <th class="right">CA Net TTC (€)</th>
                    <th>Tendance</th>
                </tr></thead><tbody>';
            $i = 0;
            $maxDay = 1;
            foreach ($data['parJour'] as $j) {
                if ((float)$j->ca_net > $maxDay) $maxDay = (float)$j->ca_net;
            }
            foreach ($data['parJour'] as $j) {
                $val   = (float)$j->ca_net;
                $pct   = $maxDay > 0 ? round(max(0, $val) / $maxDay * 100) : 0;
                $cls   = $i % 2 === 0 ? '' : 'even';
                $color = $val >= 0 ? '#065F46' : '#DC2626';
                $body .= '<tr class="' . $cls . '">
                    <td><strong>' . Carbon::parse($j->date)->format('d/m/Y') . '</strong>
                        <span style="color:#9CA3AF;font-size:9px;margin-left:4px;">' . Carbon::parse($j->date)->format('l') . '</span>
                    </td>
                    <td class="right" style="font-weight:bold;color:' . $color . ';font-size:12px;">'
                        . $this->fmt($val) . ' €</td>
                    <td>
                        <div class="bar-wrap"><div class="bar-fill" style="width:' . $pct . '%;background-color:' . ($val >= 0 ? '#3B82F6' : '#EF4444') . ';"></div></div>
                    </td>
                </tr>';
                $i++;
            }
            // Total
            $totalJours = 0;
            foreach ($data['parJour'] as $j) $totalJours += (float)$j->ca_net;
            $body .= '<tr class="total">
                <td>TOTAL PÉRIODE</td>
                <td class="right highlight">' . $this->fmt($totalJours) . ' €</td>
                <td></td>
            </tr>';
            $body .= '</tbody></table>';
            break;
 
        // ════════════════════════════════════════════════
        // VENDEURS
        // ════════════════════════════════════════════════
        case 'vendeurs':
            $totalNet    = $data['rows']->sum('ca_net');
            $totalBrut   = $data['rows']->sum('ca_brut');
            $totalRetour = $data['rows']->sum('ca_retour');
            $totalBl     = $data['rows']->sum('nb_bl');
 
            // Callout CA Net global
            $body .= '
            <div class="callout-net">
                <div class="lbl">CA Net TTC total — tous vendeurs confondus</div>
                <div class="val">' . $this->fmt($totalNet) . ' €</div>
                <div class="detail">
                    ' . count($data['rows']) . ' vendeur(s) actifs
                    &nbsp;·&nbsp; ' . $totalBl . ' BL au total
                    &nbsp;·&nbsp; CA Brut ' . $this->fmt($totalBrut) . ' €
                    &nbsp;·&nbsp; Retours - ' . $this->fmt($totalRetour) . ' €
                </div>
            </div>';
 
            $body .= '<div class="section-title">Classement par CA Net — du meilleur au moins bon</div>';
            $body .= '<table class="data">
                <thead><tr>
                    <th style="width:4%;">#</th>
                    <th style="width:22%;">Vendeur</th>
                    <th class="right" style="width:16%;background-color:#1a3a2a;">CA NET TTC ★</th>
                    <th class="right" style="width:14%;">CA Brut</th>
                    <th class="right" style="width:12%;">Retours</th>
                    <th class="right" style="width:8%;">Nb BL</th>
                    <th class="right" style="width:10%;">Taux retour</th>
                    <th style="width:14%;">Part du CA Net</th>
                </tr></thead><tbody>';
 
            $rank = 1;
            foreach ($data['rows'] as $r) {
                $caNet   = (float)$r->ca_net;
                $caBrut  = (float)$r->ca_brut;
                $caRet   = (float)$r->ca_retour;
                $taux    = (float)$r->taux_retour;
                $pct     = $maxCaNet > 0 ? round(max(0, $caNet) / $maxCaNet * 100) : 0;
                $part    = $totalNet  > 0 ? round($caNet / $totalNet * 100, 1) : 0;
 
                $medals  = array(1=>'🥇', 2=>'🥈', 3=>'🥉');
                $medal   = isset($medals[$rank]) ? $medals[$rank] : $rank;
                $rowCls  = $rank === 1 ? 'top1' : ($rank === 2 ? 'top2' : ($rank === 3 ? 'top3' : ($rank % 2 === 0 ? 'even' : '')));
                $netCls  = $caNet >= 0 ? 'ca-net-pos' : 'ca-net-neg';
                $tauCls  = $taux <= 5 ? 'taux-ok' : ($taux <= 15 ? 'taux-warn' : 'taux-bad');
 
                $body .= '<tr class="' . $rowCls . '">
                    <td class="rank">' . $medal . '</td>
                    <td><strong>' . htmlspecialchars($r->vendeur) . '</strong></td>
                    <td class="right ca-net ' . $netCls . '">' . $this->fmt($caNet) . ' €</td>
                    <td class="right" style="color:#1D4ED8;">' . $this->fmt($caBrut) . ' €</td>
                    <td class="right" style="color:#DC2626;">' . ($caRet > 0 ? '- ' : '') . $this->fmt($caRet) . ' €</td>
                    <td class="right">' . $r->nb_bl . '</td>
                    <td class="right"><span class="' . $tauCls . '">' . number_format($taux, 1, ',', ' ') . '%</span></td>
                    <td>
                        <div style="font-size:9px;color:#6B7A99;margin-bottom:2px;">' . $part . '% du total</div>
                        <div class="bar-wrap"><div class="bar-fill" style="width:' . $pct . '%;"></div></div>
                    </td>
                </tr>';
                $rank++;
            }
 
            $totNetCls = $totalNet >= 0 ? '#6EE7B7' : '#FCA5A5';
            $body .= '<tr class="total">
                <td colspan="2">TOTAL</td>
                <td class="right" style="font-size:14px;color:' . $totNetCls . ';">' . $this->fmt($totalNet) . ' €</td>
                <td class="right">' . $this->fmt($totalBrut) . ' €</td>
                <td class="right">' . $this->fmt($totalRetour) . ' €</td>
                <td class="right">' . $totalBl . '</td>
                <td colspan="2"></td>
            </tr>';
            $body .= '</tbody></table>';
            break;
 
        // ════════════════════════════════════════════════
        // CLIENTS
        // ════════════════════════════════════════════════
        case 'clients':
            $totalNet    = $data['rows']->sum('ca_net');
            $totalBrut   = $data['rows']->sum('ca_brut');
            $totalRetour = $data['rows']->sum('ca_retour');
            $totalBl     = $data['rows']->sum('nb_bl');
 
            $body .= '
            <div class="callout-net">
                <div class="lbl">CA Net TTC total — tous clients confondus</div>
                <div class="val">' . $this->fmt($totalNet) . ' €</div>
                <div class="detail">
                    ' . count($data['rows']) . ' client(s) actifs sur la période
                    &nbsp;·&nbsp; ' . $totalBl . ' BL
                    &nbsp;·&nbsp; CA Brut ' . $this->fmt($totalBrut) . ' €
                    &nbsp;·&nbsp; Retours - ' . $this->fmt($totalRetour) . ' €
                </div>
            </div>';
 
            $body .= '<div class="section-title">Classement clients par CA Net — du meilleur au moins bon</div>';
            $body .= '<table class="data">
                <thead><tr>
                    <th style="width:4%;">#</th>
                    <th style="width:26%;">Client</th>
                    <th style="width:10%;color:#9CA3AF;font-size:8px;">Code</th>
                    <th class="right" style="width:16%;background-color:#1a3a2a;">CA NET TTC ★</th>
                    <th class="right" style="width:14%;">CA Brut</th>
                    <th class="right" style="width:12%;">Retours</th>
                    <th class="right" style="width:6%;">BL</th>
                    <th style="width:12%;">Part</th>
                </tr></thead><tbody>';
 
            $rank = 1;
            foreach ($data['rows'] as $r) {
                $caNet  = (float)$r->ca_net;
                $pct    = $maxCaNet > 0 ? round(max(0, $caNet) / $maxCaNet * 100) : 0;
                $part   = $totalNet  > 0 ? round($caNet / $totalNet * 100, 1) : 0;
                $medals = array(1=>'🥇', 2=>'🥈', 3=>'🥉');
                $medal  = isset($medals[$rank]) ? $medals[$rank] : $rank;
                $rowCls = $rank === 1 ? 'top1' : ($rank === 2 ? 'top2' : ($rank === 3 ? 'top3' : ($rank % 2 === 0 ? 'even' : '')));
                $netCls = $caNet >= 0 ? 'ca-net-pos' : 'ca-net-neg';
 
                $body .= '<tr class="' . $rowCls . '">
                    <td class="rank">' . $medal . '</td>
                    <td><strong>' . htmlspecialchars($r->client_name) . '</strong></td>
                    <td style="color:#9CA3AF;font-size:8.5px;">' . $r->numclient . '</td>
                    <td class="right ca-net ' . $netCls . '">' . $this->fmt($caNet) . ' €</td>
                    <td class="right" style="color:#1D4ED8;">' . $this->fmt((float)$r->ca_brut) . ' €</td>
                    <td class="right" style="color:#DC2626;">' . $this->fmt((float)$r->ca_retour) . ' €</td>
                    <td class="right">' . $r->nb_bl . '</td>
                    <td>
                        <div style="font-size:9px;color:#6B7A99;margin-bottom:2px;">' . $part . '%</div>
                        <div class="bar-wrap"><div class="bar-fill" style="width:' . $pct . '%;"></div></div>
                    </td>
                </tr>';
                $rank++;
            }
 
            $totNetCls = $totalNet >= 0 ? '#6EE7B7' : '#FCA5A5';
            $body .= '<tr class="total">
                <td colspan="3">TOTAL</td>
                <td class="right" style="font-size:14px;color:' . $totNetCls . ';">' . $this->fmt($totalNet) . ' €</td>
                <td class="right">' . $this->fmt($totalBrut) . ' €</td>
                <td class="right">' . $this->fmt($totalRetour) . ' €</td>
                <td class="right">' . $totalBl . '</td>
                <td></td>
            </tr>';
            $body .= '</tbody></table>';
            break;
 
        // ════════════════════════════════════════════════
        // DÉTAILS BL
        // ════════════════════════════════════════════════
        case 'details':
            $totalTtc = $data['rows']->sum('total_ttc');
            $nbBl     = count($data['rows']);
 
            $body .= '
            <div class="callout-net">
                <div class="lbl">Total TTC — ' . $nbBl . ' bon(s) de livraison sur la période</div>
                <div class="val">' . $this->fmt($totalTtc) . ' €</div>
            </div>';
 
            $body .= '<div class="section-title">Détail des bons de livraison</div>';
            $body .= '<table class="data">
                <thead><tr>
                    <th style="width:14%;">N° BL</th>
                    <th style="width:12%;">Date</th>
                    <th style="width:30%;">Client</th>
                    <th style="width:18%;">Vendeur</th>
                    <th class="right" style="width:16%;">Total TTC (€)</th>
                    <th style="width:10%;">Statut</th>
                </tr></thead><tbody>';
 
            $i = 0;
            foreach ($data['rows'] as $bl) {
                $cls   = $i % 2 === 0 ? '' : 'even';
                $bcls  = $bl->status === 'Expédié' ? 'badge-green' : 'badge-blue';
                $body .= '<tr class="' . $cls . '">
                    <td><strong>' . $bl->numdoc . '</strong></td>
                    <td>' . Carbon::parse($bl->delivery_date)->format('d/m/Y') . '</td>
                    <td>' . htmlspecialchars(optional($bl->customer)->name ?? $bl->numclient) . '</td>
                    <td>' . htmlspecialchars($bl->vendeur ?? '—') . '</td>
                    <td class="right" style="font-weight:bold;">' . $this->fmt((float)$bl->total_ttc) . ' €</td>
                    <td><span class="badge ' . $bcls . '">' . $bl->status . '</span></td>
                </tr>';
                $i++;
            }
            $body .= '<tr class="total">
                <td colspan="4">TOTAL TTC</td>
                <td class="right highlight">' . $this->fmt($totalTtc) . ' €</td>
                <td></td>
            </tr>';
            $body .= '</tbody></table>';
            break;
    }
 
    return '<!DOCTYPE html>
    <html><head>
        <meta charset="UTF-8">
        <style>' . $css . '</style>
    </head><body>
 
    <div class="header">
        <div class="header-top">
            <div class="header-company">AZ NEGOCE — Rapport Analytique</div>
            <div class="header-title">' . $typeLabel . '</div>
            <div class="header-subtitle">Document généré automatiquement</div>
        </div>
        <div style="display:table;width:100%;">
            <div style="display:table-cell;vertical-align:middle;">
                <span class="period-badge">Periode : ' . $periodLabel . '</span>
            </div>
            <div style="display:table-cell;text-align:right;vertical-align:middle;">
                <span class="header-meta">Généré le ' . $generatedAt . '</span>
            </div>
        </div>
    </div>
 
    <div class="separator"></div>
 
    <div class="content">
        ' . $body . '
        <div class="footer">
            AZ NEGOCE &nbsp;·&nbsp; Rapport analytique &nbsp;·&nbsp;
            Généré le ' . $generatedAt . ' &nbsp;·&nbsp; Document confidentiel
        </div>
    </div>
 
    </body></html>';
}





    // ══════════════════════════════════════════════════════════
    // HELPER formatage nombre
    // ══════════════════════════════════════════════════════════
    private function fmt($val)
    {
        return number_format((float)$val, 2, ',', ' ');
    }
}