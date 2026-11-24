<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Services\PortalCartService;
use App\Services\PortalCatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortalCatalogController extends Controller
{
    private function ctx(Request $r)
    {
        $user = $r->attributes->get('portal_user');
        return [
            'pUser' => $user,
            'customer' => $user->customer,
            'cs' => $r->attributes->get('portal_customer_settings'),
            's' => $r->attributes->get('portal_settings'),
        ];
    }

    /** Temps passé côté serveur (visible dans l'onglet Réseau du navigateur : X-Server-Ms) pour distinguer serveur lent / réseau lent. */
    private function ms()
    {
        return defined('LARAVEL_START') ? (string) round((microtime(true) - LARAVEL_START) * 1000) : '';
    }

    private function service(array $c)
    {
        return (new PortalCatalogService($c['s']))->forUser($c['customer']->id, $c['pUser']->id);
    }

    private function ids($v)
    {
        $out = [];
        foreach ((array) $v as $x) { if (is_scalar($x) && $x !== '' && strlen((string) $x) <= 40) $out[] = (string) $x; }
        return array_slice(array_values(array_unique($out)), 0, 30);
    }

    public function index(Request $request)
    {
        $c = $this->ctx($request);
        $svc = $this->service($c);

        $filters = [
            'q'        => mb_substr(trim((string) $request->query('q', '')), 0, 120),
            'brand'    => $this->ids($request->query('brand', [])),
            'category' => $this->ids($request->query('category', [])),
            'sub'      => $this->ids($request->query('sub', [])),
            'stock'    => $request->boolean('stock'),
            'fav'      => $request->boolean('fav'),
            'sort'     => in_array($request->query('sort'), ['price_asc', 'price_desc', 'stock', 'ref'], true) ? $request->query('sort') : 'relevance',
        ];
        $all = $request->boolean('all');
        $browse = !$all && $filters['q'] === '' && !$filters['brand'] && !$filters['category'] && !$filters['sub'] && !$filters['stock'] && !$filters['fav'];

        $data = $c + ['filters' => $filters, 'browse' => $browse, 'options' => $svc->filterOptions()];

        if ($browse) {
            $favRows = $svc->rows(
                \App\Models\Item::query()->select('items.*', DB::raw('COALESCE((SELECT SUM(quantity) FROM stocks WHERE stocks.item_id = items.id),0) AS stock_qty'))
                    ->where('items.is_active', 1)->with(['brand', 'category', 'unit', 'discountGroup'])
                    ->whereIn('items.id', function ($s) use ($c) { $s->select('item_id')->from('portal_favorites')->where('customer_id', $c['customer']->id); })
                    ->orderBy('items.code')->limit(6)->get(),
                $c['customer'], $c['cs']);
            $data += ['dash' => (new \App\Services\PortalDashboardService())->build($c['customer'], $c['cs'])];
            $data += ['pager' => null, 'equiv' => [], 'facets' => ['brands' => [], 'categories' => [], 'subs' => []], 'approx' => false, 'favRows' => $favRows];
        } else {
            $res = $svc->search($c['customer'], $c['cs'], $filters, max(1, (int) $request->query('page', 1)));
            $res['pager']->appends($request->query());
            $data += $res + ['favRows' => []];
        }

        $cart = (new PortalCartService($c['s'], $c['pUser'], $c['customer'], $c['cs']))->summary();
        $favCount = DB::table('portal_favorites')->where('customer_id', $c['customer']->id)->count();
        $data += ['cart' => $cart, 'favCount' => $favCount];

        if ($request->ajax() || $request->boolean('partial')) {
            return response()->json([
                'browse'  => $browse,
                'results' => view('portal._results', $data)->render(),
                'facets'  => $browse ? '' : view('portal._facets', $data)->render(),
                'total'   => $browse ? null : $data['pager']->total(),
            ])->header('X-Server-Ms', $this->ms());
        }
        return view('portal.catalog', $data);
    }

    public function suggest(Request $request)
    {
        $c = $this->ctx($request);
        return response()->json($this->service($c)->suggest(mb_substr((string) $request->query('q', ''), 0, 120), $c['customer'], $c['cs']))->header('X-Server-Ms', $this->ms());
    }

    /** Fiche article (tiroir). */
    public function item(Request $request, $itemId)
    {
        $c = $this->ctx($request);
        $r = $this->service($c)->detail((int) $itemId, $c['customer'], $c['cs']);
        abort_unless($r, 404);
        $cart = (new PortalCartService($c['s'], $c['pUser'], $c['customer'], $c['cs']))->orderBlock();
        return view('portal._drawer_item', $c + ['r' => $r, 'canBuy' => !$cart]);
    }

    public function equivalents(Request $request, $itemId)
    {
        $c = $this->ctx($request);
        $cart = (new PortalCartService($c['s'], $c['pUser'], $c['customer'], $c['cs']))->orderBlock();
        return view('portal._equivalents', $c + ['canBuy' => !$cart, 'rows' => $this->service($c)->equivalentsOf((int) $itemId, $c['customer'], $c['cs'])]);
    }
}
