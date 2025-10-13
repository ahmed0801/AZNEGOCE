<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Reception;
use App\Models\ReceptionLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceptionController extends Controller
{
 // Afficher le formulaire de réception
    public function create(Request $request)
    {
        $orderId = $request->query('order_id');
        $next = $request->query('next');

        $order = PurchaseOrder::with('lines.item', 'supplier')->findOrFail($orderId);

        return view('receptioncreate', compact('order', 'next'));
    }

    // Enregistrer la réception
    public function store(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'reception_date' => 'required|date',
            'lines' => 'required|array',
            'lines.*.article_code' => 'required|string',
            'lines.*.received_quantity' => 'required|numeric|min:0',
        ]);

        $reception = Reception::create([
            'purchase_order_id' => $request->purchase_order_id,
            'reception_date' => $request->reception_date,
            'status' => 'reçu',
            'total_received' => 0,
        ]);

        $totalReceived = 0;
        foreach ($request->lines as $line) {
            ReceptionLine::create([
                'reception_id' => $reception->id,
                'article_code' => $line['article_code'],
                'received_quantity' => $line['received_quantity'],
            ]);
            $totalReceived += $line['received_quantity'];
        }

        $reception->update(['total_received' => $totalReceived]);

        // Si on a passé next=invoice, on redirige vers la création de facture
        if ($request->next === 'invoice') {
            return redirect()->route('purchase_invoices.create', ['reception_id' => $reception->id]);
        }

        return redirect()->route('purchases.list')->with('success', 'Réception enregistrée avec succès.');
    }


    public function show($id)
{
    // Récupérer la réception avec sa commande et ses lignes
    $reception = Reception::with(['purchaseOrder', 'lines.item'])->findOrFail($id);

    return view('receptionshow', compact('reception'));
}








public function edit($id)
{
    $reception = Reception::with(['lines', 'purchaseOrder'])->findOrFail($id);
    return view('receptionedit', compact('reception'));
}

public function update(Request $request, $id)
{
    $reception = Reception::findOrFail($id);

    $data = $request->validate([
        'lines' => 'required|array',
        'lines.*.id' => 'required|exists:reception_lines,id',
        'lines.*.received_quantity' => 'required|integer|min:0',
    ]);

    $allMatched = true;
    $totalReceived = 0;

    foreach ($data['lines'] as $lineData) {
        $line = ReceptionLine::findOrFail($lineData['id']);
        $line->received_quantity = $lineData['received_quantity'];
        $line->save();

        $totalReceived += $lineData['received_quantity'];

        // Trouver la quantité commandée pour cet article
        $orderedQuantity = $reception->purchaseOrder->lines
            ->firstWhere('article_code', $line->article_code)
            ->ordered_quantity ?? 0;

        if ($lineData['received_quantity'] != $orderedQuantity) {
            $allMatched = false;
        }
    }

    $reception->total_received = $totalReceived;
    $reception->status = $allMatched ? 'reçu' : 'partiel';
    $reception->save();

    // Redirige vers la liste des commandes
    return redirect()->route('receptions.show', $reception->id)->with('success', 'Réception mise à jour avec succès.');
}










