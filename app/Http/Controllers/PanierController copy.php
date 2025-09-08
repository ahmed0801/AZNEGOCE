<?php

namespace App\Http\Controllers;

use App\Models\Entetepanier;
use App\Models\Panier;
use Illuminate\Http\Request;
use App\Services\BusinessCentralService;
use Illuminate\Support\Facades\Auth;

class PanierController extends Controller
{
    // Ajouter un article au panier
    public function ajouterAuPanier(Request $request)
    {
        $article = $request->input('article');
        $quantite = $request->input('quantite', 1);
        $prixV = $request->input('prixV') ?? $article['Price'];
        $remise = (float) $request->input('remise', 0); // Cast to float to avoid []

    
        $panier = session()->get('panier', []);
    
        if (isset($panier[$article['ItemNo']])) {
            $panier[$article['ItemNo']]['quantite'] += $quantite;
        } else {
            $panier[$article['ItemNo']] = [
                'article' => $article,
                'PrixVenteUnitaire' => $prixV,
                'quantite' => $quantite,
                'remise' => $remise,
            ];
        }
    
        session()->put('panier', $panier);


            // Calcul du total avec remise
    $total = array_reduce($panier, function ($sum, $item) {
        $prix = (float) str_replace(',', '', $item['PrixVenteUnitaire']);
        $remise = isset($item['remise']) ? (float) $item['remise'] : 0;
        $prixAvecRemise = $prix * (1 - ($remise / 100));
        return $sum + ($prixAvecRemise * $item['quantite']);
    }, 0);
        
    
        return response()->json(['success' => true, 'panier' => $panier, 'total' => $total]);
    }
    
    public function supprimerDuPanier(Request $request)
    {
        $itemNo = $request->input('itemNo');
        $panier = session()->get('panier', []);
    
        if (isset($panier[$itemNo])) {
            unset($panier[$itemNo]);
        }
    
        session()->put('panier', $panier);
    
        $total = array_reduce($panier, function ($sum, $item) {
            return $sum + (float) str_replace(',', '', $item['article']['Price']) * $item['quantite'];
        }, 0);
    
        return response()->json(['success' => true, 'panier' => $panier, 'total' => $total]);
    }
    

    public function vider()
    {
        // dd(session('panier'));
        session()->forget('panier');
        session()->forget('selectedClient');
        return redirect('/commande')->with('success', 'Le panier a été vidé.');
    }


    



    // Valider le panier (Nouveau)
    public function validerPanier(Request $request, BusinessCentralService $bcService)
    {
        $panierSession = session('panier', []);
        $customerNo = session('selectedClient')['CustomerNo'] ?? null;
        $vendorNo= Auth::user()->codevendeur;
        if (empty($panierSession) || !$customerNo) {
            return redirect()->route('commande')->with('error', 'Le panier est vide ou l’utilisateur n’est pas identifié.');
        }
    
        // Créer une nouvelle entête de panier
        $entetepanier = Entetepanier::create([
            'user_id' => $customerNo,
            'status' => 'en attente',
        ]);
    
        // Ajouter chaque article comme une ligne de panier
        $panierData = [];
        foreach ($panierSession as $itemNo => $details) {
            $panier = Panier::create([
                'entetepanier_id' => $entetepanier->id,
                'item_reference' => $details['article']['ItemNo'],
                'item_name' => $details['article']['Desc'],
                'quantity' => $details['quantite'],
                'price' => floatval(str_replace(',', '', $details['article']['Price'])),
            ]);
            $panierData[] = [
                'item_reference' => $details['article']['ItemNo'],
                'quantity' => $details['quantite'],
                'PrixVenteUnitaire' => $details['PrixVenteUnitaire'],
                'remise' => $details['remise'],
            ];
        }
    // dd($panierData);
        // Appeler le web service SOAP pour valider le panier
        $response = $bcService->importSales($panierData, $customerNo);
    
        if ($response) {
            $cdeTPG = $response['cdeTPG'];
    
            // Gérer les réponses
            if ($cdeTPG) {
                // Vider la session du panier après la validation
                session()->forget('panier');
                session()->forget('selectedClient');

                return redirect('/dashboard')->with([
                    'success_html' => '
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span>Le panier a été validé avec succès.</span>
                        </div>'
                ]);
                              
                
                
                            }
    
            return redirect('/dashboard')->with('error', 'La commande n’a pas pu être passée.');
        }
    
        return redirect('/dashboard')->with('error', 'Une erreur est survenue lors de la validation.');
    }
    



    
     // Retourne les entêtes de paniers avec leurs articles associés
     public function getPaniersEtArticles()
     {
         $paniers = Entetepanier::with('paniers')
             ->where('status', 'en attente') // Filtrer uniquement les paniers en attente
             ->get();
     
         // Transformer les données pour correspondre au format souhaité
         $result = $paniers->flatMap(function ($panier) {
             return $panier->paniers->map(function ($article) use ($panier) {
                 return [
                     'CodeArticle' => $article->item_reference,
                     'Qte' => $article->quantity,
                     'CodeClient' => $panier->user_id,
                    //  'panierid' => $panier->id
                 ];
             });
         });
     
         return response()->json($result);
     }
     
 
     // Marquer les paniers comme synchronisés après traitement
     public function marquerCommeSynchronise(Request $request)
{
    // Récupérer les IDs des paniers à synchroniser depuis les paramètres GET
    $entetepanierIds = explode(',', $request->input('entetepanier_ids', ''));

    // Vérifier si des IDs ont été fournis
    if (empty($entetepanierIds)) {
        return response()->json(['success' => false, 'message' => 'Aucun ID de panier fourni.'], 400);
    }

    // Mettre à jour les paniers avec les IDs fournis
    $updatedRows = Entetepanier::whereIn('id', $entetepanierIds)->update(['status' => 'synchronisé']);

    // Vérifier si des paniers ont été mis à jour
    if ($updatedRows > 0) {
        return response()->json(['success' => true, 'message' => 'Les paniers ont été synchronisés avec succès.']);
    }

    return response()->json(['success' => false, 'message' => 'Aucune mise à jour effectuée.']);
}












