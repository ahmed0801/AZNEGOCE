<?php

namespace App\Http\Controllers;

use App\Models\PurchaseProject;
use App\Models\PurchaseProjectLine;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\Souche;
use App\Imports\PurchaseProjectImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;



class PurchaseProjectController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with('tvaGroup')->get();
        $tvaRates = $suppliers->mapWithKeys(fn($s) => [$s->id => $s->tvaGroup->rate ?? 0])->toJson();
        return view('purchaseprojects.create', compact('suppliers', 'tvaRates'));
    }





     public function edit($projectId)
    {
        $project = PurchaseProject::with('lines.item', 'supplier.tvaGroup')->findOrFail($projectId);
        
        if ($project->status !== 'brouillon') {
            return redirect()->route('purchaseprojects.list')
                ->with('error', 'Seuls les projets en brouillon peuvent être modifiés.');
        }

        $suppliers = Supplier::with('tvaGroup')->get();
        $tvaRates = $suppliers->mapWithKeys(fn($s) => [$s->id => $s->tvaGroup->rate ?? 0])->toJson();
        
        // Prepare lines for the view, mimicking the import session structure
        $importedLines = $project->lines->map(function ($line) {
            return [
                'article_code' => $line->article_code,
                'ordered_quantity' => $line->ordered_quantity,
                'unit_price_ht' => $line->unit_price_ht,
                'remise' => $line->remise,
            ];
        })->toArray();
        
        Session::put('imported_lines', $importedLines);
        Session::put('imported_supplier_id', $project->supplier_id);

        return view('purchaseprojects.edit', compact('project', 'suppliers', 'tvaRates'));
    }





    

    public function update(Request $request, $projectId)
    {
        $project = PurchaseProject::findOrFail($projectId);
        
        if ($project->status !== 'brouillon') {
            return redirect()->route('purchaseprojects.list')
                ->with('error', 'Seuls les projets en brouillon peuvent être modifiés.');
        }

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'lines' => 'required|array',
            'lines.*.article_code' => 'required|exists:items,code',
            'lines.*.ordered_quantity' => 'required|integer|min:1',
            'lines.*.unit_price_ht' => 'required|numeric|min:0',
            'lines.*.remise' => 'nullable|numeric|min:0|max:100',
        ]);

        // Warn if supplier changed
        if ($request->supplier_id != $project->supplier_id) {
            Session::flash('warning', 'Vous avez changé de fournisseur. Les lignes peuvent ne pas correspondre au nouveau fournisseur.');
        }

        $supplier = Supplier::with('tvaGroup')->findOrFail($request->supplier_id);
        $tvaRate = $supplier->tvaGroup->rate ?? 0;
        $status = $request->input('action') === 'validate' ? 'validée' : ($request->input('action') === 'convert' ? 'converted' : 'brouillon');

        DB::transaction(function () use ($request, $project, $tvaRate, $status) {
            // Update project
            $project->update([
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'notes' => $request->notes,
                'status' => $status,
                'total_ht' => 0,
                'total_ttc' => 0,
                'tva_rate' => $tvaRate,
            ]);

            // Delete existing lines
            $project->lines()->delete();

            // Create new lines
            $total = 0;
            foreach ($request->lines as $line) {
                $ligne_total = $line['ordered_quantity'] * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
                PurchaseProjectLine::create([
                    'purchase_project_id' => $project->id,
                    'article_code' => $line['article_code'],
                    'ordered_quantity' => $line['ordered_quantity'],
                    'unit_price_ht' => $line['unit_price_ht'],
                    'remise' => $line['remise'] ?? 0,
                    'tva' => $tvaRate,
                    'total_ligne_ht' => $ligne_total,
                    'prix_ttc' => $ligne_total * (1 + $tvaRate / 100),
                ]);
                $total += $ligne_total;
            }

            // Update totals
            $project->update([
                'total_ht' => $total,
                'total_ttc' => $total * (1 + $tvaRate / 100),
            ]);

            // Convert to order if requested
            if ($request->input('action') === 'convert') {
                $this->createOrderPurchaseFromProjectPurchase($project->id);
            }
        });

        // Clear session data
        Session::forget(['imported_lines', 'imported_supplier_id']);

        // Set success message
        switch ($status) {
            case 'validée':
                $successMessage = 'Projet validé avec succès.';
                break;
            case 'converted':
                $successMessage = 'Projet converti en commande avec succès.';
                break;
            default:
                $successMessage = 'Projet mis à jour avec succès.';
                break;
        }

        return redirect()->route('purchaseprojects.list')->with('success', $successMessage);
    }











    
     public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'lines' => 'required|array',
            'lines.*.article_code' => 'required|exists:items,code',
            'lines.*.ordered_quantity' => 'required|integer|min:1',
            'lines.*.unit_price_ht' => 'required|numeric|min:0',
            'lines.*.remise' => 'nullable|numeric|min:0|max:100',
        ]);

        // Warn if supplier changed after import
        if (Session::has('imported_supplier_id') && $request->supplier_id != Session::get('imported_supplier_id')) {
            Session::flash('warning', 'Vous avez changé de fournisseur après l\'importation. Les lignes importées peuvent ne pas correspondre au nouveau fournisseur.');
        }

        $supplier = Supplier::with('tvaGroup')->findOrFail($request->supplier_id);
        $tvaRate = $supplier->tvaGroup->rate ?? 0;
        $souche = Souche::where('type', 'projet_commande')->firstOrFail();
        $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
        $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;
        $status = $request->input('action') === 'validate' ? 'validée' : ($request->input('action') === 'convert' ? 'converted' : 'brouillon');

        DB::transaction(function () use ($request, $tvaRate, $numdoc, $status, $souche) {
            $project = PurchaseProject::create([
                'numdoc' => $numdoc,
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'notes' => $request->notes,
                'status' => $status,
                'total_ht' => 0,
                'total_ttc' => 0,
                'tva_rate' => $tvaRate,
            ]);

            $total = 0;
            foreach ($request->lines as $line) {
                $ligne_total = $line['ordered_quantity'] * $line['unit_price_ht'] * (1 - ($line['remise'] ?? 0) / 100);
                PurchaseProjectLine::create([
                    'purchase_project_id' => $project->id,
                    'article_code' => $line['article_code'],
                    'ordered_quantity' => $line['ordered_quantity'],
                    'unit_price_ht' => $line['unit_price_ht'],
                    'remise' => $line['remise'] ?? 0,
                    'tva' => $tvaRate,
                    'total_ligne_ht' => $ligne_total,
                    'prix_ttc' => $ligne_total * (1 + $tvaRate / 100),
                ]);
                $total += $ligne_total;
            }

            $project->update([
                'total_ht' => $total,
                'total_ttc' => $total * (1 + $tvaRate / 100),
            ]);

            $souche->last_number += 1;
            $souche->save();

            if ($request->input('action') === 'convert') {
                $this->createOrderPurchaseFromProjectPurchase($project->id);
            }
        });

        // Clear session data after successful save
        Session::forget(['imported_lines', 'imported_supplier_id']);

        // Set success message
        switch ($status) {
            case 'validée':
                $successMessage = 'Projet validé avec succès.';
                break;
            case 'converted':
                $successMessage = 'Projet converti en commande avec succès.';
                break;
            default:
                $successMessage = 'Projet enregistré avec succès.';
                break;
        }

        return redirect()->route('purchaseprojects.list')->with('success', $successMessage);
    }




    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        try {
            $import = new PurchaseProjectImport($request->supplier_id);
            Excel::import($import, $request->file('file'));
            return redirect()->route('purchaseprojects.index')
                ->with('success', 'Données importées avec succès. Veuillez vérifier les lignes.');
        } catch (\Exception $e) {
            return redirect()->route('purchaseprojects.index')
                ->with('error', 'Erreur lors de l\'importation : ' . $e->getMessage());
        }
    }

    public function exportTemplate()
    {
        return Excel::download(new \App\Exports\PurchaseProjectTemplateExport, 'purchase_project_template.xlsx');
    }

    public function list(Request $request)
    {
        $query = PurchaseProject::with('supplier', 'lines.item')->orderBy('updated_at', 'desc');

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('order_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('order_date', '<=', $request->date_to);
        }

        $projects = $query->get();
        $suppliers = Supplier::all();

        if ($request->input('action') === 'export') {
            return Excel::download(new \App\Exports\PurchaseProjectsExport($projects), 'purchase_projects.xlsx');
        }

        return view('purchaseprojects.list', compact('projects', 'suppliers'));
    }




     public function createOrderPurchaseFromProjectPurchase($projectId)
    {
        $project = PurchaseProject::with('lines.item', 'supplier.tvaGroup')->findOrFail($projectId);

        if ($project->status === 'convertedxxxx') {
            return redirect()->route('purchaseprojects.list')
                ->with('error', 'Ce projet a déjà été converti en commande.');
        }

        $souche = Souche::where('type', 'commande_achat')->firstOrFail();
        $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
        $numdoc = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

        DB::transaction(function () use ($project, $numdoc, $souche) {
            $order = PurchaseOrder::create([
                'numdoc' => $numdoc,
                'supplier_id' => $project->supplier_id,
                'order_date' => $project->order_date,
                'notes' => $project->notes,
                'total_ht' => $project->total_ht,
                'total_ttc' => $project->total_ttc,
                'status' => 'brouillon',
                'tva_rate' => $project->tva_rate,
            ]);

            foreach ($project->lines as $line) {
                PurchaseOrderLine::create([
                    'purchase_order_id' => $order->id,
                    'article_code' => $line->article_code,
                    'ordered_quantity' => $line->ordered_quantity,
                    'unit_price_ht' => $line->unit_price_ht,
                    'remise' => $line->remise,
                    'tva' => $line->tva,
                    'total_ligne_ht' => $line->total_ligne_ht,
                    'prix_ttc' => $line->prix_ttc,
                ]);
            }

            $project->update(['status' => 'converted']);
            $souche->last_number += 1;
            $souche->save();
        });

        return redirect()->route('purchases.list')
            ->with('success', 'Projet converti en commande avec succès.');
    }

    public function exportSingle($projectId)
    {
        $project = PurchaseProject::with('lines.item', 'supplier')->findOrFail($projectId);
        return Excel::download(new \App\Exports\PurchaseProjectExport($project), "purchase_project_{$project->numdoc}.xlsx");
    }





    public function export(Request $request)
    {
        $query = PurchaseProject::with('supplier', 'lines.item');

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('order_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('order_date', '<=', $request->date_to);
        }

        $projects = $query->get();
        return Excel::download(new \App\Exports\PurchaseProjectsExport($projects), 'purchase_projects.xlsx');
    }



}