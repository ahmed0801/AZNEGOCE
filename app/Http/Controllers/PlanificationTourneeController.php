<?php

namespace App\Http\Controllers;



use App\Models\DeliveryNote;
use App\Models\PurchaseOrder;
use App\Models\PlanificationTournee;
use App\Models\PlanificationTourneeDocument;
use App\Models\ActionTournee;
use App\Models\DeliveryNoteLine;
use App\Models\PurchaseOrderLine;
use App\Models\User;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PlanificationTourneeController extends Controller
{
    public function index(Request $request)
{
    $query = PlanificationTournee::with([
        'utilisateur',
        'commandesAchats.supplier',
        'commandesAchats.lines.item',
        'bonsLivraisons.customer',
        'bonsLivraisons.lines.item'
    ]);

    // Set default filter to today's date if no date_debut is provided
    $dateDebut = $request->filled('date_debut') ? $request->date_debut : Carbon::today()->toDateString();
    $dateFin = $request->filled('date_fin') ? $request->date_fin : Carbon::today()->toDateString();

    // Apply date filters
    $query->whereDate('datetime_planifie', '>=', $dateDebut);
    $query->whereDate('datetime_planifie', '<=', $dateFin);

    // Apply chauffeur filter if provided
    if ($request->filled('chauffeur_id')) {
        $query->where('user_id', $request->chauffeur_id);
    }

    $planifications = $query->orderBy('datetime_planifie')->get();
    $chauffeurs = User::where('role', 'livreur')->get();

    // Pass default dates to the view
    return view('planification_tournee.index', compact('planifications', 'chauffeurs', 'dateDebut', 'dateFin'));
}

    public function create()
    {
        $utilisateurs = User::where('role', 'livreur')->get();
        $commandesAchats = PurchaseOrder::where('status_livraison', '!=', 'récuperée')
            ->whereHas('supplier')
            ->with(['supplier', 'lines.item'])
            ->latest()
            ->get();
        $bonsLivraisons = DeliveryNote::where('status_livraison', '!=', 'livré')
            ->whereHas('customer')
            ->with(['customer', 'lines.item'])
            ->latest()
            ->get();

        return view('planification_tournee.create', compact('utilisateurs', 'commandesAchats', 'bonsLivraisons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'datetime_planifie' => 'required|date|after_or_equal:now',
            'commande_achat_ids' => 'nullable|array',
            'commande_achat_ids.*' => 'exists:purchase_orders,id',
            'bon_livraison_ids' => 'nullable|array',
            'bon_livraison_ids.*' => 'exists:delivery_notes,id',
            'notes' => 'nullable|string',
        ], [
            'user_id.required' => 'Veuillez sélectionner un chauffeur.',
            'datetime_planifie.required' => 'Veuillez sélectionner une date et heure.',
            'datetime_planifie.after_or_equal' => 'La date et l\'heure doivent être maintenant ou ultérieures.',
        ]);

        if (empty($request->commande_achat_ids) && empty($request->bon_livraison_ids)) {
            return back()->withErrors(['error' => 'Vous devez sélectionner au moins une commande d\'achat ou un bon de livraison.']);
        }

        DB::transaction(function () use ($request) {
            $planification = PlanificationTournee::create([
                'user_id' => $request->user_id,
                'datetime_planifie' => $request->datetime_planifie,
                'statut' => 'planifié',
                'notes' => $request->notes,
            ]);

            if (!empty($request->commande_achat_ids)) {
                foreach ($request->commande_achat_ids as $id) {
                    PlanificationTourneeDocument::create([
                        'planification_tournee_id' => $planification->id,
                        'document_type' => 'commande_achat',
                        'document_id' => $id,
                    ]);
                }
            }

            if (!empty($request->bon_livraison_ids)) {
                foreach ($request->bon_livraison_ids as $id) {
                    PlanificationTourneeDocument::create([
                        'planification_tournee_id' => $planification->id,
                        'document_type' => 'bon_livraison',
                        'document_id' => $id,
                    ]);
                }
            }
        });

        return redirect()->route('planification.tournee.index')->with('success', 'Tournée planifiée avec succès.');
    }

    public function edit($id)
    {
        $planification = PlanificationTournee::with(['commandesAchats', 'bonsLivraisons'])->findOrFail($id);
        if ($planification->isValidee()) {
            return back()->withErrors(['error' => 'Une tournée validée ne peut pas être modifiée.']);
        }
        $utilisateurs = User::where('role', 'livreur')->get();
        $commandesAchats = PurchaseOrder::where('status_livraison', '!=', 'récuperée')
            ->whereHas('supplier')
            ->with(['supplier', 'lines.item'])
            ->latest()
            ->get();
        $bonsLivraisons = DeliveryNote::where('status_livraison', '!=', 'livré')
            ->whereHas('customer')
            ->with(['customer', 'lines.item'])
            ->latest()
            ->get();
            

        return view('planification_tournee.edit', compact('planification', 'utilisateurs', 'commandesAchats', 'bonsLivraisons'));
    }

    public function update(Request $request, $id)
    {
        $planification = PlanificationTournee::findOrFail($id);
        if ($planification->isValidee()) {
            return back()->withErrors(['error' => 'Une tournée validée ne peut pas être modifiée.']);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'datetime_planifie' => 'required|date|after_or_equal:now',
            'commande_achat_ids' => 'nullable|array',
            'commande_achat_ids.*' => 'exists:purchase_orders,id',
            'bon_livraison_ids' => 'nullable|array',
            'bon_livraison_ids.*' => 'exists:delivery_notes,id',
            'notes' => 'nullable|string',
        ], [
            'user_id.required' => 'Veuillez sélectionner un chauffeur.',
            'datetime_planifie.required' => 'Veuillez sélectionner une date et heure.',
            'datetime_planifie.after_or_equal' => 'La date et l\'heure doivent être maintenant ou ultérieures.',
        ]);

        if (empty($request->commande_achat_ids) && empty($request->bon_livraison_ids)) {
            return back()->withErrors(['error' => 'Vous devez sélectionner au moins une commande d\'achat ou un bon de livraison.']);
        }

        DB::transaction(function () use ($request, $id) {
            $planification = PlanificationTournee::findOrFail($id);
            $planification->update([
                'user_id' => $request->user_id,
                'datetime_planifie' => $request->datetime_planifie,
                'notes' => $request->notes,
            ]);

            PlanificationTourneeDocument::where('planification_tournee_id', $id)->delete();

            if (!empty($request->commande_achat_ids)) {
                foreach ($request->commande_achat_ids as $ca_id) {
                    PlanificationTourneeDocument::create([
                        'planification_tournee_id' => $planification->id,
                        'document_type' => 'commande_achat',
                        'document_id' => $ca_id,
                    ]);
                }
            }
            if (!empty($request->bon_livraison_ids)) {
                foreach ($request->bon_livraison_ids as $id) {
                    PlanificationTourneeDocument::create([
                        'planification_tournee_id' => $planification->id,
                        'document_type' => 'bon_livraison',
                        'document_id' => $id,
                    ]);
                }
            }
        });

        return redirect()->route('planification.tournee.index')->with('success', 'Tournée mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $planification = PlanificationTournee::findOrFail($id);
        if ($planification->isValidee()) {
            return back()->withErrors(['error' => 'Une tournée validée ne peut pas être supprimée.']);
        }
        if ($planification->statut !== 'planifié') {
            return back()->withErrors(['error' => 'Seules les tournées planifiées peuvent être supprimées.']);
        }

        DB::transaction(function () use ($planification) {
            PlanificationTourneeDocument::where('planification_tournee_id', $planification->id)->delete();
            $planification->delete();
        });

        return redirect()->route('planification.tournee.index')->with('success', 'Tournée supprimée avec succès.');
    }



    
    
    public function planningChauffeur(Request $request)
{
    $aujourdHui = Carbon::today();
    $hier = Carbon::yesterday();
    $demain = Carbon::tomorrow();

    // Align with index method: use full eager loading without field restrictions
    $planifications = PlanificationTournee::select([
        'id', 'user_id', 'datetime_planifie', 'statut', 'notes', 'validee_at'
    ])
        ->where('user_id', Auth::id())
        ->whereNull('validee_at')
        ->whereDate('datetime_planifie', '>=', $hier)
        ->whereDate('datetime_planifie', '<=', $demain)
        ->with([
            'commandesAchats.supplier', // No field restrictions
            'commandesAchats.lines.item',
            'bonsLivraisons.customer',  // No field restrictions
            'bonsLivraisons.lines.item',
            'actions' => function ($query) {
                $query->select([
                    'id', 'planification_tournee_id', 'type_document', 'document_id',
                    'code_article', 'quantite', 'notes', 'created_at'
                ]);
            }
        ])
        ->orderBy('datetime_planifie')
        ->get();

    // Debug: Log supplier and customer data to identify null relationships
    foreach ($planifications as $plan) {
        foreach ($plan->commandesAchats as $ca) {
            Log::debug('Commande Achat', [
                'planification_id' => $plan->id,
                'commande_achat_id' => $ca->id,
                'supplier_id' => $ca->supplier_id,
                'supplier' => $ca->supplier ? [
                    'id' => $ca->supplier->id,
                    'name' => $ca->supplier->name,
                    'address' => $ca->supplier->address,
                    'address_delivery' => $ca->supplier->address_delivery
                ] : null
            ]);
        }
        foreach ($plan->bonsLivraisons as $bonLivraison) {
            Log::debug('Bon de Livraison', [
                'planification_id' => $plan->id,
                'bon_livraison_id' => $bonLivraison->id,
                'customer_id' => $bonLivraison->customer_id,
                'customer' => $bonLivraison->customer ? [
                    'id' => $bonLivraison->customer->id,
                    'name' => $bonLivraison->customer->name,
                    'address' => $bonLivraison->customer->address,
                    'address_delivery' => $bonLivraison->customer->address_delivery
                ] : null
            ]);
        }
    }

    Log::info('Tournées chargées pour planningChauffeur', [
        'user_id' => Auth::id(),
        'hier' => $hier->toDateString(),
        'aujourdHui' => $aujourdHui->toDateString(),
        'demain' => $demain->toDateString(),
        'planification_count' => $planifications->count()
    ]);

    // Calculate scanned quantities and article details
    $scannedQuantities = [];
    $articleQuantities = [];
    foreach ($planifications as $plan) {
        foreach ($plan->commandesAchats as $ca) {
            $scannedQuantities[$ca->id] = $plan->actions
                ->where('type_document', 'commande_achat')
                ->where('document_id', $ca->id)
                ->sum('quantite');
            $articleQuantities[$ca->id] = [];
            foreach ($ca->lines as $line) {
                $scanned = $plan->actions
                    ->where('type_document', 'commande_achat')
                    ->where('document_id', $ca->id)
                    ->where('code_article', $line->article_code)
                    ->sum('quantite');
                $articleQuantities[$ca->id][$line->article_code] = [
                    'demanded' => $line->ordered_quantity,
                    'scanned' => $scanned,
                    'remaining' => max(0, $line->ordered_quantity - $scanned),
                    'name' => $line->item->name ?? 'Non disponible'
                ];
            }
        }
        foreach ($plan->bonsLivraisons as $bonLivraison) {
            $scannedQuantities[$bonLivraison->id] = $plan->actions
                ->where('type_document', 'bon_livraison')
                ->where('document_id', $bonLivraison->id)
                ->sum('quantite');
            $articleQuantities[$bonLivraison->id] = [];
            foreach ($bonLivraison->lines as $line) {
                $scanned = $plan->actions
                    ->where('type_document', 'bon_livraison')
                    ->where('document_id', $bonLivraison->id)
                    ->where('code_article', $line->article_code)
                    ->sum('quantite');
                $articleQuantities[$bonLivraison->id][$line->article_code] = [
                    'demanded' => $line->delivered_quantity,
                    'scanned' => $scanned,
                    'remaining' => max(0, $line->delivered_quantity - $scanned),
                    'name' => $line->item->name ?? 'Non disponible'
                ];
            }
        }
    }

    return view('planification_tournee.planning_chauffeur', compact(
        'planifications',
        'aujourdHui',
        'scannedQuantities',
        'articleQuantities'
    ));
}




    public function scan(Request $request)
    {
        try {
            Log::info('Requête scan reçue', [
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);

            if ($request->has('validate_only') && $request->has('code_article')) {
                $code_article = trim($request->code_article);
                $document_id = $request->document_id;
                $document_type = $request->document_type;

                Log::info('Validation de l\'article', [
                    'code_article' => $code_article,
                    'document_id' => $document_id,
                    'document_type' => $document_type
                ]);

                $hier = Carbon::yesterday();
                $aujourdHui = Carbon::today();
                $demain = Carbon::tomorrow();

                $planifications = PlanificationTournee::where('user_id', Auth::id())
                    ->whereNull('validee_at')
                    ->whereDate('datetime_planifie', '>=', $hier)
                    ->whereDate('datetime_planifie', '<=', $demain)
                    ->get();

                if ($planifications->isEmpty()) {
                    Log::warning('Aucune tournée prévue', [
                        'user_id' => Auth::id(),
                        'hier' => $hier->toDateString(),
                        'aujourdHui' => $aujourdHui->toDateString(),
                        'demain' => $demain->toDateString()
                    ]);
                    return response()->json([
                        'valid' => false,
                        'message' => 'Aucune tournée non validée prévue pour hier, aujourd\'hui ou demain.'
                    ], 422);
                }

                $document = null;
                $planification = null;
                foreach ($planifications as $plan) {
                    $document = PlanificationTourneeDocument::where('planification_tournee_id', $plan->id)
                        ->where('document_type', $document_type)
                        ->where('document_id', $document_id)
                        ->first();
                    if ($document) {
                        $planification = $plan;
                        break;
                    }
                }

                if (!$document) {
                    Log::warning('Document non associé à la tournée', [
                        'document_id' => $document_id,
                        'document_type' => $document_type
                    ]);
                    return response()->json([
                        'valid' => false,
                        'message' => 'Document non associé à la tournée.'
                    ], 422);
                }

                $model = $document_type === 'commande_achat' ? PurchaseOrder::find($document_id) : DeliveryNote::find($document_id);
                if (!$model) {
                    Log::warning('Document introuvable', [
                        'document_id' => $document_id,
                        'document_type' => $document_type
                    ]);
                    return response()->json([
                        'valid' => false,
                        'message' => 'Document introuvable.'
                    ], 422);
                }

                $line = $model->lines()->where('article_code', $code_article)->first();
                if (!$line) {
                    Log::warning('Article introuvable dans le document', [
                        'code_article' => $code_article,
                        'document_id' => $document_id,
                        'document_type' => $document_type
                    ]);
                    return response()->json([
                        'valid' => false,
                        'message' => 'Article introuvable dans le document.'
                    ], 422);
                }

                Log::info('Article validé avec succès', [
                    'code_article' => $code_article,
                    'document_id' => $document_id
                ]);
                return response()->json(['valid' => true]);
            }

            $request->validate([
                'document_id' => 'required|integer',
                'document_type' => 'required|in:commande_achat,bon_livraison',
                'code_article' => 'required|string',
                'quantite' => 'nullable|integer|min:0',
                'notes' => 'nullable|string',
            ], [
                'document_id.required' => 'L\'ID du document est requis.',
                'document_type.required' => 'Le type de document est requis.',
                'code_article.required' => 'Le code article est requis.',
                'quantite.integer' => 'La quantité doit être un entier.',
                'quantite.min' => 'La quantité ne peut pas être négative.'
            ]);

            $document_id = $request->document_id;
            $document_type = $request->document_type;
            $code_article = trim($request->code_article);
            $quantite = $request->quantite ?? 1;

            $hier = Carbon::yesterday();
            $aujourdHui = Carbon::today();
            $demain = Carbon::tomorrow();

            $planifications = PlanificationTournee::where('user_id', Auth::id())
                ->whereNull('validee_at')
                ->whereDate('datetime_planifie', '>=', $hier)
                ->whereDate('datetime_planifie', '<=', $demain)
                ->get();

            if ($planifications->isEmpty()) {
                Log::warning('Aucune tournée prévue', [
                    'user_id' => Auth::id(),
                    'hier' => $hier->toDateString(),
                    'aujourdHui' => $aujourdHui->toDateString(),
                    'demain' => $demain->toDateString()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune tournée non validée prévue pour hier, aujourd\'hui ou demain.'
                ], 422);
            }

            $document = null;
            $planification = null;
            foreach ($planifications as $plan) {
                $document = PlanificationTourneeDocument::where('planification_tournee_id', $plan->id)
                    ->where('document_type', $document_type)
                    ->where('document_id', $document_id)
                    ->first();
                if ($document) {
                    $planification = $plan;
                    break;
                }
            }

            if (!$document) {
                Log::warning('Document non associé à la tournée', [
                    'document_id' => $document_id,
                    'document_type' => $document_type
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Document non associé à la tournée.'
                ], 422);
            }

            Log::info('Vérification du document', [
                'planification_id' => $planification->id,
                'document_id' => $document_id,
                'document_type' => $document_type
            ]);

            $model = $document_type === 'commande_achat' ? PurchaseOrder::find($document_id) : DeliveryNote::find($document_id);
            if (!$model) {
                Log::warning('Document introuvable', [
                    'document_id' => $document_id,
                    'document_type' => $document_type
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Document introuvable.'
                ], 422);
            }

            $line = $model->lines()->where('article_code', $code_article)->first();
            if (!$line) {
                Log::warning('Article introuvable dans le document', [
                    'code_article' => $code_article,
                    'document_id' => $document_id,
                    'document_type' => $document_type
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Article introuvable dans le document.'
                ], 422);
            }

            // Check if the scanned quantity would exceed the demanded quantity
            $demanded_quantity = $document_type === 'commande_achat' ? $line->ordered_quantity : $line->delivered_quantity;
            $current_scanned = ActionTournee::where('planification_tournee_id', $planification->id)
                ->where('type_document', $document_type)
                ->where('document_id', $document_id)
                ->where('code_article', $code_article)
                ->sum('quantite');

            if ($current_scanned + $quantite > $demanded_quantity) {
                Log::warning('Quantité scannée excède la quantité demandée', [
                    'code_article' => $code_article,
                    'document_id' => $document_id,
                    'document_type' => $document_type,
                    'current_scanned' => $current_scanned,
                    'quantite' => $quantite,
                    'demanded_quantity' => $demanded_quantity
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'La quantité scannée excède la quantité demandée pour cet article.'
                ], 422);
            }

            $remaining_quantity = max(0, $demanded_quantity - ($current_scanned + $quantite));

            DB::transaction(function () use ($planification, $document_type, $document_id, $code_article, $quantite, $request) {
                // Record the scan
                ActionTournee::create([
                    'planification_tournee_id' => $planification->id,
                    'user_id' => Auth::id(),
                    'type_action' => 'scan_article',
                    'type_document' => $document_type,
                    'document_id' => $document_id,
                    'code_article' => $code_article,
                    'quantite' => $quantite,
                    'notes' => $request->notes,
                ]);

                // Check if the article is fully scanned
                $model = $document_type === 'commande_achat' ? PurchaseOrder::find($document_id) : DeliveryNote::find($document_id);
                $line = $model->lines()->where('article_code', $code_article)->first();
                $demanded_quantity = $document_type === 'commande_achat' ? $line->ordered_quantity : $line->delivered_quantity;
                $total_scanned = ActionTournee::where('planification_tournee_id', $planification->id)
                    ->where('type_document', $document_type)
                    ->where('document_id', $document_id)
                    ->where('code_article', $code_article)
                    ->sum('quantite');

                $article_completed = $total_scanned >= $demanded_quantity;

                // Check if all articles in the document are fully scanned
                $all_articles_completed = true;
                foreach ($model->lines as $line) {
                    $line_demanded = $document_type === 'commande_achat' ? $line->ordered_quantity : $line->delivered_quantity;
                    $line_scanned = ActionTournee::where('planification_tournee_id', $planification->id)
                        ->where('type_document', $document_type)
                        ->where('document_id', $document_id)
                        ->where('code_article', $line->article_code)
                        ->sum('quantite');
                    if ($line_scanned < $line_demanded) {
                        $all_articles_completed = false;
                        break;
                    }
                }

                // Update document status_livraison if all articles are completed
                if ($all_articles_completed) {
                    $model->update(['status_livraison' => $document_type === 'commande_achat' ? 'reçu' : 'livré']);
                    Log::info('Document marqué comme terminé (status_livraison)', [
                        'document_id' => $document_id,
                        'document_type' => $document_type,
                        'status_livraison' => $document_type === 'commande_achat' ? 'reçu' : 'livré'
                    ]);
                }

                // Update tour status
                $this->updatePlanificationStatus($planification);

                // Automatically validate the tour if all documents are completed
                if ($planification->statut === 'terminé' && !$planification->isValidee()) {
                    $planification->update(['validee_at' => now()]);
                    $existing_validation = ActionTournee::where('planification_tournee_id', $planification->id)
                        ->where('type_action', 'validation')
                        ->exists();
                    if (!$existing_validation) {
                        ActionTournee::create([
                            'planification_tournee_id' => $planification->id,
                            'user_id' => Auth::id(),
                            'type_action' => 'validation',
                            'type_document' => null,
                            'document_id' => null,
                            'notes' => 'Tournée validée automatiquement après scan complet.',
                        ]);
                        Log::info('Tournée validée automatiquement', [
                            'planification_id' => $planification->id
                        ]);
                    }
                }
            });

            Log::info('Scan enregistré', [
                'planification_id' => $planification->id,
                'user_id' => Auth::id(),
                'document_id' => $document_id,
                'document_type' => $document_type,
                'code_article' => $code_article,
                'quantite' => $quantite
            ]);

            // Calculate scanned quantities and article details for ALL documents in the planification
            $scannedQuantities = [];
            $articleQuantities = [];
            $articleCompleted = false;
            $documentCompleted = false;
            $tourValidated = false;

            // Reload planification with all related documents
            $planification = PlanificationTournee::where('id', $planification->id)
                ->with([
                    'commandesAchats.lines.item',
                    'commandesAchats.supplier',
                    'bonsLivraisons.lines.item',
                    'bonsLivraisons.customer',
                    'actions'
                ])->first();

            // Process commande_achat
            foreach ($planification->commandesAchats as $ca) {
                $scannedQuantities[$ca->id] = $planification->actions
                    ->where('type_document', 'commande_achat')
                    ->where('document_id', $ca->id)
                    ->sum('quantite');
                $articleQuantities[$ca->id] = [];
                foreach ($ca->lines as $line) {
                    $scanned = $planification->actions
                        ->where('type_document', 'commande_achat')
                        ->where('document_id', $ca->id)
                        ->where('code_article', $line->article_code)
                        ->sum('quantite');
                    $articleQuantities[$ca->id][$line->article_code] = [
                        'demanded' => $line->ordered_quantity,
                        'scanned' => $scanned,
                        'remaining' => max(0, $line->ordered_quantity - $scanned),
                        'name' => $line->item->name ?? 'Non disponible'
                    ];
                    if ($ca->id == $document_id && $line->article_code == $code_article && $scanned >= $line->ordered_quantity) {
                        $articleCompleted = true;
                    }
                }
                if ($ca->id == $document_id) {
                    $all_articles_completed = true;
                    foreach ($ca->lines as $line) {
                        $scanned = $planification->actions
                            ->where('type_document', 'commande_achat')
                            ->where('document_id', $ca->id)
                            ->where('code_article', $line->article_code)
                            ->sum('quantite');
                        if ($scanned < $line->ordered_quantity) {
                            $all_articles_completed = false;
                            break;
                        }
                    }
                    if ($all_articles_completed) {
                        $documentCompleted = true;
                    }
                }
            }

            // Process bon_livraison
            foreach ($planification->bonsLivraisons as $bonLivraison) {
                $scannedQuantities[$bonLivraison->id] = $planification->actions
                    ->where('type_document', 'bon_livraison')
                    ->where('document_id', $bonLivraison->id)
                    ->sum('quantite');
                $articleQuantities[$bonLivraison->id] = [];
                foreach ($bonLivraison->lines as $line) {
                    $scanned = $planification->actions
                        ->where('type_document', 'bon_livraison')
                        ->where('document_id', $bonLivraison->id)
                        ->where('code_article', $line->article_code)
                        ->sum('quantite');
                    $articleQuantities[$bonLivraison->id][$line->article_code] = [
                        'demanded' => $line->delivered_quantity,
                        'scanned' => $scanned,
                        'remaining' => max(0, $line->delivered_quantity - $scanned),
                        'name' => $line->item->name ?? 'Non disponible'
                    ];
                    if ($bonLivraison->id == $document_id && $line->article_code == $code_article && $scanned >= $line->delivered_quantity) {
                        $articleCompleted = true;
                    }
                }
                if ($bonLivraison->id == $document_id) {
                    $all_articles_completed = true;
                    foreach ($bonLivraison->lines as $line) {
                        $scanned = $planification->actions
                            ->where('type_document', 'bon_livraison')
                            ->where('document_id', $bonLivraison->id)
                            ->where('code_article', $line->article_code)
                            ->sum('quantite');
                        if ($scanned < $line->delivered_quantity) {
                            $all_articles_completed = false;
                            break;
                        }
                    }
                    if ($all_articles_completed) {
                        $documentCompleted = true;
                    }
                }
            }

            if ($planification->isValidee()) {
                $tourValidated = true;
            }

            // Log the response for debugging
            Log::info('Réponse scan', [
                'document_id' => $document_id,
                'document_type' => $document_type,
                'code_article' => $code_article,
                'article_quantities' => $articleQuantities,
                'scanned_quantities' => $scannedQuantities,
                'scan_data' => [
                    'article_completed' => $articleCompleted,
                    'document_completed' => $documentCompleted,
                    'tour_validated' => $tourValidated,
                    'planification_id' => $planification->id,
                    'remaining_quantity' => $remaining_quantity
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Article scanné avec succès.',
                'scanned_quantities' => $scannedQuantities,
                'article_quantities' => $articleQuantities,
                'scan_data' => [
                    'document_id' => $document_id,
                    'document_type' => $document_type,
                    'code_article' => $code_article,
                    'quantite' => $quantite,
                    'article_completed' => $articleCompleted,
                    'document_completed' => $documentCompleted,
                    'tour_validated' => $tourValidated,
                    'planification_id' => $planification->id,
                    'remaining_quantity' => $remaining_quantity
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors du scan', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Erreur serveur lors du scan : ' . $e->getMessage()
            ], 500);
        }
    }

    protected function updatePlanificationStatus(PlanificationTournee $planification)
    {
        $allDocumentsCompleted = true;

        foreach ($planification->commandesAchats as $ca) {
            if ($ca->status_livraison !== 'reçu') {
                $allDocumentsCompleted = false;
                break;
            }
        }

        foreach ($planification->bonsLivraisons as $bl) {
            if ($bl->status_livraison !== 'livré') {
                $allDocumentsCompleted = false;
                break;
            }
        }

        if ($allDocumentsCompleted) {
            $planification->update(['statut' => 'terminé']);
        }
    }

    public function valider(Request $request, $id)
    {
        try {
            $planification = PlanificationTournee::with(['commandesAchats', 'bonsLivraisons'])->findOrFail($id);

            if ($planification->user_id !== Auth::id()) {
                throw new \Exception('Vous n\'êtes pas autorisé à valider cette tournée.');
            }

            if ($planification->isValidee()) {
                throw new \Exception('Cette tournée est déjà validée.');
            }

            if ($planification->statut !== 'terminé') {
                throw new \Exception('La tournée doit être terminée avant de pouvoir être validée.');
            }

            DB::transaction(function () use ($planification) {
                $planification->update([
                    'validee_at' => now(),
                ]);

                $existing_validation = ActionTournee::where('planification_tournee_id', $planification->id)
                    ->where('type_action', 'validation')
                    ->exists();
                if (!$existing_validation) {
                    ActionTournee::create([
                        'planification_tournee_id' => $planification->id,
                        'user_id' => Auth::id(),
                        'type_action' => 'validation',
                        'type_document' => null,
                        'document_id' => null,
                        'notes' => 'Tournée validée par le chauffeur.',
                    ]);
                }
            });

            return back()->with('success', 'Tournée validée avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function rapport(Request $request)
    {
        $query = ActionTournee::with([
            'planificationTournee.utilisateur',
            'planificationTournee.commandesAchats.supplier',
            'planificationTournee.commandesAchats.lines.item',
            'planificationTournee.bonsLivraisons.customer',
            'planificationTournee.bonsLivraisons.lines.item'
        ]);

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }
        if ($request->filled('chauffeur_id')) {
            $query->whereHas('planificationTournee', function ($q) use ($request) {
                $q->where('user_id', $request->chauffeur_id);
            });
        }
        if ($request->filled('planification_tournee_id')) {
            $query->where('planification_tournee_id', $request->planification_tournee_id);
        }

        $actions = $query->orderByDesc('created_at')->get();
        $chauffeurs = User::where('role', 'livreur')->get();

        return view('planification_tournee.rapport', compact('actions', 'chauffeurs'));
    }

    public function suivi(Request $request)
    {
        $commandesAchats = PurchaseOrder::with(['supplier', 'lines.item']);
        $bonsLivraisons = DeliveryNote::with(['customer', 'lines.item']);

        if ($request->filled('date_debut')) {
            $commandesAchats->whereDate('created_at', '>=', $request->date_debut);
            $bonsLivraisons->whereDate('delivery_date', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $commandesAchats->whereDate('created_at', '<=', $request->date_fin);
            $bonsLivraisons->whereDate('delivery_date', '<=', $request->date_fin);
        }
        if ($request->filled('chauffeur_id')) {
            $commandesAchats->whereHas('planificationTournee', function ($q) use ($request) {
                $q->where('user_id', $request->chauffeur_id);
            });
            $bonsLivraisons->whereHas('planificationTournee', function ($q) use ($request) {
                $q->where('user_id', $request->chauffeur_id);
            });
        }

        $commandesAchats = $commandesAchats->get();
        $bonsLivraisons = $bonsLivraisons->get();
        $chauffeurs = User::where('role', 'livreur')->get();

        return view('planification_tournee.suivi', compact('commandesAchats', 'bonsLivraisons', 'chauffeurs'));
    }

   
}