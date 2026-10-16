<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Exports\PurchaseExport;
use App\Exports\PurchaseInvoiceExport;
use App\Exports\PurchaseNoteExport;
use App\Exports\PurchaseNotesExport;
use App\Exports\PurchaseReturnExport;
use App\Exports\PurchasesExport;
use App\Models\CompanyInformation;
use App\Models\Item;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceLine;
use App\Models\PurchaseNote;
use App\Models\PurchaseNoteLine;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnLine;
use App\Models\Reception;
use App\Models\ReceptionLine;
use App\Models\Souche;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Services\PurchaseDocumentService;
use App\Services\PurchaseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Picqer\Barcode\BarcodeGeneratorPNG;
use PDF; 


class PurchaseController extends Controller
{
public function list(Request $request)
{
    $query = PurchaseOrder::with(['supplier', 'lines.item', 'receptions'])->orderBy('updated_at', 'desc');

    if ($request->filled('q')) {
        $t = trim($request->q);
        $query->where(function ($w) use ($t) {
            $w->where('numdoc', 'like', "%$t%")
              ->orWhereHas('supplier', function ($s) use ($t) { $s->where('name', 'like', "%$t%")->orWhere('code', 'like', "%$t%"); });
        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('supplier_id')) {
        $query->where('supplier_id', $request->supplier_id);
    }

    if ($request->filled('date_from')) {
        $query->whereDate('order_date', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('order_date', '<=', $request->date_to);
    }

    if ($request->filled('reception_status')) {
        $query->where('reception_status', $request->reception_status);
    }

    $purchases = $query->paginate(50);
    $suppliers = \App\Models\Supplier::orderBy('name')->get();

    return view('purchases', compact('purchases', 'suppliers'));
}


    /** Ancienne page de création de commande : tout passe par la page unique d'achat. */
    public function index()
    {
        return redirect()->route('purchase_docs.create');
    }

   
    

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'lines'       => 'required|array|min:1',
        ]);

        $supplier = Supplier::with('tvaGroup')->findOrFail($request->supplier_id);
        $header = [
            'date'     => $request->input('order_date') ?: now()->toDateString(),
            'notes'    => $request->input('notes'),
            'type'     => $request->input('type') ?: 'Commande stock',
            'store_id' => $request->input('store_id') ?: null,
            'status'   => $request->input('action') === 'validate' ? 'validée' : 'brouillon',
        ];

        try {
            $order = app(PurchaseDocumentService::class)->createOrder($supplier, $header, (array) $request->input('lines'));
        } catch (PurchaseException $e) {
            return back()->withInput()->with('error', e($e->getMessage()));
        }

        return redirect()->route('purchases.list')->with('success', "Commande {$order->numdoc} créée. Le stock entrera à la réception.");
    }


    public function edit($id)
    {
        $order = PurchaseOrder::with('lines.item', 'supplier')->findOrFail($id);
        if (Reception::where('purchase_order_id', $order->id)->where('status', '!=', 'annulée')->exists() || $order->invoiced) {
            return redirect()->route('purchases.list')->with('error', "La commande {$order->numdoc} a déjà des réceptions ou une facture : elle ne peut plus être modifiée.");
        }
        return app(PurchaseDocumentController::class)->renderEdit('order', $order);
    }

    public function update(Request $request, $numdoc)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date'  => 'required|date',
            'lines'       => 'required|array|min:1',
        ]);

        $supplier = Supplier::with('tvaGroup')->findOrFail($request->supplier_id);
        $order    = PurchaseOrder::where('numdoc', $numdoc)->firstOrFail();
        $validate = $request->input('action') === 'validate';

        try {
            app(PurchaseDocumentService::class)->updateOrder($order, $supplier, [
                'date'  => $request->input('order_date'),
                'notes' => $request->input('notes'),
            ], (array) $request->input('lines'), $validate);
        } catch (PurchaseException $e) {
            return back()->withInput()->with('error', e($e->getMessage()));
        }

        return redirect()->route('purchases.list')
            ->with('success', $validate ? 'Commande mise à jour et validée.' : 'Commande mise à jour.');
    }


    /** Valide un brouillon. Aucun stock ici : il entre à la réception. */
    public function validateOrder($id)
    {
        $order = PurchaseOrder::findOrFail($id);

        if ($order->status !== 'brouillon') {
            return back()->with('error', 'Cette commande est déjà validée.');
        }

        $order->update(['status' => 'validée']);

        return back()->with('success', 'Commande validée. Utilisez « Réceptionner » à l\'arrivée de la marchandise.');
    }


