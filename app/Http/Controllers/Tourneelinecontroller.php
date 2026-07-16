<?php

namespace App\Http\Controllers;

use App\Services\TourneeService;
use App\Models\Invoice;
use App\Models\DeliveryNote;
use App\Models\Supplier;
use App\Models\Item;
use Illuminate\Http\Request;

class TourneeLineController extends Controller
{
    protected $tournee;

    public function __construct(TourneeService $tournee)
    {
        $this->tournee = $tournee;
    }

    // ── GET /tournee/chauffeurs ───────────────────────────────
    // Retourne la liste des chauffeurs pour le select du modal
    public function chauffeurs()
    {
        $chauffeurs = $this->tournee->getChauffeurs();
        return response()->json($chauffeurs);
    }

    // ── GET /tournee/lines/{invoiceId} ────────────────────────
    // Retourne les lignes de tournée d'une facture
    // ── GET /tournee/parametres ───────────────────────────────
    // Appelée par le modal JS pour récupérer les créneaux du jour
    public function getParametres(Request $request)
    {
        $date   = $request->input('date', today()->toDateString());
        $result = $this->tournee->getParametres($date);
        return response()->json($result);
    }

    // ── GET /tournee/lines/{id} ───────────────────────────────
    public function linesForInvoice(Request $request, $invoiceId)
    {
        $sourceType = $request->input('source_type', 'facture_vente');
        $lines = $this->tournee->getLinesForSource($invoiceId, $sourceType);
        return response()->json($lines);
    }

    // ── POST /tournee/lines ───────────────────────────────────
    // Le vendeur ajoute une ligne depuis la page facture
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id'      => 'required|integer',
            'invoice_line_id' => 'nullable|integer',
            'article_code'    => 'required|string',
            'article_name'    => 'required|string',
            'quantity'        => 'required|numeric|min:0.01',
            'supplier_id'     => 'nullable|integer|exists:suppliers,id',
            'chauffeur_id'    => 'nullable|integer',
            'date_tournee'    => 'required|date',
            'slot' => 'required|string|max:50',
            'notes'           => 'nullable|string|max:500',
        ]);

        $sourceType = $request->input('source_type', 'facture_vente');

        if ($sourceType === 'bl') {
            $doc = \App\Models\DeliveryNote::find($request->invoice_id);
        } else {
            $doc = Invoice::find($request->invoice_id);
        }

        if (!$doc) {
            return response()->json(['success' => false, 'error' => 'Document introuvable'], 404);
        }

        $supplier = $request->supplier_id
            ? Supplier::find($request->supplier_id)
            : null;

        $item    = Item::where('code', $request->article_code)->first();
        $barcode = $item ? $item->barcode : null;

        $result = $this->tournee->addLine([
            'source_type'           => $sourceType,
            'source_id'             => $doc->id,
            'source_numdoc'         => $doc->numdoc,
            'source_line_id'        => $request->invoice_line_id,
            'article_code'          => $request->article_code,
            'article_name'          => $request->article_name,
            'quantity'              => $request->quantity,
            'barcode'               => $barcode,
            'fournisseur_remote_id' => $supplier ? $supplier->id : null,
            'fournisseur_name'      => $supplier ? $supplier->name : null,
            'chauffeur_id'          => $request->chauffeur_id ?: null,
            'date_tournee'          => $request->date_tournee,
            'slot'                  => $request->slot,
            'notes'                 => $request->notes,
            'created_by_name'       => auth()->user()->name,
        ]);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Ajouté à la tournée du '
                    . \Carbon\Carbon::parse($request->date_tournee)->format('d/m/Y')
                    . ' (' . ($request->slot === 'matin' ? '🌅 Matin' : '🌇 Après-midi') . ')',
                'line_id' => $result['data']['line_id'],
            ]);
        }

        return response()->json([
            'success' => false,
            'error'   => $result['error'],
        ], 500);
    }

    // ── DELETE /tournee/lines/{id} ────────────────────────────
    public function destroy($lineId)
    {
        $deleted = $this->tournee->deleteLine($lineId);
        return response()->json(['success' => $deleted]);
    }

    // ── POST /api/articles/update-barcode ─────────────────────
    // Reçu depuis le hub tournée quand un chauffeur associe un barcode
    public function updateBarcode(Request $request)
    {
        $apiKey = $request->header('X-API-KEY');
        if ($apiKey !== config('services.tournee.key')) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $request->validate([
            'article_code' => 'required|string',
            'barcode'      => 'required|string',
        ]);

        $item = Item::where('code', $request->article_code)->first();
        if (!$item) {
            return response()->json(['error' => 'Article non trouvé'], 404);
        }

        $item->update(['barcode' => $request->barcode]);

        \Log::info('Barcode mis à jour par tournée: ' . $request->article_code . ' → ' . $request->barcode);

        return response()->json(['success' => true]);
    }

    // ── POST /tournee/sync-fournisseurs ───────────────────────
    // Synchroniser tous les fournisseurs vers le hub (appel manuel ou cron)
    public function syncFournisseurs()
    {
        $fournisseurs = Supplier::select('id', 'name', 'address', 'city', 'phone')
            ->orderBy('name')
            ->get()
            ->map(function ($s) {
                return [
                    'id'      => $s->id,
                    'name'    => $s->name,
                    'address' => $s->address ?? null,
                    'city'    => $s->city ?? null,
                    'phone'   => $s->phone ?? null,
                ];
            })
            ->toArray();

        $result = $this->tournee->syncFournisseurs($fournisseurs);

        return response()->json([
            'success' => $result,
            'synced'  => count($fournisseurs),
        ]);
    }
}