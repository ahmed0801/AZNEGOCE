<?php

namespace App\Http\Controllers;

use App\Exports\SalesStatsExport;
use App\Models\Brand;
use App\Models\ItemCategory;
use App\Models\ItemSubcategory;
use App\Models\Supplier;
use App\Services\SalesStatsService;
use App\Services\StatsFormat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class SalesStatsController extends Controller
{
    const TTL = 600;                       // 10 minutes
    const SECTIONS = ['dormants', 'clients', 'vendeurs'];

    protected function key($section, SalesStatsService $svc)
    {
        return 'salesstats:' . $section . ':' . md5(json_encode($svc->filters()));
    }

    /** Résultat mis en cache 10 min ; ?refresh=1 force le recalcul de toutes les sections de ces filtres */
    protected function cached($section, SalesStatsService $svc, $refresh)
    {
        $key = $this->key($section, $svc);
        if ($refresh) Cache::forget($key);
        return Cache::remember($key, self::TTL, function () use ($section, $svc) {
            return $section === 'core' ? $svc->core() : ($section === 'clients' ? $svc->clientsFull() : $svc->{$section}());
        });
    }

    protected function refreshAll(SalesStatsService $svc)
    {
        foreach (array_merge(['core'], self::SECTIONS) as $s) Cache::forget($this->key($s, $svc));
    }

    /** Page « Statistiques de vente » : charge uniquement le cœur ; le reste arrive en AJAX */
    public function index(Request $request)
    {
        @set_time_limit(120);

        $svc = new SalesStatsService($request->all());
        $debug = $request->boolean('debug');
        if ($request->boolean('refresh') || $debug) $this->refreshAll($svc);
        $data = $this->cached('core', $svc, false);
        $diag = $debug ? $svc->diagnostics() : null;

        return view('sales.stats', [
            'data'          => $data,
            'f'             => $data['filters'],
            'debug'         => $debug,
            'diag'          => $diag,
            'brands'        => Brand::orderBy('name')->get(['id', 'name']),
            'categories'    => ItemCategory::orderBy('name')->get(['id', 'name']),
            'subcategories' => ItemSubcategory::orderBy('name')->get(['id', 'name', 'code', 'category_id']),
            'suppliers'     => Supplier::orderBy('name')->get(['code', 'name']),
            'vendeurs'      => SalesStatsService::vendeursList(),
        ]);
    }

    /** Sections lourdes chargées à la demande : dormants, clients, vendeurs (JSON : html + indicateurs) */
    public function section(Request $request, $section)
    {
        if (!in_array($section, self::SECTIONS, true)) abort(404);
        @set_time_limit(120);

        $svc = new SalesStatsService($request->all());
        $d = $this->cached($section, $svc, false);
        $cl = null;
        if ($section === 'clients') { $cl = $d; $d = $cl['rows']; }

        $payload = [
            'html' => view('sales.stats_' . $section, ['d' => $d] + StatsFormat::closures())->render(),
        ];
        if ($section === 'dormants') {
            $payload['kpi'] = [
                'value' => number_format($d['value'], 0, ',', ' ') . ' €',
                'count' => number_format($d['count'], 0, ',', ' ') . ' articles sans vente',
            ];
            $payload['insights'] = $svc->lateInsights($d, null);
        } elseif ($section === 'clients') {
            $payload['insights'] = $svc->lateInsights(null, $d);
            $payload['kpi'] = ['clients' => number_format($cl['count'], 0, ',', ' '),
                'per_client' => $cl['count'] ? number_format($cl['ca'] / $cl['count'], 0, ',', ' ') . ' € / client' : '—'];
        }

        $payload['timings'] = $svc->timings();
        return response()->json($payload);
    }

    /** Export Excel multi-feuilles (mêmes filtres que la page, réutilise le cache) */
    public function export(Request $request)
    {
        @set_time_limit(300);
        @ini_set('memory_limit', '512M');

        $svc  = new SalesStatsService($request->all());
        $data = $this->cached('core', $svc, false);
        $data['dormants'] = $this->cached('dormants', $svc, false);
        $cf = $this->cached('clients', $svc, false);
        $data['clients']  = $cf['rows'];
        $data['kpis']['cur']['clients'] = $cf['count'];
        $data['vendeurs'] = $this->cached('vendeurs', $svc, false);
        $data['insights'] = array_merge($data['insights'], $svc->lateInsights($data['dormants'], $data['clients']));

        $name = 'statistiques_ventes_' . $data['period']['from'] . '_au_' . $data['period']['to'] . '.xlsx';
        return Excel::download(new SalesStatsExport($data), $name);
    }
}