public function scan($id)
    {
        $reception = Reception::with(['lines.item', 'purchaseOrder.supplier', 'purchaseOrder.lines'])->findOrFail($id);

        // Prepare article quantities for the view
        $articleQuantities = $reception->lines->mapWithKeys(function ($line) use ($reception) {
            $orderedQuantity = $reception->purchaseOrder && $reception->purchaseOrder->lines
                ? ($reception->purchaseOrder->lines->firstWhere('article_code', $line->article_code)->ordered_quantity ?? 0)
                : 0;
            return [$line->article_code => [
                'name' => $line->item ? $line->item->name : 'Non disponible',
                'demanded' => $orderedQuantity,
                'scanned' => $line->received_quantity,
                'remaining' => max(0, $orderedQuantity - $line->received_quantity),
            ]];
        })->toArray();

        // Log for debugging
        Log::info('Article Quantities for Reception', [
            'reception_id' => $id,
            'article_quantities' => $articleQuantities,
        ]);

        return view('receptions.scan', compact('reception', 'articleQuantities'));
    }

    public function scanReception(Request $request, $id)
    {
        $reception = Reception::with(['lines', 'purchaseOrder'])->findOrFail($id);

        // Preprocess validate_only to handle string inputs
        $validateOnly = filter_var($request->input('validate_only', false), FILTER_VALIDATE_BOOLEAN);

        // Log raw request data for debugging
        Log::info('Scan Reception Request', [
            'reception_id' => $id,
            'request_data' => $request->all(),
            'validate_only_raw' => $request->input('validate_only'),
            'validate_only_processed' => $validateOnly,
        ]);

        $data = $request->validate([
            'reception_id' => 'required|exists:receptions,id',
            'code_article' => 'required|string',
            'quantite' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'validate_only' => 'nullable|boolean',
        ]);

        $line = $reception->lines->firstWhere('article_code', $data['code_article']);
        if (!$line) {
            return response()->json([
                'valid' => false,
                'message' => 'Article non trouvé dans la réception.',
            ], 422);
        }

        $orderedQuantity = $reception->purchaseOrder->lines
            ->firstWhere('article_code', $data['code_article'])
            ->ordered_quantity ?? 0;

        if ($line->received_quantity >= $orderedQuantity) {
            return response()->json([
                'valid' => false,
                'message' => 'Quantité scannée dépasse la quantité demandée.',
            ], 422);
        }

        if ($validateOnly) {
            return response()->json(['valid' => true]);
        }

        // Update the line
        $line->received_quantity += $data['quantite'];
        $line->save();

        // Update total received
        $totalReceived = $reception->lines->sum('received_quantity');
        $reception->total_received = $totalReceived;

        // Check if all quantities match
        $allMatched = $reception->lines->every(function ($line) use ($reception) {
            $orderedQuantity = $reception->purchaseOrder->lines
                ->firstWhere('article_code', $line->article_code)
                ->ordered_quantity ?? 0;
            return $line->received_quantity >= $orderedQuantity;
        });

        $reception->status = $allMatched ? 'reçu' : 'partiel';
        $reception->save();

        // Prepare response data
        $articleQuantities = $reception->lines->mapWithKeys(function ($line) use ($reception) {
            return [$line->article_code => [
                'name' => $line->item->name ?? 'Non disponible',
                'demanded' => $reception->purchaseOrder->lines->firstWhere('article_code', $line->article_code)->ordered_quantity ?? 0,
                'scanned' => $line->received_quantity,
                'remaining' => max(0, ($reception->purchaseOrder->lines->firstWhere('article_code', $line->article_code)->ordered_quantity ?? 0) - $line->received_quantity),
            ]];
        })->toArray();

        $scanData = [
            'code_article' => $data['code_article'],
            'remaining_quantity' => max(0, $orderedQuantity - $line->received_quantity),
            'article_completed' => $line->received_quantity >= $orderedQuantity,
            'document_completed' => $allMatched,
        ];

        Log::info('Reception scanned and updated', [
            'reception_id' => $reception->id,
            'article_code' => $data['code_article'],
            'quantity' => $data['quantite'],
        ]);

        return response()->json([
            'success' => true,
            'article_quantities' => $articleQuantities,
            'scanned_quantity' => $totalReceived,
            'scan_data' => $scanData,
        ]);
    }

    public function generatePdf($id)
    {
        $reception = Reception::with(['purchaseOrder.supplier', 'lines.item'])->findOrFail($id);

        $data = [
            'reception' => $reception,
            'date' => \Carbon\Carbon::parse($reception->reception_date)->format('d/m/Y'),
            'supplier' => $reception->purchaseOrder->supplier,
        ];

        $pdf = Pdf::loadView('receptions.pdf', $data);
        $fileName = 'reception_' . $reception->id . '_' . time() . '.pdf';
        $path = 'receptions/' . $fileName;
        Storage::disk('public')->put($path, $pdf->output());

        Log::info('Reception PDF generated', ['reception_id' => $reception->id, 'file_path' => $path]);

        return redirect(Storage::disk('public')->url($path));
    }
}