public function export(Request $request)
{
    // Récupérer les filtres de la requête
    $filters = $request->only(['status', 'supplier_id', 'date_from', 'date_to']);

    // Exporter le fichier Excel avec les filtres
    return Excel::download(new PurchasesExport($filters), 'commandes_achat.xlsx');
}



    public function exportSingle($id)
    {
        $purchase = PurchaseOrder::with(['supplier', 'reception', 'lines.item'])->findOrFail($id);
        return Excel::download(new PurchaseExport($purchase), "commande_{$purchase->numdoc}.xlsx");
    }
    




    public function printSingleold($id)
    {
        $purchase = PurchaseOrder::with(['supplier', 'reception', 'lines.item', 'supplier.tvaGroup'])->findOrFail($id);
        $company = CompanyInformation::first() ?? new CompanyInformation([
            'name' => 'Test Company S.A.R.L',
            'address' => '123 Rue Fictive, Tunis 1000',
            'phone' => '+216 12 345 678',
            'email' => 'contact@testcompany.com',
            'matricule_fiscal' => '1234567ABC000',
            'swift' => 'TESTTNTT',
            'rib' => '123456789012',
            'iban' => 'TN59 1234 5678 9012 3456 7890',
            'logo_path' => 'assets/img/test_logo.png',
        ]);
        $pdf = Pdf::loadView('pdf.purchase', compact('purchase', 'company'));
        return $pdf->stream("commande_{$purchase->numdoc}.pdf");
    }

    public function companyInformation()
    {
        $company = CompanyInformation::first();
        return view('company_information', compact('company'));
    }




        public function printSingle($id)
    {
        $purchase = PurchaseOrder::with(['supplier', 'reception', 'lines.item', 'supplier.tvaGroup'])->findOrFail($id);
        $company = CompanyInformation::first() ?? new CompanyInformation([
            'name' => 'Test Company S.A.R.L',
            'address' => '123 Rue Fictive, Tunis 1000',
            'phone' => '+216 12 345 678',
            'email' => 'contact@testcompany.com',
            'matricule_fiscal' => '1234567ABC000',
            'swift' => 'TESTTNTT',
            'rib' => '123456789012',
            'iban' => 'TN59 1234 5678 9012 3456 7890',
            'logo_path' => 'assets/img/test_logo.png',
        ]);

                $generator = new BarcodeGeneratorPNG();
    $barcode = 'data:image/png;base64,' . base64_encode(
        $generator->getBarcode($purchase->numdoc, $generator::TYPE_CODE_128)
    );

        $pdf = Pdf::loadView('pdf.purchase', compact('purchase', 'company','barcode'));
        return $pdf->stream("commande_{$purchase->numdoc}.pdf");
    }
    





// imprimer avis de retrait

public function withdrawalNotice(Request $request, $id)
{
    $purchase = PurchaseOrder::with(['supplier', 'lines.item'])
        ->where('id', $id)
        ->firstOrFail();

    // Vérifiez que le statut de livraison est "non_récuperée"
    if ($purchase->status_livraison !== 'non_récuperée') {
        return redirect()->route('purchases.list')->with('error', 'L\'avis de retrait ne peut être généré que pour les commandes non récupérées.');
    }

    $company = CompanyInformation::first() ?? new CompanyInformation([
        'name' => 'Test Company S.A.R.L',
        'address' => '123 Rue Fictive, Tunis 1000',
        'phone' => '+216 12 345 678',
        'email' => 'contact@testcompany.com',
        'matricule_fiscal' => '1234567ABC000',
        'swift' => 'TESTTNTT',
        'rib' => '123456789012',
        'iban' => 'TN59 1234 5678 9012 3456 7890',
        'logo_path' => 'assets/img/test_logo.png',
    ]);

    $generator = new BarcodeGeneratorPNG();
    $barcode = 'data:image/png;base64,' . base64_encode(
        $generator->getBarcode($purchase->numdoc, $generator::TYPE_CODE_128)
    );

    // Récupérer le commentaire depuis la requête
    $comment = $request->input('comment');

    $pdf = Pdf::loadView('pdf.withdrawal_notice', compact('purchase', 'company', 'barcode', 'comment'));
    return $pdf->stream("avis_retrait_{$purchase->numdoc}.pdf");
}








    public function storeCompanyInformation(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'matricule_fiscal' => 'required|string|max:50',
            'swift' => 'nullable|string|max:50',
            'rib' => 'nullable|string|max:50',
            'iban' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $company = CompanyInformation::first();

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = $file->getClientOriginalName();
            $logoPath = 'assets/img/' . $filename;
            $file->move(public_path('assets/img'), $filename);
            $validated['logo_path'] = $logoPath;
        } elseif ($company) {
            $validated['logo_path'] = $company->logo_path;
        } else {
            $validated['logo_path'] = 'assets/img/test_logo.png';
        }

        if ($company) {
            $company->update($validated);
        } else {
            CompanyInformation::create($validated);
        }

        return redirect()->route('company_information.edit')->with('success', 'Informations de l\'entreprise enregistrées avec succès.');
    }









/** Ancien lien « retour depuis la commande » : on ouvre la page unique de retour avec la commande présélectionnée. */
public function createReturn($id)
{
    $order = PurchaseOrder::findOrFail($id);
    if ($order->status !== 'validée') {
        return redirect()->route('purchases.list')->with('error', 'La commande doit être validée pour créer un retour.');
    }
    return redirect()->route('purchases.return.new', ['supplier_id' => $order->supplier_id, 'order_id' => $order->id]);
}

/** Page « Nouveau retour fournisseur » : fournisseur → commande source → quantités à retourner. */
public function newReturn(Request $request)
{
    $suppliers = Supplier::orderBy('name')->get(['id', 'code', 'name']);
    $orderId = (int) $request->input('order_id', 0);
    $order = $orderId ? PurchaseOrder::find($orderId) : null;
    $supplierId = $request->input('supplier_id', $order ? $order->supplier_id : null);
    return view('purchases_return_create', compact('suppliers', 'supplierId', 'orderId'));
}

