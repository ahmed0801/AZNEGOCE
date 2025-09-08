<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Reception;
use App\Models\ReceptionLine;
use Illuminate\Http\Request;

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











}
