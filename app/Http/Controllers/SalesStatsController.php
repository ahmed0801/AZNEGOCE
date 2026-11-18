<?php

namespace App\Http\Controllers;

use App\Exports\SalesStatsExport;
use App\Models\Brand;
use App\Models\ItemCategory;
use App\Models\ItemSubcategory;
use App\Models\Supplier;
use App\Services\SalesStatsService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SalesStatsController extends Controller
{
    /** Page « Statistiques de vente » */
    public function index(Request $request)
    {
        @set_time_limit(120);

        $svc  = new SalesStatsService($request->all());
        $data = $svc->build();

        return view('sales.stats', [
            'data'          => $data,
            'f'             => $data['filters'],
            'brands'        => Brand::orderBy('name')->get(['id', 'name']),
            'categories'    => ItemCategory::orderBy('name')->get(['id', 'name']),
            'subcategories' => ItemSubcategory::orderBy('name')->get(['id', 'name', 'code', 'category_id']),
            'suppliers'     => Supplier::orderBy('name')->get(['code', 'name']),
            'vendeurs'      => SalesStatsService::vendeursList(),
        ]);
    }

    /** Export Excel multi-feuilles (mêmes filtres que la page) */
    public function export(Request $request)
    {
        @set_time_limit(300);
        @ini_set('memory_limit', '512M');

        $data = (new SalesStatsService($request->all()))->build();
        $name = 'statistiques_ventes_' . $data['period']['from'] . '_au_' . $data['period']['to'] . '.xlsx';

        return Excel::download(new SalesStatsExport($data), $name);
    }
}
