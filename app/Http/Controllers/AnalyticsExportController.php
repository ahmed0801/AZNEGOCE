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
    // ══════════════════════════════════════════════════════════
    public function export(Request $request)
    {
        $format = $request->input('format', 'excel');
        $type   = $request->input('type',   'vendeurs');

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
        $period = $request->input('period', 'thisYear');
        $customStart = $request->input('start_date');
        $customEnd   = $request->input('end_date');
        $year   = (int) $request->input('year', $now->year);

        if ($customStart && $customEnd) {
            $start = Carbon::parse($customStart)->startOfDay();
            $end   = Carbon::parse($customEnd)->endOfDay();
            $label = 'Du ' . $start->format('d/m/Y') . ' au ' . $end->format('d/m/Y');

        } elseif ($period === 'lastYear') {
            $start = Carbon::create($now->year - 1, 1, 1)->startOfDay();
            $end   = Carbon::create($now->year - 1, 12, 31)->endOfDay();
            $label = 'Année ' . ($now->year - 1);

        } elseif ($period === 'lastMonth') {
            $start = $now->clone()->subMonth()->startOfMonth();
            $end   = $now->clone()->subMonth()->endOfMonth();
            $label = $start->format('F Y');

        } elseif ($period === 'thisMonth') {
            $start = $now->clone()->startOfMonth();
            $end   = $now->clone()->endOfDay();
            $label = $now->format('F Y');

        } elseif ($period === 'today') {
            $start = $now->clone()->startOfDay();
            $end   = $now->clone()->endOfDay();
            $label = "Aujourd'hui " . $now->format('d/m/Y');

        } else {
            // thisYear (défaut)
            $start = Carbon::create($year, 1, 1)->startOfDay();
            $end   = $year === $now->year ? $now->clone()->endOfDay() : Carbon::create($year, 12, 31)->endOfDay();
            $label = 'Année ' . $year;
        }

        return array($start, $end, $label);
    }

    // ══════════════════════════════════════════════════════════
    // COLLECTER LES DONNÉES
    // ══════════════════════════════════════════════════════════
    private function collectData($type, $start, $end)
    {
        switch ($type) {
            case 'vendeurs':        return $this->getVendeursData($start, $end);
            case 'clients':         return $this->getClientsData($start, $end);
            case 'vendeurs_mois':   return $this->getVendeursMoisData($start, $end);
            case 'clients_mois':    return $this->getClientsMoisData($start, $end);
            case 'details':         return $this->getDetailsData($start, $end);
            default:                return $this->getRecapData($start, $end);
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

        // CA par mois (pour le tableau Jan→Déc)
        $parMois = DB::table('delivery_notes')
            ->whereIn('status', ['Expédié', 'en_cours'])
            ->whereBetween('delivery_date', [$start, $end])
            ->selectRaw('MONTH(delivery_date) as mois, SUM(total_ttc) as ca_brut, COUNT(id) as nb_bl')
            ->groupByRaw('MONTH(delivery_date)')
            ->orderByRaw('MONTH(delivery_date)')
            ->get()->keyBy('mois');

        $parMoisRetours = DB::table('sales_returns')
            ->whereBetween('return_date', [$start, $end])
            ->selectRaw('MONTH(return_date) as mois, SUM(total_ttc) as ca_retour')
            ->groupByRaw('MONTH(return_date)')
            ->orderByRaw('MONTH(return_date)')
            ->get()->keyBy('mois');

        return array(
            'caNet'         => $caNet,
            'caBrut'        => $caBrut,
            'caRetour'      => $caRetour,
            'nbBl'          => $nbBl,
            'panierMoyen'   => $nbBl > 0 ? round($caNet / $nbBl, 2) : 0,
            'parMois'       => $parMois,
            'parMoisRetours'=> $parMoisRetours,
        );
    }

    // ── VENDEURS ───────────────────────────────────────────────
    private function getVendeursData($start, $end)
    {
        $ventes = DB::table('delivery_notes')
            ->whereIn('status', ['Expédié', 'en_cours'])
            ->whereBetween('delivery_date', [$start, $end])
            ->whereNotNull('vendeur')->where('vendeur', '!=', '')
            ->groupBy('vendeur')
            ->selectRaw('vendeur, SUM(total_ttc) as ca_brut, COUNT(id) as nb_bl')
            ->get()->keyBy('vendeur');

        $retours = DB::table('sales_returns')
            ->whereBetween('return_date', [$start, $end])
            ->whereNotNull('vendeur')->where('vendeur', '!=', '')
            ->groupBy('vendeur')
            ->selectRaw('vendeur, SUM(total_ttc) as ca_retour')
            ->get()->keyBy('vendeur');

        $rows = collect();
        foreach ($ventes as $nom => $v) {
            $retour = isset($retours[$nom]) ? (float)$retours[$nom]->ca_retour : 0;
            $brut   = (float)$v->ca_brut;
            $rows->push((object) array(
                'vendeur'     => $nom,
                'ca_brut'     => $brut,
                'ca_retour'   => $retour,
                'ca_net'      => $brut - $retour,
                'nb_bl'       => (int)$v->nb_bl,
                'taux_retour' => $brut > 0 ? round(($retour / $brut) * 100, 1) : 0,
            ));
        }
        foreach ($retours as $nom => $r) {
            if (!$ventes->has($nom)) {
                $rows->push((object) array(
                    'vendeur'     => $nom,
                    'ca_brut'     => 0,
                    'ca_retour'   => (float)$r->ca_retour,
                    'ca_net'      => -(float)$r->ca_retour,
                    'nb_bl'       => 0,
                    'taux_retour' => 0,
                ));
            }
        }
        return array('rows' => $rows->sortByDesc('ca_net')->values());
    }

    // ── VENDEURS PAR MOIS (tableau Jan→Déc) ───────────────────
    private function getVendeursMoisData($start, $end)
    {
        // Un seul SELECT avec MONTH — pas de timeout
        $rows = DB::table('delivery_notes')
            ->whereIn('status', ['Expédié', 'en_cours'])
            ->whereBetween('delivery_date', [$start, $end])
            ->whereNotNull('vendeur')->where('vendeur', '!=', '')
            ->selectRaw('vendeur, MONTH(delivery_date) as mois, SUM(total_ttc) as ca_brut, COUNT(id) as nb_bl')
            ->groupByRaw('vendeur, MONTH(delivery_date)')
            ->get();

        $retourRows = DB::table('sales_returns')
            ->whereBetween('return_date', [$start, $end])
            ->whereNotNull('vendeur')->where('vendeur', '!=', '')
            ->selectRaw('vendeur, MONTH(return_date) as mois, SUM(total_ttc) as ca_retour')
            ->groupByRaw('vendeur, MONTH(return_date)')
            ->get();

        // Construire matrice [vendeur][mois]
        $matrix = array();
        foreach ($rows as $r) {
            if (!isset($matrix[$r->vendeur])) $matrix[$r->vendeur] = array();
            $matrix[$r->vendeur][$r->mois] = array(
                'ca_brut' => (float)$r->ca_brut,
                'nb_bl'   => (int)$r->nb_bl,
                'ca_retour' => 0,
            );
        }
        foreach ($retourRows as $r) {
            if (!isset($matrix[$r->vendeur])) $matrix[$r->vendeur] = array();
            if (!isset($matrix[$r->vendeur][$r->mois])) {
                $matrix[$r->vendeur][$r->mois] = array('ca_brut' => 0, 'nb_bl' => 0, 'ca_retour' => 0);
            }
            $matrix[$r->vendeur][$r->mois]['ca_retour'] = (float)$r->ca_retour;
        }

        // Calculer totaux par vendeur
        $vendeurTotaux = array();
        foreach ($matrix as $vendeur => $moisData) {
            $tot = 0;
            foreach ($moisData as $m => $d) {
                $tot += $d['ca_brut'] - $d['ca_retour'];
            }
            $vendeurTotaux[$vendeur] = $tot;
        }
        arsort($vendeurTotaux);

        return array('matrix' => $matrix, 'vendeurTotaux' => $vendeurTotaux);
    }

    // ── CLIENTS (corrigé — sans JOIN qui timeout) ──────────────
    private function getClientsData($start, $end)
    {
        // Étape 1 : CA par client (groupBy simple, pas de JOIN)
        $ventes = DB::table('delivery_notes')
            ->whereIn('status', ['Expédié', 'en_cours'])
            ->whereBetween('delivery_date', [$start, $end])
            ->whereNotNull('numclient')
            ->groupBy('numclient')
            ->selectRaw('numclient, SUM(total_ttc) as ca_brut, COUNT(id) as nb_bl')
            ->orderByRaw('SUM(total_ttc) DESC')
            ->limit(100) // top 100 pour éviter timeout
            ->get()->keyBy('numclient');

        // Étape 2 : Retours par client
        $retours = DB::table('sales_returns')
            ->whereBetween('return_date', [$start, $end])
            ->whereNotNull('customer_id')
            ->groupBy('customer_id')
            ->selectRaw('customer_id, SUM(total_ttc) as ca_retour')
            ->get()->keyBy('customer_id');

        // Étape 3 : Noms clients — requête séparée légère
        $codes   = $ventes->keys()->toArray();
        $clients = array();
        if (!empty($codes)) {
            $clientRows = DB::table('customers')
                ->whereIn('code', $codes)
                ->select('code', 'name')
                ->get();
            foreach ($clientRows as $c) {
                $clients[$c->code] = $c->name;
            }
        }

        // Étape 4 : Fusion en PHP
        $rows = collect();
        foreach ($ventes as $code => $v) {
            $retour = isset($retours[$code]) ? (float)$retours[$code]->ca_retour : 0;
            $brut   = (float)$v->ca_brut;
            $rows->push((object) array(
                'numclient'   => $code,
                'client_name' => isset($clients[$code]) ? $clients[$code] : 'Client #' . $code,
                'ca_brut'     => $brut,
                'ca_retour'   => $retour,
                'ca_net'      => $brut - $retour,
                'nb_bl'       => (int)$v->nb_bl,
            ));
        }

        return array('rows' => $rows->sortByDesc('ca_net')->values());
    }

    // ── CLIENTS PAR MOIS (tableau Jan→Déc) ────────────────────
    private function getClientsMoisData($start, $end)
    {
        // Top 20 clients sur la période (évite tableau géant)
        $topClients = DB::table('delivery_notes')
            ->whereIn('status', ['Expédié', 'en_cours'])
            ->whereBetween('delivery_date', [$start, $end])
            ->whereNotNull('numclient')
            ->groupBy('numclient')
            ->selectRaw('numclient, SUM(total_ttc) as ca_total')
            ->orderByRaw('SUM(total_ttc) DESC')
            ->limit(20)
            ->pluck('ca_total', 'numclient');

        $codes = $topClients->keys()->toArray();

        // Noms
        $clients = array();
        if (!empty($codes)) {
            foreach (DB::table('customers')->whereIn('code', $codes)->select('code','name')->get() as $c) {
                $clients[$c->code] = $c->name;
            }
        }

        // CA mensuel pour ces clients
        $rows = DB::table('delivery_notes')
            ->whereIn('status', ['Expédié', 'en_cours'])
            ->whereBetween('delivery_date', [$start, $end])
            ->whereIn('numclient', $codes)
            ->selectRaw('numclient, MONTH(delivery_date) as mois, SUM(total_ttc) as ca_brut')
            ->groupByRaw('numclient, MONTH(delivery_date)')
            ->get();

        $matrix = array();
        foreach ($rows as $r) {
            $nom = isset($clients[$r->numclient]) ? $clients[$r->numclient] : 'Client #' . $r->numclient;
            if (!isset($matrix[$r->numclient])) $matrix[$r->numclient] = array('nom' => $nom, 'mois' => array());
            $matrix[$r->numclient]['mois'][$r->mois] = (float)$r->ca_brut;
        }

        // Trier par total desc
        uasort($matrix, function($a, $b) {
            $totA = array_sum($a['mois']);
            $totB = array_sum($b['mois']);
            return $totB <=> $totA;
        });

        return array('matrix' => $matrix);
    }

    // ── DETAILS ────────────────────────────────────────────────
    private function getDetailsData($start, $end)
    {
        $bls = DeliveryNote::whereIn('status', ['Expédié', 'en_cours'])
            ->whereBetween('delivery_date', [$start, $end])
            ->with('customer')
            ->orderBy('delivery_date', 'desc')
            ->limit(500)
            ->get(['id', 'numdoc', 'delivery_date', 'numclient', 'vendeur', 'total_ttc', 'status']);

        return array('rows' => $bls);
    }

    // ══════════════════════════════════════════════════════════
    // EXPORT EXCEL
    // ══════════════════════════════════════════════════════════
    private function exportExcel($type, $data, $start, $end, $periodLabel)
    {
        $filename = 'analytics_' . $type . '_' . $start->format('Ymd') . '_' . $end->format('Ymd') . '.csv';
        $rows = array();
        $moisNoms = array(1=>'Janvier',2=>'Février',3=>'Mars',4=>'Avril',5=>'Mai',6=>'Juin',
                          7=>'Juillet',8=>'Août',9=>'Septembre',10=>'Octobre',11=>'Novembre',12=>'Décembre');

        $rows[] = array('RAPPORT ANALYTIQUE AZ NEGOCE');
        $rows[] = array('Période : ' . $periodLabel);
        $rows[] = array('Généré le : ' . now()->format('d/m/Y H:i'));
        $rows[] = array('Type : ' . strtoupper($type));
        $rows[] = array();

        switch ($type) {

            case 'recap':
                $rows[] = array('RÉSUMÉ');
                $rows[] = array('CA Brut TTC',  $this->fmt($data['caBrut'])   . ' €');
                $rows[] = array('Retours TTC',   $this->fmt($data['caRetour']) . ' €');
                $rows[] = array('CA Net TTC',    $this->fmt($data['caNet'])    . ' €');
                $rows[] = array('Nb BL',         $data['nbBl']);
                $rows[] = array('Panier moyen',  $this->fmt($data['panierMoyen']) . ' €');
                $rows[] = array();
                // Tableau mensuel
                $header = array('');
                for ($m = 1; $m <= 12; $m++) $header[] = $moisNoms[$m];
                $header[] = 'TOTAL';
                $rows[] = $header;

                $rowBrut   = array('CA Brut (€)');
                $rowRetour = array('Retours (€)');
                $rowNet    = array('CA Net (€)');
                $totBrut = $totRet = $totNet = 0;
                for ($m = 1; $m <= 12; $m++) {
                    $b = isset($data['parMois'][$m])       ? (float)$data['parMois'][$m]->ca_brut     : 0;
                    $r = isset($data['parMoisRetours'][$m]) ? (float)$data['parMoisRetours'][$m]->ca_retour : 0;
                    $n = $b - $r;
                    $rowBrut[]   = $this->fmt($b);
                    $rowRetour[] = $this->fmt($r);
                    $rowNet[]    = $this->fmt($n);
                    $totBrut += $b; $totRet += $r; $totNet += $n;
                }
                $rowBrut[]   = $this->fmt($totBrut);
                $rowRetour[] = $this->fmt($totRet);
                $rowNet[]    = $this->fmt($totNet);
                $rows[] = $rowBrut;
                $rows[] = $rowRetour;
                $rows[] = $rowNet;
                break;

            case 'vendeurs':
                $rows[] = array('Rang','Vendeur','CA Brut (€)','Retours (€)','CA Net (€)','Nb BL','Taux retour (%)');
                $rank = 1;
                foreach ($data['rows'] as $r) {
                    $rows[] = array($rank++, $r->vendeur,
                        $this->fmt($r->ca_brut), $this->fmt($r->ca_retour),
                        $this->fmt($r->ca_net), $r->nb_bl,
                        number_format($r->taux_retour, 1, ',', ' ') . '%');
                }
                $rows[] = array();
                $rows[] = array('TOTAL','',
                    $this->fmt($data['rows']->sum('ca_brut')),
                    $this->fmt($data['rows']->sum('ca_retour')),
                    $this->fmt($data['rows']->sum('ca_net')),
                    $data['rows']->sum('nb_bl'),'');
                break;

            case 'vendeurs_mois':
                // En-tête avec les mois
                $header = array('Vendeur');
                for ($m = 1; $m <= 12; $m++) $header[] = $moisNoms[$m];
                $header[] = 'TOTAL CA Net';
                $rows[] = $header;
                foreach ($data['vendeurTotaux'] as $vendeur => $totNet) {
                    $row = array($vendeur);
                    $moisData = isset($data['matrix'][$vendeur]) ? $data['matrix'][$vendeur] : array();
                    for ($m = 1; $m <= 12; $m++) {
                        $b = isset($moisData[$m]) ? $moisData[$m]['ca_brut']   : 0;
                        $r = isset($moisData[$m]) ? $moisData[$m]['ca_retour'] : 0;
                        $row[] = $this->fmt($b - $r);
                    }
                    $row[] = $this->fmt($totNet);
                    $rows[] = $row;
                }
                break;

            case 'clients':
                $rows[] = array('Rang','Client','Code','CA Brut (€)','Retours (€)','CA Net (€)','Nb BL');
                $rank = 1;
                foreach ($data['rows'] as $r) {
                    $rows[] = array($rank++, $r->client_name, $r->numclient,
                        $this->fmt($r->ca_brut), $this->fmt($r->ca_retour),
                        $this->fmt($r->ca_net), $r->nb_bl);
                }
                $rows[] = array();
                $rows[] = array('TOTAL','','',
                    $this->fmt($data['rows']->sum('ca_brut')),
                    $this->fmt($data['rows']->sum('ca_retour')),
                    $this->fmt($data['rows']->sum('ca_net')),
                    $data['rows']->sum('nb_bl'));
                break;

            case 'clients_mois':
                $header = array('Client');
                for ($m = 1; $m <= 12; $m++) $header[] = $moisNoms[$m];
                $header[] = 'TOTAL';
                $rows[] = $header;
                foreach ($data['matrix'] as $code => $client) {
                    $row = array($client['nom']);
                    $tot = 0;
                    for ($m = 1; $m <= 12; $m++) {
                        $v = isset($client['mois'][$m]) ? $client['mois'][$m] : 0;
                        $row[] = $v > 0 ? $this->fmt($v) : '';
                        $tot  += $v;
                    }
                    $row[] = $this->fmt($tot);
                    $rows[] = $row;
                }
                break;

            case 'details':
                $rows[] = array('N° BL','Date','Client','Vendeur','Total TTC (€)','Statut');
                foreach ($data['rows'] as $bl) {
                    $rows[] = array($bl->numdoc,
                        Carbon::parse($bl->delivery_date)->format('d/m/Y'),
                        optional($bl->customer)->name ?? $bl->numclient,
                        $bl->vendeur ?? '-',
                        $this->fmt((float)$bl->total_ttc), $bl->status);
                }
                $rows[] = array();
                $rows[] = array('TOTAL TTC','','','',$this->fmt($data['rows']->sum('total_ttc')),'');
                break;
        }

        $output = "\xEF\xBB\xBF";
        foreach ($rows as $row) {
            $cells = array_map(function($cell) {
                return '"' . str_replace('"', '""', (string)$cell) . '"';
            }, $row);
            $output .= implode(';', $cells) . "\r\n";
        }

        return response($output, 200, array(
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'no-cache',
        ));
    }

    // ══════════════════════════════════════════════════════════
    // EXPORT PDF
    // ══════════════════════════════════════════════════════════
    private function exportPdf($type, $data, $start, $end, $periodLabel)
    {
        $typeLabels = array(
            'recap'         => 'Récapitulatif mensuel',
            'vendeurs'      => 'Classement Vendeurs',
            'vendeurs_mois' => 'Vendeurs par mois',
            'clients'       => 'Top Clients',
            'clients_mois'  => 'Clients par mois',
            'details'       => 'Détail des BL',
        );
        $filename  = 'analytics_' . $type . '_' . $start->format('Ymd') . '_' . $end->format('Ymd') . '.pdf';
        $typeLabel = isset($typeLabels[$type]) ? $typeLabels[$type] : $type;
        $genAt     = now()->format('d/m/Y à H:i');

        $html = $this->buildPdfHtml($type, $data, $periodLabel, $typeLabel, $genAt);

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', in_array($type, ['vendeurs_mois','clients_mois','recap']) ? 'landscape' : 'portrait')
            ->setOptions(array(
                'defaultFont'          => 'DejaVu Sans',
                'isRemoteEnabled'      => false,
                'isHtml5ParserEnabled' => true,
                'dpi'                  => 120,
            ));

        return $pdf->download($filename);
    }

    // ══════════════════════════════════════════════════════════
    // BUILD PDF HTML
    // ══════════════════════════════════════════════════════════
    private function buildPdfHtml($type, $data, $periodLabel, $typeLabel, $genAt)
    {
        $moisNoms = array(1=>'Jan',2=>'Fév',3=>'Mar',4=>'Avr',5=>'Mai',6=>'Jun',
                          7=>'Jul',8=>'Aoû',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Déc');

        $css = '
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size:10px; color:#1a2b4a; background:white; }
        .header { background-color:#1E2D4A; padding:16px 20px 12px; }
        .header h1 { font-size:17px; font-weight:bold; color:white; margin-bottom:2px; }
        .header .sub { font-size:9px; color:#7FA0C8; }
        .header .period { font-size:10px; font-weight:bold; color:#60B0FF; margin-top:6px; }
        .separator { height:4px; background-color:#3B82F6; margin-bottom:16px; }
        .content { padding:0 18px 18px; }
        .callout { background-color:#F0FDF4; border-left:5px solid #10B981; padding:10px 14px; margin-bottom:14px; }
        .callout .val { font-size:26px; font-weight:bold; color:#065F46; }
        .callout .lbl { font-size:9px; color:#6B7A99; text-transform:uppercase; letter-spacing:.05em; }
        .callout .det { font-size:8.5px; color:#9CA3AF; margin-top:2px; }
        .section-title { font-size:11px; font-weight:bold; color:white; background-color:#1E2D4A; padding:6px 10px; margin-bottom:0; }
        table.data { width:100%; border-collapse:collapse; margin-bottom:14px; }
        table.data thead tr { background-color:#2D4A8A; }
        table.data th { color:white; padding:7px 8px; text-align:left; font-size:8.5px; text-transform:uppercase; letter-spacing:.04em; }
        table.data th.r { text-align:right; }
        table.data th.net { background-color:#1a3a2a; }
        table.data td { padding:7px 8px; font-size:9.5px; border-bottom:1px solid #E8EFF8; }
        table.data td.r { text-align:right; }
        table.data td.net { font-weight:bold; font-size:11px; }
        table.data td.pos { color:#065F46; }
        table.data td.neg { color:#DC2626; }
        table.data td.blue { color:#1D4ED8; }
        table.data td.red  { color:#DC2626; }
        table.data tr.even td { background-color:#F8FAFF; }
        table.data tr.top1 td { background-color:#FFFBEB; }
        table.data tr.total td { background-color:#1E2D4A; color:white; font-weight:bold; font-size:10.5px; padding:8px; border-bottom:none; }
        table.data tr.total td.hl { color:#6EE7B7; font-size:13px; }
        .bar-wrap { background-color:#E2E8F0; border-radius:3px; height:5px; min-width:40px; }
        .bar-fill  { background-color:#3B82F6; border-radius:3px; height:5px; }
        .badge { display:inline-block; padding:1px 5px; border-radius:3px; font-size:7.5px; font-weight:bold; }
        .bg  { background-color:#D1FAE5; color:#065F46; }
        .br  { background-color:#FEE2E2; color:#DC2626; }
        .bb  { background-color:#DBEAFE; color:#1D4ED8; }
        .tg  { color:#065F46; font-weight:bold; }
        .tw  { color:#D97706; font-weight:bold; }
        .tb  { color:#DC2626; font-weight:bold; }
        .footer { text-align:center; color:#9CA3AF; font-size:8px; margin-top:16px; padding-top:8px; border-top:1px solid #E2E8F0; }
        /* Tableau mensuel */
        table.mois { width:100%; border-collapse:collapse; margin-bottom:14px; font-size:8.5px; }
        table.mois th { background-color:#2D4A8A; color:white; padding:5px 4px; text-align:center; font-size:7.5px; }
        table.mois th.left { text-align:left; padding-left:8px; min-width:80px; }
        table.mois td { padding:5px 4px; text-align:right; border-bottom:1px solid #E8EFF8; font-size:8px; }
        table.mois td.name { text-align:left; padding-left:8px; font-weight:bold; font-size:9px; }
        table.mois tr.even td { background-color:#F8FAFF; }
        table.mois tr.total-row td { background-color:#1E2D4A; color:white; font-weight:bold; }
        table.mois td.top { font-weight:bold; color:#065F46; }
        table.mois td.zero { color:#D1D5DB; }
        ';

        $body = '';

        switch ($type) {

            // ── RECAP mensuel ──────────────────────────────────
            case 'recap':
                $body .= '
                <div class="callout">
                    <div class="lbl">CA Net TTC — résultat de la période</div>
                    <div class="val">' . $this->fmt($data['caNet']) . ' €</div>
                    <div class="det">CA Brut ' . $this->fmt($data['caBrut']) . ' € — Retours - ' . $this->fmt($data['caRetour']) . ' € · ' . $data['nbBl'] . ' BL · Panier moyen ' . $this->fmt($data['panierMoyen']) . ' €</div>
                </div>
                <div class="section-title">CA Net par mois (Brut − Retours)</div>
                <table class="mois">
                    <thead><tr>
                        <th class="left">Indicateur</th>';
                for ($m = 1; $m <= 12; $m++) $body .= '<th>' . $moisNoms[$m] . '</th>';
                $body .= '<th>TOTAL</th></tr></thead><tbody>';

                // Ligne CA Brut
                $body .= '<tr><td class="name">CA Brut (€)</td>';
                $totB = 0;
                for ($m = 1; $m <= 12; $m++) {
                    $v = isset($data['parMois'][$m]) ? (float)$data['parMois'][$m]->ca_brut : 0;
                    $body .= '<td' . ($v > 0 ? ' class="blue"' : ' class="zero"') . '>' . ($v > 0 ? $this->fmt($v) : '—') . '</td>';
                    $totB += $v;
                }
                $body .= '<td style="font-weight:bold;">' . $this->fmt($totB) . '</td></tr>';

                // Ligne Retours
                $body .= '<tr class="even"><td class="name">Retours (€)</td>';
                $totR = 0;
                for ($m = 1; $m <= 12; $m++) {
                    $v = isset($data['parMoisRetours'][$m]) ? (float)$data['parMoisRetours'][$m]->ca_retour : 0;
                    $body .= '<td' . ($v > 0 ? ' class="red"' : ' class="zero"') . '>' . ($v > 0 ? '- ' . $this->fmt($v) : '—') . '</td>';
                    $totR += $v;
                }
                $body .= '<td style="font-weight:bold;color:#DC2626;">- ' . $this->fmt($totR) . '</td></tr>';

                // Ligne CA Net
                $body .= '<tr class="total-row"><td class="name">CA NET (€)</td>';
                $totN = 0;
                for ($m = 1; $m <= 12; $m++) {
                    $b = isset($data['parMois'][$m])        ? (float)$data['parMois'][$m]->ca_brut          : 0;
                    $r = isset($data['parMoisRetours'][$m]) ? (float)$data['parMoisRetours'][$m]->ca_retour  : 0;
                    $n = $b - $r;
                    $body .= '<td style="' . ($n > 0 ? 'color:#6EE7B7;font-weight:bold;' : ($n < 0 ? 'color:#FCA5A5;' : 'color:#6B7A99;')) . '">'
                           . ($n != 0 ? $this->fmt($n) : '—') . '</td>';
                    $totN += $n;
                }
                $body .= '<td style="font-size:12px;color:#6EE7B7;font-weight:bold;">' . $this->fmt($totN) . '</td></tr>';

                $body .= '</tbody></table>';
                break;

            // ── VENDEURS simple ────────────────────────────────
            case 'vendeurs':
                $totalNet  = $data['rows']->sum('ca_net');
                $totalBrut = $data['rows']->sum('ca_brut');
                $totalRet  = $data['rows']->sum('ca_retour');
                $totalBl   = $data['rows']->sum('nb_bl');
                $maxCa     = $data['rows']->max('ca_net') ?: 1;

                $body .= '
                <div class="callout">
                    <div class="lbl">CA Net TTC total — tous vendeurs</div>
                    <div class="val">' . $this->fmt($totalNet) . ' €</div>
                    <div class="det">' . count($data['rows']) . ' vendeur(s) · ' . $totalBl . ' BL · CA Brut ' . $this->fmt($totalBrut) . ' € · Retours - ' . $this->fmt($totalRet) . ' €</div>
                </div>
                <div class="section-title">Classement vendeurs par CA Net</div>
                <table class="data">
                    <thead><tr>
                        <th style="width:4%;">#</th>
                        <th style="width:22%;">Vendeur</th>
                        <th class="r net" style="width:16%;">CA NET ★</th>
                        <th class="r" style="width:14%;">CA Brut</th>
                        <th class="r" style="width:12%;">Retours</th>
                        <th class="r" style="width:7%;">BL</th>
                        <th class="r" style="width:9%;">Taux ret.</th>
                        <th style="width:16%;">Part</th>
                    </tr></thead><tbody>';

                $rank = 1;
                foreach ($data['rows'] as $r) {
                    $caNet  = (float)$r->ca_net;
                    $taux   = (float)$r->taux_retour;
                    $pct    = $maxCa > 0 ? round(max(0, $caNet) / $maxCa * 100) : 0;
                    $part   = $totalNet > 0 ? round($caNet / $totalNet * 100, 1) : 0;
                    $medals = array(1=>'🥇',2=>'🥈',3=>'🥉');
                    $medal  = isset($medals[$rank]) ? $medals[$rank] : $rank;
                    $cls    = $rank === 1 ? 'top1' : ($rank % 2 === 0 ? 'even' : '');
                    $tCls   = $taux <= 5 ? 'tg' : ($taux <= 15 ? 'tw' : 'tb');
                    $nCls   = $caNet >= 0 ? 'pos' : 'neg';
                    $body .= '<tr class="' . $cls . '">
                        <td style="text-align:center;">' . $medal . '</td>
                        <td><strong>' . htmlspecialchars($r->vendeur) . '</strong></td>
                        <td class="r net ' . $nCls . '">' . $this->fmt($caNet) . ' €</td>
                        <td class="r blue">' . $this->fmt((float)$r->ca_brut) . ' €</td>
                        <td class="r red">' . $this->fmt((float)$r->ca_retour) . ' €</td>
                        <td class="r">' . $r->nb_bl . '</td>
                        <td class="r"><span class="' . $tCls . '">' . number_format($taux,1,',','') . '%</span></td>
                        <td>
                            <span style="font-size:8px;color:#6B7A99;">' . $part . '%</span>
                            <div class="bar-wrap"><div class="bar-fill" style="width:' . $pct . '%;"></div></div>
                        </td>
                    </tr>';
                    $rank++;
                }
                $nCls = $totalNet >= 0 ? '#6EE7B7' : '#FCA5A5';
                $body .= '<tr class="total">
                    <td colspan="2">TOTAL</td>
                    <td class="r hl" style="color:' . $nCls . ';">' . $this->fmt($totalNet) . ' €</td>
                    <td class="r">' . $this->fmt($totalBrut) . ' €</td>
                    <td class="r">' . $this->fmt($totalRet) . ' €</td>
                    <td class="r">' . $totalBl . '</td>
                    <td colspan="2"></td>
                </tr></tbody></table>';
                break;

            // ── VENDEURS PAR MOIS — tableau Jan→Déc ───────────
            case 'vendeurs_mois':
                $body .= '<div class="section-title">CA Net par vendeur et par mois</div>';
                $body .= '<table class="mois"><thead><tr><th class="left">Vendeur</th>';
                for ($m = 1; $m <= 12; $m++) $body .= '<th>' . $moisNoms[$m] . '</th>';
                $body .= '<th>TOTAL</th></tr></thead><tbody>';

                // Ligne totaux mois
                $totMois = array();
                for ($m = 1; $m <= 12; $m++) $totMois[$m] = 0;

                $i = 0;
                foreach ($data['vendeurTotaux'] as $vendeur => $totNet) {
                    $moisData = isset($data['matrix'][$vendeur]) ? $data['matrix'][$vendeur] : array();
                    $cls = $i % 2 === 0 ? '' : 'even';
                    $body .= '<tr class="' . $cls . '"><td class="name">' . htmlspecialchars($vendeur) . '</td>';
                    for ($m = 1; $m <= 12; $m++) {
                        $b = isset($moisData[$m]) ? $moisData[$m]['ca_brut']   : 0;
                        $r = isset($moisData[$m]) ? $moisData[$m]['ca_retour'] : 0;
                        $n = $b - $r;
                        $totMois[$m] += $n;
                        if ($n > 0) {
                            $body .= '<td class="top">' . $this->fmt($n) . '</td>';
                        } elseif ($n < 0) {
                            $body .= '<td style="color:#DC2626;">' . $this->fmt($n) . '</td>';
                        } else {
                            $body .= '<td class="zero">—</td>';
                        }
                    }
                    $body .= '<td style="font-weight:bold;color:' . ($totNet >= 0 ? '#065F46' : '#DC2626') . ';">' . $this->fmt($totNet) . '</td></tr>';
                    $i++;
                }

                // Ligne total
                $body .= '<tr class="total-row"><td class="name">TOTAL</td>';
                $grandTotal = 0;
                for ($m = 1; $m <= 12; $m++) {
                    $v = $totMois[$m];
                    $grandTotal += $v;
                    $body .= '<td style="color:' . ($v > 0 ? '#6EE7B7' : ($v < 0 ? '#FCA5A5' : '#6B7A99')) . ';font-weight:bold;">'
                           . ($v != 0 ? $this->fmt($v) : '—') . '</td>';
                }
                $body .= '<td style="color:#6EE7B7;font-size:11px;font-weight:bold;">' . $this->fmt($grandTotal) . '</td></tr>';
                $body .= '</tbody></table>';
                break;

            // ── CLIENTS simple ─────────────────────────────────
            case 'clients':
                $totalNet  = $data['rows']->sum('ca_net');
                $totalBrut = $data['rows']->sum('ca_brut');
                $totalRet  = $data['rows']->sum('ca_retour');
                $totalBl   = $data['rows']->sum('nb_bl');
                $maxCa     = $data['rows']->max('ca_net') ?: 1;

                $body .= '
                <div class="callout">
                    <div class="lbl">CA Net TTC — Top ' . count($data['rows']) . ' clients</div>
                    <div class="val">' . $this->fmt($totalNet) . ' €</div>
                    <div class="det">' . $totalBl . ' BL · CA Brut ' . $this->fmt($totalBrut) . ' € · Retours - ' . $this->fmt($totalRet) . ' €</div>
                </div>
                <div class="section-title">Classement clients par CA Net</div>
                <table class="data">
                    <thead><tr>
                        <th style="width:4%;">#</th>
                        <th style="width:28%;">Client</th>
                        <th class="r net" style="width:16%;">CA NET ★</th>
                        <th class="r" style="width:14%;">CA Brut</th>
                        <th class="r" style="width:12%;">Retours</th>
                        <th class="r" style="width:6%;">BL</th>
                        <th style="width:20%;">Part du CA</th>
                    </tr></thead><tbody>';

                $rank = 1;
                foreach ($data['rows'] as $r) {
                    $caNet = (float)$r->ca_net;
                    $pct   = $maxCa > 0 ? round(max(0, $caNet) / $maxCa * 100) : 0;
                    $part  = $totalNet > 0 ? round($caNet / $totalNet * 100, 1) : 0;
                    $medals= array(1=>'🥇',2=>'🥈',3=>'🥉');
                    $medal = isset($medals[$rank]) ? $medals[$rank] : $rank;
                    $cls   = $rank === 1 ? 'top1' : ($rank % 2 === 0 ? 'even' : '');
                    $nCls  = $caNet >= 0 ? 'pos' : 'neg';
                    $body .= '<tr class="' . $cls . '">
                        <td style="text-align:center;">' . $medal . '</td>
                        <td><strong>' . htmlspecialchars($r->client_name) . '</strong>
                            <span style="font-size:7.5px;color:#9CA3AF;display:block;">' . $r->numclient . '</span></td>
                        <td class="r net ' . $nCls . '">' . $this->fmt($caNet) . ' €</td>
                        <td class="r blue">' . $this->fmt((float)$r->ca_brut) . ' €</td>
                        <td class="r red">' . $this->fmt((float)$r->ca_retour) . ' €</td>
                        <td class="r">' . $r->nb_bl . '</td>
                        <td>
                            <span style="font-size:8px;color:#6B7A99;">' . $part . '%</span>
                            <div class="bar-wrap"><div class="bar-fill" style="width:' . $pct . '%;"></div></div>
                        </td>
                    </tr>';
                    $rank++;
                }
                $nCls2 = $totalNet >= 0 ? '#6EE7B7' : '#FCA5A5';
                $body .= '<tr class="total">
                    <td colspan="2">TOTAL (Top ' . count($data['rows']) . ')</td>
                    <td class="r hl" style="color:' . $nCls2 . ';">' . $this->fmt($totalNet) . ' €</td>
                    <td class="r">' . $this->fmt($totalBrut) . ' €</td>
                    <td class="r">' . $this->fmt($totalRet) . ' €</td>
                    <td class="r">' . $totalBl . '</td>
                    <td></td>
                </tr></tbody></table>';
                break;

            // ── CLIENTS PAR MOIS ───────────────────────────────
            case 'clients_mois':
                $body .= '<div class="section-title">CA par client et par mois (Top 20)</div>';
                $body .= '<table class="mois"><thead><tr><th class="left">Client</th>';
                for ($m = 1; $m <= 12; $m++) $body .= '<th>' . $moisNoms[$m] . '</th>';
                $body .= '<th>TOTAL</th></tr></thead><tbody>';
                $i = 0;
                $totMois2 = array();
                for ($m = 1; $m <= 12; $m++) $totMois2[$m] = 0;
                foreach ($data['matrix'] as $code => $client) {
                    $cls = $i % 2 === 0 ? '' : 'even';
                    $tot = 0;
                    $body .= '<tr class="' . $cls . '"><td class="name">' . htmlspecialchars($client['nom']) . '</td>';
                    for ($m = 1; $m <= 12; $m++) {
                        $v = isset($client['mois'][$m]) ? $client['mois'][$m] : 0;
                        $totMois2[$m] += $v;
                        $tot += $v;
                        $body .= $v > 0
                            ? '<td class="top">' . $this->fmt($v) . '</td>'
                            : '<td class="zero">—</td>';
                    }
                    $body .= '<td style="font-weight:bold;color:#065F46;">' . $this->fmt($tot) . '</td></tr>';
                    $i++;
                }
                $body .= '<tr class="total-row"><td class="name">TOTAL</td>';
                $grand = 0;
                for ($m = 1; $m <= 12; $m++) {
                    $v = $totMois2[$m]; $grand += $v;
                    $body .= '<td style="color:' . ($v > 0 ? '#6EE7B7' : '#6B7A99') . ';font-weight:bold;">'
                           . ($v > 0 ? $this->fmt($v) : '—') . '</td>';
                }
                $body .= '<td style="color:#6EE7B7;font-weight:bold;">' . $this->fmt($grand) . '</td></tr>';
                $body .= '</tbody></table>';
                break;

            // ── DETAILS ────────────────────────────────────────
            case 'details':
                $totalTtc = $data['rows']->sum('total_ttc');
                $body .= '
                <div class="callout">
                    <div class="lbl">Total TTC — ' . count($data['rows']) . ' BL</div>
                    <div class="val">' . $this->fmt($totalTtc) . ' €</div>
                </div>
                <div class="section-title">Détail des bons de livraison</div>
                <table class="data">
                    <thead><tr>
                        <th style="width:13%;">N° BL</th>
                        <th style="width:11%;">Date</th>
                        <th style="width:32%;">Client</th>
                        <th style="width:18%;">Vendeur</th>
                        <th class="r net" style="width:15%;">Total TTC</th>
                        <th style="width:11%;">Statut</th>
                    </tr></thead><tbody>';
                $i = 0;
                foreach ($data['rows'] as $bl) {
                    $cls  = $i % 2 === 0 ? '' : 'even';
                    $bcls = $bl->status === 'Expédié' ? 'bg' : 'bb';
                    $body .= '<tr class="' . $cls . '">
                        <td><strong>' . $bl->numdoc . '</strong></td>
                        <td>' . Carbon::parse($bl->delivery_date)->format('d/m/Y') . '</td>
                        <td>' . htmlspecialchars(optional($bl->customer)->name ?? $bl->numclient) . '</td>
                        <td>' . htmlspecialchars($bl->vendeur ?? '—') . '</td>
                        <td class="r net pos">' . $this->fmt((float)$bl->total_ttc) . ' €</td>
                        <td><span class="badge ' . $bcls . '">' . $bl->status . '</span></td>
                    </tr>';
                    $i++;
                }
                $body .= '<tr class="total">
                    <td colspan="4">TOTAL TTC</td>
                    <td class="r hl">' . $this->fmt($totalTtc) . ' €</td>
                    <td></td>
                </tr></tbody></table>';
                break;
        }

        return '<!DOCTYPE html><html><head><meta charset="UTF-8">
        <style>' . $css . '</style></head><body>
        <div class="header">
            <h1>Rapport Analytics — ' . $typeLabel . '</h1>
            <div class="sub">AZ NEGOCE · Généré le ' . $genAt . '</div>
            <div class="period">Période : ' . $periodLabel . '</div>
        </div>
        <div class="separator"></div>
        <div class="content">' . $body . '
            <div class="footer">AZ NEGOCE · Confidentiel · ' . $genAt . '</div>
        </div></body></html>';
    }

    private function fmt($val)
    {
        return number_format((float)$val, 2, ',', ' ');
    }
}