/** JSON : commandes d'un fournisseur sur lesquelles il reste de la marchandise retournable. */
public function returnOrders(Request $request)
{
    $request->validate(['supplier_id' => 'required|exists:suppliers,id']);
    $svc = app(PurchaseDocumentService::class);
    $out = [];
    $orders = PurchaseOrder::where('supplier_id', $request->supplier_id)->where('status', 'validée')
        ->orderByDesc('order_date')->orderByDesc('id')->limit(150)->get();
    foreach ($orders as $o) {
        $rows = $svc->returnableForOrder($o);
        $qty = 0.0; $recv = 0.0;
        foreach ($rows as $r) { $qty += $r['max']; $recv += $r['received']; }
        if ($qty <= 0) continue;
        $out[] = ['id' => $o->id, 'numdoc' => $o->numdoc, 'date' => $o->order_date ? \Carbon\Carbon::parse($o->order_date)->format('d/m/Y') : '', 'returnable' => $qty, 'received' => $recv];
    }
    return response()->json(['ok' => true, 'orders' => $out]);
}

/** JSON : lignes retournables d'une commande (reçu, déjà retourné, retournable, stock actuel). */
public function returnOrderLines($id)
{
    $order = PurchaseOrder::with('supplier')->findOrFail($id);
    if ($order->status !== 'validée') {
        return response()->json(['ok' => false, 'message' => 'La commande doit être validée.'], 422);
    }
    $rows = array_values(app(PurchaseDocumentService::class)->returnableForOrder($order));
    $storeId = (int) ($order->store_id ?? 1);
    $stocks = DB::table('stocks')->join('items', 'items.id', '=', 'stocks.item_id')
        ->where('stocks.store_id', $storeId)->whereIn('items.code', array_column($rows, 'code'))
        ->pluck('stocks.quantity', 'items.code');
    foreach ($rows as &$r) { $r['stock'] = (float) ($stocks[$r['code']] ?? 0); }
    unset($r);
    return response()->json(['ok' => true, 'order' => ['id' => $order->id, 'numdoc' => $order->numdoc, 'supplier_id' => $order->supplier_id, 'date' => $order->order_date, 'reception_status' => $order->reception_status, 'tva_rate' => (float) $order->tva_rate], 'lines' => $rows]);
}

/** Retour fournisseur : quantités plafonnées à « reçu − déjà retourné », stock ressorti (jamais négatif), le tout dans une transaction. */
public function storeReturn(Request $request, $id)
{
    $request->validate([
        'return_date' => 'required|date',
        'type'        => 'required|in:total,partiel',
        'lines'       => 'nullable|array',
    ]);

    $order = PurchaseOrder::findOrFail($id);
    $qty = [];
    foreach ((array) $request->input('lines', []) as $code => $line) {
        if (!empty($line['selected'])) {
            $qty[(string) $code] = $line['returned_quantity'] ?? 0;
        }
    }

    try {
        $ret = app(PurchaseDocumentService::class)->createReturn($order, [
            'date'  => $request->return_date,
            'notes' => $request->notes,
        ], $qty, $request->type === 'total');
    } catch (PurchaseException $e) {
        return back()->withInput()->with('error', e($e->getMessage()));
    }

    return redirect()->route('returns.list')->with('success', "Retour {$ret->numdoc} créé : le stock a été ressorti.");
}

