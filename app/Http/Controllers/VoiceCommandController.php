<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Item;
use App\Models\Supplier;

class VoiceCommandController extends Controller
{
    public function handle(Request $request)
    {
        $command = Str::lower(trim($request->input('command')));

        // Intents & phrases associées
        $intents = [
            'greeting' => [
                'bonjour', 'salut', 'hello', 'hi', 'hey', 'bonjour negobot', 'bonjour negobot'
            ],
            'purchase_suggestion' => [
                'commande achat', 'propose une commande', 'proposition d\'achat',
                'réapprovisionnement', 'fais une commande', 'besoin de stock',
                'bonjour negobot, propose une commande achat selon la rotation de notre stock'
            ],
            'thanks' => [
                'merci', 'thanks', 'merci beaucoup', 'c’est gentil'
            ]
        ];

        // Détection de l'intention
        foreach ($intents as $intent => $phrases) {
            foreach ($phrases as $phrase) {
                if (Str::contains($command, $phrase)) {
                    return $this->respondToIntent($intent);
                }
            }
        }

        return response()->json([
            'status' => '❌ Je n’ai pas compris votre demande.',
            'propositions' => []
        ]);
    }

    private function respondToIntent($intent)
    {
        switch ($intent) {
            case 'greeting':
                return response()->json([
                    'status' => '👋 Bonjour, comment puis-je vous aider ?',
                    'propositions' => []
                ]);

            case 'thanks':
                return response()->json([
                    'status' => '🙏 Avec plaisir !',
                    'propositions' => []
                ]);

            case 'purchase_suggestion':
                return $this->handlePurchaseSuggestion();

            default:
                return response()->json([
                    'status' => '🤖 Intention non reconnue.',
                    'propositions' => []
                ]);
        }
    }

    private function handlePurchaseSuggestion()
    {
        $suggestions = [];

        $stocks = Stock::with('item')->get();

        foreach ($stocks as $stock) {
            if ($stock->quantity < $stock->min_quantity) {
                $item = $stock->item;
                $neededQty = $stock->max_quantity - $stock->quantity;

                $supplier = $item->supplier;
                $lastMovement = $item->stockMovements()->orderByDesc('created_at')->first();

                $suggestions[] = [
                    'article' => $item->name ?? 'Article inconnu',
                    'quantité_suggérée' => $neededQty,
                    'fournisseur' => $supplier->name ?? $lastMovement->supplier_name ?? 'Fournisseur standard',
                    'prix_unitaire' => $lastMovement->cost_price ?? $item->cost_price ?? 0
                ];
            }
        }

        return response()->json([
            'status' => '✅ Suggestions générées avec succès',
            'propositions' => $suggestions
        ]);
    }
}
