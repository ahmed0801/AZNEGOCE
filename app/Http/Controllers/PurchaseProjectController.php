<?php

namespace App\Http\Controllers;

use App\Models\PurchaseProject;
use App\Models\PurchaseProjectLine;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\Souche;
use App\Services\PurchaseDocumentService;
use App\Services\PurchaseException;
use App\Imports\PurchaseProjectImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;



class PurchaseProjectController extends Controller
{
    /** L'ancienne page de création (import Excel) est remplacée par la page commune, qui gère aussi l'import Excel. */
    public function index()
    {
        return redirect()->route('purchase_docs.create');
    }

    /** Édition : même page que la création (recherche d'article, Excel, assistant prix, aide à la décision). */
    public function edit($projectId)
    {
        $project = PurchaseProject::with('lines.item', 'supplier.tvaGroup')->findOrFail($projectId);

        if ($project->status === 'converted') {
            return redirect()->route('purchaseprojects.list')->with('error', 'Ce projet est déjà converti en commande : il ne se modifie plus.');
        }

        return app(PurchaseDocumentController::class)->renderEdit('project', $project);
    }

    public function update(Request $request, $projectId)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date'  => 'required|date',
            'lines'       => 'required|array|min:1',
        ], ['lines.required' => 'Ajoutez au moins une ligne au projet.']);

        $project  = PurchaseProject::findOrFail($projectId);
        $supplier = Supplier::with('tvaGroup')->findOrFail($request->supplier_id);
        $action   = in_array($request->input('action'), ['validate', 'convert'], true) ? $request->input('action') : 'save';

        try {
            $res = app(PurchaseDocumentService::class)->updateProject($project, $supplier, [
                'date'     => $request->input('order_date'),
                'notes'    => $request->input('notes'),
                'store_id' => $request->input('store_id') ?: null,
            ], (array) $request->input('lines'), $action);
        } catch (PurchaseException $e) {
            return back()->withInput()->with('error', e($e->getMessage()));
        }

        if ($action === 'convert') {
            return redirect()->route('purchases.list')->with('success', "Projet {$res['project']->numdoc} converti en commande {$res['order']->numdoc}.");
        }
        return redirect()->route('purchaseprojects.list')->with('success', $action === 'validate' ? 'Projet validé.' : 'Projet mis à jour.');
    }

    /** Création depuis l'ancienne page d'import Excel — passe par le service (numérotation verrouillée, TVA par article). */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date'  => 'required|date',
            'lines'       => 'required|array|min:1',
        ]);

        $supplier = Supplier::with('tvaGroup')->findOrFail($request->supplier_id);
        $service  = app(PurchaseDocumentService::class);
        try {
            $project = $service->createProject($supplier, ['date' => $request->order_date, 'notes' => $request->notes], (array) $request->lines);
            if ($request->input('action') === 'validate') $service->validateProject($project->id);
            if ($request->input('action') === 'convert') {
                $order = $service->convertProject($project->id);
                Session::forget(['imported_lines', 'imported_supplier_id']);
                return redirect()->route('purchases.list')->with('success', "Projet {$project->numdoc} converti en commande {$order->numdoc}.");
            }
        } catch (PurchaseException $e) {
            return back()->withInput()->with('error', e($e->getMessage()));
        }

        Session::forget(['imported_lines', 'imported_supplier_id']);
        return redirect()->route('purchaseprojects.list')->with('success', $request->input('action') === 'validate' ? 'Projet validé.' : 'Projet enregistré.');
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
        try {
            $order = app(PurchaseDocumentService::class)->convertProject((int) $projectId);
        } catch (PurchaseException $e) {
            return redirect()->route('purchaseprojects.list')->with('error', e($e->getMessage()));
        }
        return redirect()->route('purchases.list')->with('success', "Projet converti en commande {$order->numdoc}.");
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