public function cancelReturnAction(Request $request, $id)
{
    try {
        $ret = app(PurchaseDocumentService::class)->cancelReturn((int) $id, $request->input('reason'));
    } catch (PurchaseException $e) {
        return back()->with('error', e($e->getMessage()));
    }
    return back()->with('success', "Retour {$ret->numdoc} annulé : le stock est revenu.");
}


     public function returnsList(Request $request)
    {
        $query = PurchaseReturn::with(['purchaseOrder.supplier', 'lines.item'])->orderBy('updated_at', 'desc');

        if ($request->filled('supplier_id')) {
            $query->where(function ($w) use ($request) {
                $w->where('supplier_id', $request->supplier_id)
                  ->orWhereHas('purchaseOrder', function ($q) use ($request) {
                      $q->where('supplier_id', $request->supplier_id);
                  });
            });
        }

        if ($request->filled('purchase_order_id')) {
            $query->where('purchase_order_id', $request->purchase_order_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('return_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('return_date', '<=', $request->date_to);
        }

        $returns = $query->paginate(50);
        $suppliers = Supplier::orderBy('name')->get();
        $purchaseOrders = PurchaseOrder::has('returns')->orderBy('numdoc')->get();

        return view('retourachat', compact('returns', 'suppliers', 'purchaseOrders'));
    }

    public function showReturn($id)
    {
        $return = PurchaseReturn::with(['supplier', 'purchaseOrder.supplier', 'lines.item'])->findOrFail($id);
        return view('retourachatshow', compact('return'));
    }
    
    public function exportSingleReturn($id)
    {
        $return = PurchaseReturn::findOrFail($id);
        return Excel::download(new PurchaseReturnExport($return), 'retour_achat_' . $return->numdoc . '.xlsx');
    }

    public function printSingleReturn($id)
    {
        $return = PurchaseReturn::with(['supplier', 'purchaseOrder.supplier', 'lines.item'])->findOrFail($id);
        $company = CompanyInformation::first();

                        $generator = new BarcodeGeneratorPNG();
    $barcode = 'data:image/png;base64,' . base64_encode(
        $generator->getBarcode($return->numdoc, $generator::TYPE_CODE_128)
    );


        $pdf = PDF::loadView('pdf.purchase_return', compact('return', 'company','barcode'));
        return $pdf->stream('retour_achat_' . $return->numdoc . '.pdf');
    }










// facture

    public function invoicesList(Request $request)
    {
        $query = PurchaseInvoice::with(['supplier', 'lines.item', 'lines.reception', 'payments'])->orderBy('updated_at', 'desc');

        if ($request->filled('q')) {
            $t = trim($request->q);
            $query->where(function ($w) use ($t) {
                $w->where('numdoc', 'like', "%$t%")
                  ->orWhere('supplier_ref', 'like', "%$t%")
                  ->orWhereHas('supplier', function ($s) use ($t) { $s->where('name', 'like', "%$t%")->orWhere('code', 'like', "%$t%"); });
            });
        }
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } elseif (!$request->boolean('with_cancelled')) {
            $query->where('status', '!=', 'annulée');
        }
        if ($request->filled('paid')) {
            $query->where('paid', $request->paid === '1');
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        $invoices = $query->paginate(50);
        $suppliers = Supplier::orderBy('name')->get();

        return view('invoices.list', compact('invoices', 'suppliers'));
    }

    /** Facture directe d'une commande : elle passe par les réceptions (le stock entre à la réception). */
    public function createDirectInvoice($orderId)
    {
        $order = PurchaseOrder::findOrFail($orderId);
        $service = app(PurchaseDocumentService::class);

        if ($order->status !== 'validée') {
            return redirect()->route('purchases.list')->with('error', 'La commande doit être validée avant d\'être facturée.');
        }
        if ($order->invoiced) {
            return redirect()->route('purchases.list')->with('error', 'Cette commande est déjà facturée.');
        }
        if ($service->isLegacyUninvoicedOrder($order)) {
            return redirect()->route('purchases.list')->with('error', 'Ancienne commande déjà reçue : utilisez « Facturer (ancienne commande) » dans le menu de la commande.');
        }
        if ($order->reception_status === 'recue') {
            return redirect()->route('receptions.index', ['state' => 'a_facturer', 'q' => $order->numdoc])
                ->with('success', 'Sélectionnez la ou les réceptions de la commande à facturer.');
        }
        return redirect()->route('receptions.create', ['order_id' => $order->id])
            ->with('success', 'Réceptionnez la marchandise puis utilisez « Réceptionner et facturer ».');
    }

    /** Facture groupée : sélection des réceptions dans la liste des réceptions. */
    public function createGroupedInvoice()
    {
        return redirect()->route('receptions.index', ['state' => 'a_facturer'])
            ->with('success', 'Cochez les réceptions d\'un même fournisseur puis « Facturer la sélection ».');
    }

    /** Facture d'anciennes commandes (déjà reçues avant la migration) — sans toucher au stock. */
    public function storeLegacyInvoice(Request $request)
    {
        $request->validate(['order_ids' => 'required|array|min:1', 'order_ids.*' => 'integer']);
        $orders = PurchaseOrder::whereIn('id', $request->order_ids)->get();
        if ($orders->isEmpty()) {
            return back()->with('error', 'Commande introuvable.');
        }
        $supplier = Supplier::findOrFail($orders->first()->supplier_id);
        try {
            $inv = app(PurchaseDocumentService::class)->invoiceLegacyOrders($supplier, [
                'invoice_date' => $request->input('invoice_date') ?: now()->toDateString(),
                'due_date'     => $request->input('due_date') ?: null,
                'supplier_ref' => $request->input('supplier_ref'),
            ], $orders->all());
        } catch (PurchaseException $e) {
            return back()->with('error', e($e->getMessage()));
        }
        return redirect()->route('invoices.list')->with('success', "Facture {$inv->numdoc} créée (stock déjà pris en compte à l'époque).");
    }

    public function createFreeInvoice()
    {
        $suppliers = Supplier::with('tvaGroup')->orderBy('name')->get();
        $tvaMap = $suppliers->mapWithKeys(function ($supplier) {
            return [$supplier->id => $supplier->tvaGroup->rate ?? 0];
        })->toArray();

        return view('invoices.create_free', compact('suppliers', 'tvaMap'));
    }

    /** Seule la facture LIBRE se saisit ici ; directe / groupée se font à partir des réceptions. */
    public function storeInvoice(Request $request)
    {
        $request->validate([
            'supplier_id'  => 'required|exists:suppliers,id',
            'invoice_date' => 'required|date',
            'due_date'     => 'nullable|date',
            'lines'        => 'required|array|min:1',
        ]);
        if ($request->input('type', 'libre') !== 'libre') {
            return redirect()->route('receptions.index', ['state' => 'a_facturer'])
                ->with('error', 'Les factures directes et groupées se créent à partir des réceptions.');
        }

        $supplier = Supplier::with('tvaGroup')->findOrFail($request->supplier_id);
        try {
            $inv = app(PurchaseDocumentService::class)->createFreeInvoice($supplier, $this->invoiceHeader($request), (array) $request->lines, $request->input('action') === 'validate');
        } catch (PurchaseException $e) {
            return back()->withInput()->with('error', e($e->getMessage()));
        }
        return redirect()->route('invoices.list')->with('success', "Facture {$inv->numdoc} " . ($inv->status === 'validée' ? 'validée.' : 'enregistrée en brouillon.'));
    }

    private function invoiceHeader(Request $request): array
    {
        return [
            'invoice_date' => $request->input('invoice_date'),
            'due_date'     => $request->input('due_date') ?: null,
            'supplier_ref' => $request->input('supplier_ref'),
            'notes'        => $request->input('notes'),
            'tva_rate'     => $request->input('tva_rate'),
        ];
    }

    public function editInvoice($id)
    {
        $invoice = PurchaseInvoice::with(['lines.item', 'supplier'])->findOrFail($id);
        if ($invoice->status !== 'brouillon') {
            return redirect()->route('invoices.list')->with('error', 'Seule une facture en brouillon est modifiable. Pour une facture validée : « Modifier les mentions » ou « Annuler la facture ».');
        }
        $suppliers = Supplier::all();
        $tvaRates = Supplier::with('tvaGroup')
            ->get()
            ->mapWithKeys(fn($s) => [$s->id => $s->tvaGroup->rate ?? 0])
            ->toJson();
        return view('invoices.edit', compact('invoice', 'suppliers', 'tvaRates'));
    }

    public function updateInvoice(Request $request, $numdoc)
    {
        $request->validate([
            'supplier_id'  => 'required|exists:suppliers,id',
            'invoice_date' => 'required|date',
            'due_date'     => 'nullable|date',
            'lines'        => 'required|array|min:1',
        ]);

        $invoice  = PurchaseInvoice::where('numdoc', $numdoc)->firstOrFail();
        $supplier = Supplier::with('tvaGroup')->findOrFail($request->supplier_id);
        $validate = $request->input('action') === 'validate';

        try {
            app(PurchaseDocumentService::class)->updateDraftInvoice($invoice->id, $supplier, $this->invoiceHeader($request), (array) $request->lines, $validate);
        } catch (PurchaseException $e) {
            return back()->withInput()->with('error', e($e->getMessage()));
        }
        return redirect()->route('invoices.list')->with('success', $validate ? 'Facture mise à jour et validée.' : 'Brouillon mis à jour.');
    }

    public function validateInvoiceAction($id)
    {
        try {
            $inv = app(PurchaseDocumentService::class)->validateInvoice((int) $id);
        } catch (PurchaseException $e) {
            return back()->with('error', e($e->getMessage()));
        }
        return back()->with('success', "Facture {$inv->numdoc} validée.");
    }

    public function updateInvoiceHeaderAction(Request $request, $id)
    {
        $request->validate(['due_date' => 'nullable|date', 'invoice_date' => 'nullable|date']);
        try {
            $inv = app(PurchaseDocumentService::class)->updateInvoiceHeader((int) $id, $request->only(['supplier_ref', 'due_date', 'notes', 'invoice_date']));
        } catch (PurchaseException $e) {
            return back()->with('error', e($e->getMessage()));
        }
        return back()->with('success', "Mentions de la facture {$inv->numdoc} mises à jour.");
    }

    public function cancelInvoiceAction(Request $request, $id)
    {
        try {
            $inv = app(PurchaseDocumentService::class)->cancelInvoice((int) $id, $request->input('reason'));
        } catch (PurchaseException $e) {
            return back()->with('error', e($e->getMessage()));
        }
        return back()->with('success', "Facture {$inv->numdoc} annulée : solde fournisseur corrigé, réceptions de nouveau à facturer.");
    }

public function printSingleInvoice($id)
{
    $invoice = PurchaseInvoice::with(['supplier', 'lines.item', 'lines.reception', 'lines.purchaseReturn', 'payments'])->findOrFail($id);
    $company = CompanyInformation::first() ?? new CompanyInformation([
        'name' => 'Test Company S.A.R.L',
        'address' => '123 Rue Fictive, Tunis 1000',
        'phone' => '+216 12 345 678',
        'email' => 'contact@testcompany.com',
        'matricule_fiscal' => '1234567ABC000',
        'swift' => 'TESTTNTT',
        'rib' => '123456789012',
        'iban' => 'TN59 1234 5678 9012 3456 7890',
        'logo_path' => 'assets/img/test_logo.png',
    ]);
$generator = new BarcodeGeneratorPNG();
    $barcode = 'data:image/png;base64,' . base64_encode(
        $generator->getBarcode($invoice->numdoc, $generator::TYPE_CODE_128)
    );

    $pdf = PDF::loadView('pdf.purchase_invoice', compact('invoice', 'company', 'barcode'));
        return $pdf->stream("facture_{$invoice->numdoc}.pdf");
}



public function exportSingleInvoice($id)
{
    $invoice = PurchaseInvoice::with(['supplier', 'lines.item'])->findOrFail($id);
    return Excel::download(new PurchaseInvoiceExport($invoice), "facture_{$invoice->numdoc}.xlsx");
}

public function exportinvoices(Request $request)
{
    $filters = $request->only(['supplier_id', 'status', 'type', 'date_from', 'date_to']);
    return Excel::download(new InvoicesExport($filters), 'factures_achat.xlsx');
}



public function old_search_onlycmd(Request $request)
{
    $query = PurchaseOrder::where('status', 'validée')
        ->where('invoiced', 0)
        ->with(['lines.item', 'supplier']);
    
    if ($request->filled('supplier_id')) {
        $query->where('supplier_id', $request->supplier_id);
    }
    if ($request->filled('term')) {
        $query->where('numdoc', 'like', '%' . $request->term . '%');
    }

    return $query->get()->map(function ($order) {
        return [
            'id' => $order->id,
            'numdoc' => $order->numdoc,
            'order_date' => $order->order_date,
            'supplier_name' => $order->supplier->name,
            'tva_rate' => $order->tva_rate ?? 0,
            'lines' => $order->lines->map(function ($line) {
                return [
                    'article_code' => $line->article_code,
                    'item_name' => $line->item->name ?? null,
                    'ordered_quantity' => $line->ordered_quantity,
                    'unit_price_ht' => $line->unit_price_ht,
                    'remise' => $line->remise,
                ];
            })->toArray(),
        ];
    });
}







public function search(Request $request)
{
    $supplierId = $request->input('supplier_id');
    $term = $request->input('term');

    // Search purchase orders
    $orderQuery = PurchaseOrder::where('status', 'validée')
        ->where('invoiced', 0)
        ->with(['lines.item', 'supplier']);

    if ($supplierId) {
        $orderQuery->where('supplier_id', $supplierId);
    }
    if ($term) {
        $orderQuery->where('numdoc', 'like', '%' . $term . '%');
    }

    $orders = $orderQuery->get()->map(function ($order) {
        return [
            'id' => 'order_' . $order->id,
            'type' => 'order',
            'numdoc' => $order->numdoc,
            'order_date' => $order->order_date,
            'supplier_name' => $order->supplier->name,
            'tva_rate' => $order->tva_rate ?? 0,
            'lines' => $order->lines->map(function ($line) {
                return [
                    'article_code' => $line->article_code,
                    'item_name' => $line->item->name ?? null,
                    'ordered_quantity' => $line->ordered_quantity,
                    'unit_price_ht' => $line->unit_price_ht,
                    'remise' => $line->remise,
                ];
            })->toArray(),
        ];
    });

    // Search purchase returns
    $returnQuery = PurchaseReturn::where('invoiced', 0)
        ->with(['lines.item', 'supplier']);

    if ($supplierId) {
        $returnQuery->where('supplier_id', $supplierId);
    }
    if ($term) {
        $returnQuery->where('numdoc', 'like', '%' . $term . '%');
    }

    $returns = $returnQuery->get()->map(function ($return) {
        return [
            'id' => 'return_' . $return->id,
            'type' => 'return',
            'numdoc' => $return->numdoc,
            'order_date' => $return->return_date,
            'supplier_name' => $return->supplier->name ?? 'N/A',
            'tva_rate' => $return->tva_rate ?? 0,
            'lines' => $return->lines->map(function ($line) {
                return [
                    'article_code' => $line->article_code,
                    'item_name' => $line->item->name ?? null,
                    'ordered_quantity' => -$line->returned_quantity, // Use returned_quantity
                    'unit_price_ht' => $line->unit_price_ht,
                    'remise' => $line->remise,
                ];
            })->toArray(),
        ];
    });

    return response()->json($orders->merge($returns)->toArray());
}





























// Nouvelles méthodes pour le module PurchaseNote avoirs
   public function notesList(Request $request)
{
    $query = PurchaseNote::with(['supplier', 'lines.item', 'lines.purchaseReturn', 'lines.purchaseInvoice'])->orderBy('updated_at', 'desc');

    if ($request->filled('supplier_id')) {
        $query->where('supplier_id', $request->supplier_id);
    }
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }
    if ($request->filled('date_from')) {
        $query->whereDate('note_date', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $query->whereDate('note_date', '<=', $request->date_to);
    }

    $notes = $query->paginate(50);
    $suppliers = Supplier::orderBy('name')->get();
    $returns = PurchaseReturn::with('supplier')->where('invoiced', 0)->where(function ($q) { $q->whereNull('status')->orWhere('status', '!=', 'annulée'); })->get();
    $invoices = PurchaseInvoice::with('supplier')->where('status', 'validée')->get(); // Only validated invoices

    return view('notes.list', compact('notes', 'suppliers', 'returns', 'invoices'));
}





    public function createNoteFromReturn()
    {
        $returns = PurchaseReturn::with(['lines.item', 'supplier'])
            ->where('invoiced', 0)->where(function ($q) { $q->whereNull('status')->orWhere('status', '!=', 'annulée'); })
            ->get();
        $suppliers = Supplier::all();
        $tvaRates = Supplier::with('tvaGroup')->get()->mapWithKeys(fn($s) => [$s->id => $s->tvaGroup->rate ?? 0])->toJson();

        return view('notes.create_from_return', compact('returns', 'suppliers', 'tvaRates'));
    }



public function createNoteFromInvoice()
{
    $invoices = PurchaseInvoice::with(['lines.item', 'supplier'])
        ->where('status', 'validée')
        ->get();
    $suppliers = Supplier::all();
    $tvaRates = Supplier::with('tvaGroup')
        ->get()
        ->mapWithKeys(fn($s) => [$s->id => $s->tvaGroup->rate ?? 0])
        ->toJson();

    return view('notes.create_from_invoice', compact('invoices', 'suppliers', 'tvaRates'));
}

    public function getReturnLines(Request $request)
    {
        $lines = collect(app(PurchaseDocumentService::class)->creditableLines('from_return', array_map('intval', (array) $request->input('return_ids', []))))
            ->filter(fn ($l) => $l['available'] > 0)
            ->map(fn ($l) => [
                'purchase_return_id' => $l['source_id'], 'return_numdoc' => $l['source_numdoc'], 'article_code' => $l['code'],
                'description' => $l['name'], 'returned_quantity' => $l['available'], 'unit_price_ht' => $l['pu'], 'remise' => $l['remise'], 'tva' => $l['tva'],
            ])->values();

        return response()->json(['lines' => $lines]);
    }

    public function getInvoiceLines(Request $request)
    {
        $lines = collect(app(PurchaseDocumentService::class)->creditableLines('from_invoice', array_map('intval', (array) $request->input('invoice_ids', []))))
            ->filter(fn ($l) => $l['available'] > 0)
            ->map(fn ($l) => [
                'purchase_invoice_id' => $l['source_id'], 'invoice_numdoc' => $l['source_numdoc'], 'article_code' => $l['code'],
                'description' => $l['name'], 'quantity' => $l['available'], 'unit_price_ht' => $l['pu'], 'remise' => $l['remise'], 'tva' => $l['tva'],
            ])->values();

        return response()->json(['lines' => $lines]);
    }

  






    // ffffffffffffffffffffff




    // fffffffffffffffffffffffffff







    /** Création d'un avoir (depuis des retours ou des factures) — toute la logique est dans le service. */
    private function storeNoteOfType(Request $request, string $type)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'note_date'   => 'required|date',
            'lines'       => 'required|array|min:1',
            $type === 'from_return' ? 'returns' : 'invoices' => 'required|array|min:1',
        ], [
            'returns.required'  => 'Au moins un retour doit être sélectionné.',
            'invoices.required' => 'Au moins une facture doit être sélectionnée.',
        ]);

        $supplier = Supplier::findOrFail($request->supplier_id);
        $validate = $request->input('action') === 'validate';
        try {
            $note = app(PurchaseDocumentService::class)->createNote($supplier, $type, [
                'note_date'    => $request->note_date,
                'notes'        => $request->notes,
                'return_goods' => $request->boolean('return_goods'),
            ], (array) ($type === 'from_return' ? $request->returns : $request->invoices), (array) $request->lines, $validate);
        } catch (PurchaseException $e) {
            return back()->withInput()->with('error', e($e->getMessage()));
        }

        return redirect()->route('notes.list')->with('success', "Avoir {$note->numdoc} " . ($validate ? 'validé : le solde du fournisseur a été mis à jour.' : 'enregistré en brouillon.'));
    }

    public function storeReturnNote(Request $request)
    {
        return $this->storeNoteOfType($request, 'from_return');
    }

    public function storeInvoiceNote(Request $request)
    {
        return $this->storeNoteOfType($request, 'from_invoice');
    }

    public function storeNote(Request $request)
    {
        return $this->storeNoteOfType($request, $request->input('type') === 'from_return' ? 'from_return' : 'from_invoice');
    }

    public function validateNoteAction($id)
    {
        try {
            $note = app(PurchaseDocumentService::class)->validateNote((int) $id);
        } catch (PurchaseException $e) {
            return back()->with('error', e($e->getMessage()));
        }
        return back()->with('success', "Avoir {$note->numdoc} validé : le solde du fournisseur a été mis à jour.");
    }

    public function cancelNoteAction(Request $request, $id)
    {
        try {
            $note = app(PurchaseDocumentService::class)->cancelNote((int) $id, $request->input('reason'));
        } catch (PurchaseException $e) {
            return back()->with('error', e($e->getMessage()));
        }
        return back()->with('success', "Avoir {$note->numdoc} annulé : solde remis, retours libérés.");
    }

    public function editNote($id)
    {
        $note = PurchaseNote::with(['lines.item', 'supplier'])->findOrFail($id);
        if ($note->status === 'validée') {
            return back()->with('error', 'Impossible de modifier un avoir validé.');
        }
        $suppliers = Supplier::all();
        $tvaRates = Supplier::with('tvaGroup')
            ->get()
            ->mapWithKeys(fn($s) => [$s->id => $s->tvaGroup->rate ?? 0])
            ->toJson();
        return view('notes.edit', compact('note', 'suppliers', 'tvaRates'));
    }

    public function updateNote(Request $request, $numdoc)
    {
        $request->validate([
            'note_date' => 'required|date',
            'lines'     => 'required|array|min:1',
        ]);

        $note = PurchaseNote::where('numdoc', $numdoc)->firstOrFail();
        $validate = $request->input('action') === 'validate';
        try {
            app(PurchaseDocumentService::class)->updateDraftNote($note->id, [
                'note_date'    => $request->note_date,
                'notes'        => $request->notes,
                'return_goods' => $request->boolean('return_goods'),
            ], (array) $request->lines, $validate);
        } catch (PurchaseException $e) {
            return back()->withInput()->with('error', e($e->getMessage()));
        }

        return redirect()->route('notes.list')->with('success', $validate ? 'Avoir mis à jour et validé.' : 'Brouillon d\'avoir mis à jour.');
    }

    public function showNote($id)
    {
        $note = PurchaseNote::with(['supplier', 'lines.item', 'purchaseReturn', 'purchaseInvoice'])->findOrFail($id);
        return view('notes.show', compact('note'));
    }

    public function exportSingleNote($id)
    {
        $note = PurchaseNote::with(['supplier', 'lines.item', 'purchaseReturn', 'purchaseInvoice'])->findOrFail($id);
        return Excel::download(new PurchaseNoteExport($note), "avoir_{$note->numdoc}.xlsx");
    }

    public function exportNotes(Request $request)
    {
        $filters = $request->only(['supplier_id', 'status', 'type', 'date_from', 'date_to']);
        return Excel::download(new PurchaseNotesExport($filters), 'avoirs_achat.xlsx');
    }

    public function printSingleNote($id)
    {
        $note = PurchaseNote::with(['supplier', 'lines.item', 'purchaseReturn', 'purchaseInvoice'])->findOrFail($id);
        $company = CompanyInformation::first() ?? new CompanyInformation([
            'name' => 'Test Company S.A.R.L',
            'address' => '123 Rue Fictive, Tunis 1000',
            'phone' => '+216 12 345 678',
            'email' => 'contact@testcompany.com',
            'matricule_fiscal' => '1234567ABC000',
            'swift' => 'TESTTNTT',
            'rib' => '123456789012',
            'iban' => 'TN59 1234 5678 9012 3456 7890',
            'logo_path' => 'assets/img/test_logo.png',
        ]);

        $generator = new BarcodeGeneratorPNG();
    $barcode = 'data:image/png;base64,' . base64_encode(
        $generator->getBarcode($note->numdoc, $generator::TYPE_CODE_128)
    );

        $pdf = PDF::loadView('pdf.purchase_note', compact('note', 'company','barcode'));
        return $pdf->stream("avoir_{$note->numdoc}.pdf");
    }



    public function searchInvoices(Request $request)
    {
        $svc = app(PurchaseDocumentService::class);
        $query = PurchaseInvoice::where('status', 'validée')->with(['supplier']);
        if ($request->filled('supplier_id')) $query->where('supplier_id', $request->input('supplier_id'));
        if ($request->filled('term')) $query->where('numdoc', 'like', '%' . $request->input('term') . '%');

        $out = [];
        foreach ($query->orderByDesc('id')->limit(100)->get() as $invoice) {
            $lines = collect($svc->creditableLines('from_invoice', [$invoice->id]))->filter(fn ($l) => $l['available'] > 0)->map(fn ($l) => [
                'article_code' => $l['code'], 'item_name' => $l['name'], 'quantity' => $l['available'], 'unit_price_ht' => $l['pu'], 'remise' => $l['remise'], 'tva' => $l['tva'],
            ])->values()->all();
            if (!$lines) continue;
            $out[] = ['id' => $invoice->id, 'numdoc' => $invoice->numdoc, 'invoice_date' => $invoice->invoice_date, 'supplier_name' => $invoice->supplier->name ?? 'N/A',
                      'tva_rate' => $invoice->tva_rate ?? 0, 'lines' => $lines];
        }
        return response()->json($out);
    }




    public function searchReturns(Request $request)
    {
        $svc = app(PurchaseDocumentService::class);
        $query = PurchaseReturn::where('invoiced', 0)->where(function ($q) { $q->whereNull('status')->orWhere('status', '!=', 'annulée'); })->with(['supplier']);
        if ($request->filled('supplier_id')) $query->where('supplier_id', $request->input('supplier_id'));
        if ($request->filled('term')) {
            $term = $request->input('term');
            $query->where(function ($q) use ($term) {
                $q->where('numdoc', 'like', '%' . $term . '%')->orWhereHas('supplier', function ($q) use ($term) { $q->where('name', 'like', '%' . $term . '%'); });
            });
        }

        $out = [];
        foreach ($query->orderByDesc('id')->limit(100)->get() as $return) {
            $lines = collect($svc->creditableLines('from_return', [$return->id]))->filter(fn ($l) => $l['available'] > 0)->map(fn ($l) => [
                'article_code' => $l['code'], 'item_name' => $l['name'], 'returned_quantity' => $l['available'], 'unit_price_ht' => $l['pu'], 'remise' => $l['remise'], 'tva' => $l['tva'],
            ])->values()->all();
            if (!$lines) continue;
            $out[] = ['id' => $return->id, 'numdoc' => $return->numdoc, 'return_date' => $return->return_date, 'supplier_name' => $return->supplier->name ?? 'N/A',
                      'tva_rate' => $return->tva_rate ?? 0, 'type' => $return->type, 'lines' => $lines];
        }
        return response()->json($out);
    }






            public function markAsShipped(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $deliveryNote = PurchaseOrder::findOrFail($id);

            $deliveryNote->update(['status_livraison' => 'Reçu']);

            return redirect("/purchases/list")
                ->with('success', 'Bon de livraison validé avec succès.');
        });
    }








    // route joindre factures fournisseurs

    public function uploadSupplierInvoice(Request $request, $id)
    {
        $request->validate([
            'supplier_invoice_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max, allow PDF and images
        ]);

        $invoice = PurchaseInvoice::findOrFail($id);

        if ($invoice->supplier_invoice_file) {
            Storage::delete($invoice->supplier_invoice_file);
        }

        $file = $request->file('supplier_invoice_file');
        $fileName = 'supplier_invoices/purchase_invoice_' . $invoice->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public', $fileName);

        $invoice->update(['supplier_invoice_file' => $path]);

        Log::info('Supplier invoice uploaded', [
            'purchase_invoice_id' => $invoice->id,
            'file_path' => $path,
        ]);

        return redirect()->route('invoices.list')
            ->with('success', 'Facture fournisseur jointe avec succès.');
    }

    public function downloadSupplierInvoice($id)
    {
        $invoice = PurchaseInvoice::findOrFail($id);

        if (!$invoice->supplier_invoice_file || !Storage::exists($invoice->supplier_invoice_file)) {
            Log::warning('Supplier invoice file not found', ['purchase_invoice_id' => $invoice->id]);
            return redirect()->route('invoices.list')
                ->with('error', 'Fichier de la facture fournisseur non trouvé.');
        }

        return Storage::download($invoice->supplier_invoice_file, 'facture_fournisseur_' . $invoice->numdoc . '.' . pathinfo($invoice->supplier_invoice_file, PATHINFO_EXTENSION));
    }

    public function deleteSupplierInvoice($id)
    {
        $invoice = PurchaseInvoice::findOrFail($id);

        if ($invoice->supplier_invoice_file && Storage::exists($invoice->supplier_invoice_file)) {
            Storage::delete($invoice->supplier_invoice_file);
            $invoice->update(['supplier_invoice_file' => null]);

            Log::info('Supplier invoice deleted', ['purchase_invoice_id' => $invoice->id]);
        }

        return redirect()->route('invoices.list')
            ->with('success', 'Facture fournisseur supprimée avec succès.');
    }

    

    


    
}