    // Valider le panier (Nouveau)
    public function createdevis(Request $request)
    {
        $panierSession = session('panier', []);
        $customerNo = session('selectedClient')['CustomerNo'] ?? null;
        $CustomerName = session('selectedClient')['CustomerName'] ?? null;
        $MatFiscale = session('selectedClient')['MatFiscale'] ?? null;
        $VATCode = session('selectedClient')['VATCode'] ?? null;

        $vendorNo= Auth::user()->codevendeur;
        if (empty($panierSession) || !$customerNo) {
            return redirect()->route('commande')->with('error', 'Le panier est vide ou l’utilisateur n’est pas identifié.');
        }
    
        // dd(session('panier'));
        // Créer une nouvelle entête de panier
        $entetepanier = Entetepanier::create([
            'user_id' => $customerNo,
            'status' => 'en attente',
            'CustomerName' => $CustomerName,
            'MatFiscale' => $MatFiscale,
            'VATCode' => $VATCode,
        ]);
    
        // Ajouter chaque article comme une ligne de panier
        $panierData = [];
        foreach ($panierSession as $itemNo => $details) {
            $panier = Panier::create([
                'entetepanier_id' => $entetepanier->id,
                'item_reference' => $details['article']['ItemNo'],
                'item_name' => $details['article']['Desc'],
                'quantity' => $details['quantite'],
                'price' => floatval(str_replace(',', '', $details['PrixVenteUnitaire'])),
                'remise' => $details['remise'],
            ]);
            $panierData[] = [
                'item_reference' => $details['article']['ItemNo'],
                'quantity' => $details['quantite'],
                'PrixVenteUnitaire' => $details['PrixVenteUnitaire'],
                'remise' => $details['remise'],
            ];
        }
    // dd($panierData);
      
    // Vider la session du panier après la validation
    session()->forget('panier');
    session()->forget('selectedClient');
      
    
        // return redirect('/dashboard')->with('success', 'Devis crée avec succés.');
        $dernierDevis = Entetepanier::orderBy('created_at', 'desc')->first();

$lienPdf = route('devis.exportPdf', ['id' => $dernierDevis->id]);
$messageHtml = "Devis créé avec succès. <a href='$lienPdf' target='_blank' class='btn btn-success btn-xs ml-2' style='padding: 2px 6px; font-size: 0.75rem;'>🖨️ Imprimer</a>";

return redirect('/dashboard')->with('success_html', $messageHtml);

    }



     
}
