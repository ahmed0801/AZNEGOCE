<?php

namespace App\Http\Controllers;

use App\Models\Arrivage;
use App\Models\Brand;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Store;
use App\Models\TvaGroup;
use App\Models\Unit;
use App\Services\BusinessCentralService;
use Illuminate\Http\Request;
use Composite\TecDoc\Facades\TecDoc;


class ItemController extends Controller
{
    protected $businessCentralService;

    public function __construct(BusinessCentralService $businessCentralService)
    {
        $this->businessCentralService = $businessCentralService;
    }






public function index(Request $request)
{
$query = Item::with(['category', 'brand', 'tvaGroup', 'store', 'supplier']);

    // Recherche globale
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('code', 'like', "%{$request->search}%");
        });
    }

    // Filtres spécifiques
    if ($request->filled('brand_id')) {
        $query->where('brand_id', $request->brand_id);
    }

    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    if ($request->filled('store_id')) {
        $query->where('store_id', $request->store_id);
    }
    if ($request->filled('codefournisseur')) {
    $query->where('codefournisseur', $request->codefournisseur);
}

if ($request->has('is_active') && $request->is_active !== null && $request->is_active !== '') {
    $query->where('is_active', $request->is_active);
}



    $items = $query->orderBy('name')->paginate(150)->withQueryString();

    return view('articles', [
        'items' => $items,
        'categories' => ItemCategory::all(),
        'brands' => Brand::all(),
        'units' => Unit::all(),
        'stores' => Store::all(),
        'tvaGroups' => TvaGroup::all(),
        'search' => $request->search,
        'brand_id' => $request->brand_id,
        'category_id' => $request->category_id,
        'store_id' => $request->store_id
    ]);
}








public function indexold(Request $request)
{
    $query = Item::with(['category', 'brand', 'tvaGroup']);

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%");
        });
    }

    $items = $query->orderBy('name')->paginate(1)->withQueryString();

    return view('articles', [
        'items' => $items,
        'categories' => ItemCategory::all(),
        'brands' => Brand::all(),
        'units' => Unit::all(),
        'stores' => Store::all(),
        'tvaGroups' => TvaGroup::all(),
        'search' => $request->search
    ]);
}









public function store(Request $request)
{
    // 1. Validation des champs
    $validated = $request->validate([
        'code'           => 'required|string|max:255|unique:items,code',
        'name'           => 'required|string|max:255',
        'description'    => 'nullable|string',
        'category_id'    => 'nullable|exists:item_categories,id',
        'brand_id'       => 'nullable|exists:brands,id',
        'unit_id'        => 'nullable|exists:units,id',
        'tva_group_id'   => 'nullable|exists:tva_groups,id',
        'barcode'        => 'nullable|string|max:255',
        'cost_price'     => 'nullable|numeric|min:0',
        'sale_price'     => 'nullable|numeric|min:0',
        'stock_min'      => 'nullable|integer|min:0',
        'stock_max'      => 'nullable|integer|min:0',
        'is_active'      => 'nullable|boolean',
        'store_id' => 'nullable|exists:stores,id',
        'codefournisseur' => 'nullable|exists:suppliers,code',
'location' => 'nullable|string|max:255',

    ]);

    // 2. Création de l’article
    $item = Item::create([
        'code'           => $validated['code'],
        'name'           => $validated['name'],
        'description'    => $validated['description'] ?? null,
        'category_id'    => $validated['category_id'] ?? null,
        'brand_id'       => $validated['brand_id'] ?? null,
        'unit_id'        => $validated['unit_id'] ?? null,
        'tva_group_id'   => $validated['tva_group_id'] ?? null,
        'barcode'        => $validated['barcode'] ?? null,
        'cost_price'     => $validated['cost_price'] ?? 0.00,
        'sale_price'     => $validated['sale_price'] ?? 0.00,
        'stock_min'      => $validated['stock_min'] ?? 0,
        'stock_max'      => $validated['stock_max'] ?? 0,
        'store_id' => $validated['store_id'] ?? null,
        'codefournisseur' => $validated['codefournisseur'] ?? null,
'location' => $validated['location'] ?? null,

        'is_active'      => true,
    ]);

    // 3. Redirection ou réponse AJAX
    return redirect()->back()->with('success', 'Article créé avec succès.');
}



public function destroy($id)
{
    $item = Item::findOrFail($id);
    $item->delete();

    return redirect()->back()->with('success', 'Article supprimé avec succès.');
}



public function update(Request $request, $id)
{
    $item = Item::findOrFail($id);

    $validated = $request->validate([
        'code'         => 'required|string|max:255|unique:items,code,' . $item->id,
        'name'         => 'required|string|max:255',
        'description'  => 'nullable|string',
        'category_id'  => 'nullable|exists:item_categories,id',
        'brand_id'     => 'nullable|exists:brands,id',
        'unit_id'      => 'nullable|exists:units,id',
        'tva_group_id' => 'nullable|exists:tva_groups,id',
        'barcode'      => 'nullable|string|max:255',
        'cost_price'   => 'nullable|numeric|min:0',
        'sale_price'   => 'nullable|numeric|min:0',
        'stock_min'    => 'nullable|integer|min:0',
        'stock_max'    => 'nullable|integer|min:0',
        'store_id' => 'nullable|exists:stores,id',
        'codefournisseur' => 'nullable|exists:suppliers,code',
'location' => 'nullable|string|max:255',
'is_active' => 'required|boolean',

    ]);

    $item->update($validated);

    return redirect()->back()->with('success', 'Article mis à jour avec succès.');
}




















    public function search(Request $request)
    {
        // Initialisation services et données
        $businessCentralService = new BusinessCentralService();
        
    // Vérifier si les vendors sont déjà dans la session
    $vendors = session('vendors');
    
    if (!$vendors) {
        // Si les vendors ne sont pas dans la session, appeler le service
        $businessCentralService = new BusinessCentralService();
        $vendors = $businessCentralService->getAllVendors();

        // Stocker les vendors dans la session pour les prochaines requêtes
        session(['vendors' => $vendors]);
    }
    

    // Vérifier si les clients sont déjà dans la session
    $clients = session('clients');
    if (!$clients) {
        // Si les clients ne sont pas dans la session, appeler le service
        $clients = $this->businessCentralService->CustomerList() ?? [];
        // Stocker les clients dans la session pour les prochaines requêtes
        session(['clients' => $clients]);
    }



    
    $selectedClient = session('selectedClient')['CustomerNo'] ?? '';
    
        // Récupération des filtres
        $itemFilter = $request->input('itemFilter');
        $descriptionFilter = $request->input('descriptionFilter');
        $originReferenceFilter = $request->input('originReferenceFilter');
    
        // Message d'erreur si tous les champs sont vides
        if (empty($itemFilter) && empty($descriptionFilter) && empty($originReferenceFilter)) {
            return redirect('/commande')->with('error', 'Veuillez entrer une référence, une description ou une référence d\'origine.');
        }
    
        $items = [];
        $articles = null;
    
        // Cas 1 : Recherche par Référence (Item)
        if (!empty($itemFilter)) {
            $articles = $this->searchTecDoc($itemFilter);
    
            // Crée un tableau des références
            $articleNos = [["ArticleNo" => $itemFilter]];
            if ($articles) {
                foreach ($articles as $article) {
                    if (isset($article->directArticle->articleNo)) {
                        $articleNos[] = ["ArticleNo" => $article->directArticle->articleNo];
                        if (count($articleNos) >= 10) break;
                    }
                }
            }
    
            $items = $this->businessCentralService->getItemsByGroup(json_encode($articleNos));
        }
    
        // Cas 2 : Recherche par Description
        elseif (!empty($descriptionFilter)) {
            $items = $this->businessCentralService->getItemsByDescription($descriptionFilter);
        }
    
        // Cas 3 : Recherche par Référence d’Origine
        elseif (!empty($originReferenceFilter)) {
            $articles = $this->searchTecDoc($originReferenceFilter);
    
            $articleNos = [["OrigineNo" => $originReferenceFilter]];
            $seenOeNumbers = [];
    
            if ($articles) {
                foreach ($articles as $article) {
                    if (!empty($article->oenNumbers)) {
                        foreach ($article->oenNumbers as $oen) {
                            if (!in_array($oen->oeNumber, $seenOeNumbers)) {
                                $articleNos[] = ["OrigineNo" => $oen->oeNumber];
                                $seenOeNumbers[] = $oen->oeNumber;
                                if (count($articleNos) >= 10) break 2;
                            }
                        }
                    }
                }
            }
    
            $items = $this->businessCentralService->searchByRefOriginGroup(json_encode($articleNos));
        }
    
        // Post-traitement : suppression des doublons et tri
        $items = collect($items)->unique('ItemNo')->sortBy('Desc')->values()->all();
    
        // Si aucun résultat
        if (empty($items) && empty($articles)) {
            return redirect('/commande')->with('error', 'Aucun article trouvé pour cette recherche.');
        }
        $scrollTo = 'searchresultat';
        return view('commande', compact('vendors', 'selectedClient', 'clients', 'items', 'itemFilter', 'descriptionFilter', 'originReferenceFilter', 'articles', 'scrollTo'));
    }
    
    private function searchTecDoc($reference)
    {
        $filter = ['numberType' => 10, 'searchExact' => true];
        try {
            $articles = TecDoc::articles()->findByNumber($reference, (array) $filter);
            return empty($articles) ? null : $articles;
        } catch (\Exception $e) {
            return null;
        }
    }
    
    
   
    














    public function cataloguesearch(Request $request)
{
    $descriptionFilter = $request->input('descriptionFilter');
    $Catalogue = $request->input('Catalogue');
    $itemFilter = "";
    $originReferenceFilter = "";
    

    $items = [];





     // Appel au service pour récupérer les fournisseurs
     $businessCentralService = new BusinessCentralService();
     $vendors = $businessCentralService->getAllVendors();

    $clients = $this->businessCentralService->CustomerList() ?? [];
    // session(['clients' => $clients]);


    $selectedClient = session('selectedClient')['CustomerNo'] ?? '';

    if ($descriptionFilter === "ALLUM") {
        // Recherche par "ALLUM"
        $items1 = $this->businessCentralService->getItemsByDescription("ALLUM");

        // Recherche par "BOUGIE"
        $items2 = $this->businessCentralService->getItemsByDescription("BOUGIE");

        // Fusion des résultats
        $items = array_merge($items1, $items2);
    } 

    elseif ($descriptionFilter === "ESSUI") {
        // Recherche par "ALLUM"
        $items1 = $this->businessCentralService->getItemsByDescription("ESSUI");

        // Recherche par "BOUGIE"
        $items2 = $this->businessCentralService->getItemsByDescription("LAVE");

        // Fusion des résultats
        $items = array_merge($items1, $items2);
    } 
    elseif ($descriptionFilter === "COURROI") {
        // Recherche par "ALLUM"
        $items1 = $this->businessCentralService->getItemsByDescription("COURROI");

        // Recherche par "BOUGIE"
        $items2 = $this->businessCentralService->getItemsByDescription("CHAINE");

        // Fusion des résultats
        $items = array_merge($items1, $items2);
    } 


    else {
        $items = $this->businessCentralService->getItemsByDescription($descriptionFilter);
    }

    // Filtrage et suppression des doublons
    $items = collect($items)
        ->unique('ItemNo')
        ->sortBy('Desc') // Tri par ordre alphabétique
        ->values()
        ->all();

    $descriptionFilter = $Catalogue;
    $arrivages = Arrivage::latest()->take(3)->get(); // 3 derniers arrivages

    if (empty($items)) {
        return redirect('/commande')->with('error', 'Aucun article trouvé pour cette recherche.');
    }

    $scrollTo = 'searchresultat';

    return view('commande', compact('vendors','clients','selectedClient','arrivages','items', 'itemFilter', 'descriptionFilter', 'originReferenceFilter','scrollTo'));
}




public function commande(Request $request)
{
    $itemFilter = '';
    $descriptionFilter = '';
    $originReferenceFilter = '';
    $items = [];


      // test a supprimer
      $clients = session('clients');
    if (!$clients) {
    $clients = $this->businessCentralService->CustomerList() ?? [];
    session(['clients' => $clients]);
    }

    // Ne pas pré-sélectionner un client automatiquement
    $selectedClient = session('selectedClient')['CustomerNo'] ?? '';

    // Vérifier si les vendors sont déjà stockés dans la session
    $vendors = session('vendors');
    
    if (!$vendors) {
        // Si les vendors ne sont pas dans la session, appeler le service
        $businessCentralService = new BusinessCentralService();
        $vendors = $businessCentralService->getAllVendors();

        // Stocker les vendors dans la session pour les prochaines requêtes
        session(['vendors' => $vendors]);
    }

    return view('commande', compact('vendors', 'items', 'itemFilter', 'descriptionFilter', 'originReferenceFilter', 'clients', 'selectedClient'));
}





public function history(Request $request)
{
    $itemNo = $request->query('itemNo');

    if (!$itemNo) {
        abort(404, 'Article non spécifié.');
    }

    $itemNo = urldecode($itemNo);
    $historique = $this->businessCentralService->getItemBLHistory($itemNo);
    $data = $historique['data'] ?? [];

    // Tri décroissant direct par BLNo
    usort($data, function ($a, $b) {
        return strcmp($b['BLNo'], $a['BLNo']); // tri décroissant
    });

    return view('history_popup', [
        'itemNo' => $itemNo,
        'historique' => $data
    ]);
}



    











public function commandetest(Request $request)
{
    $itemFilter = '';
    $descriptionFilter = '';
    $originReferenceFilter = '';
    $items = [];


      // test a supprimer
      $clients = session('clients');
    if (!$clients) {
    $clients = $this->businessCentralService->CustomerList() ?? [];
    session(['clients' => $clients]);
    }

    // Ne pas pré-sélectionner un client automatiquement
    $selectedClient = session('selectedClient')['CustomerNo'] ?? '';

    // Vérifier si les vendors sont déjà stockés dans la session
    $vendors = session('vendors');
    
    if (!$vendors) {
        // Si les vendors ne sont pas dans la session, appeler le service
        $businessCentralService = new BusinessCentralService();
        $vendors = $businessCentralService->getAllVendors();

        // Stocker les vendors dans la session pour les prochaines requêtes
        session(['vendors' => $vendors]);
    }

    return view('commandetest', compact('vendors', 'items', 'itemFilter', 'descriptionFilter', 'originReferenceFilter', 'clients', 'selectedClient'));
}

public function searchtest(Request $request)
    {
        // Initialisation services et données
        $businessCentralService = new BusinessCentralService();
        
    // Vérifier si les vendors sont déjà dans la session
    $vendors = session('vendors');
    
    if (!$vendors) {
        // Si les vendors ne sont pas dans la session, appeler le service
        $businessCentralService = new BusinessCentralService();
        $vendors = $businessCentralService->getAllVendors();

        // Stocker les vendors dans la session pour les prochaines requêtes
        session(['vendors' => $vendors]);
    }
    

    // Vérifier si les clients sont déjà dans la session
    $clients = session('clients');
    if (!$clients) {
        // Si les clients ne sont pas dans la session, appeler le service
        $clients = $this->businessCentralService->CustomerList() ?? [];
        // Stocker les clients dans la session pour les prochaines requêtes
        session(['clients' => $clients]);
    }



    
    $selectedClient = session('selectedClient')['CustomerNo'] ?? '';
    
        // Récupération des filtres
        $itemFilter = $request->input('itemFilter');
        $descriptionFilter = $request->input('descriptionFilter');
        $originReferenceFilter = $request->input('originReferenceFilter');
    
        // Message d'erreur si tous les champs sont vides
        if (empty($itemFilter) && empty($descriptionFilter) && empty($originReferenceFilter)) {
            return redirect('/commande')->with('error', 'Veuillez entrer une référence, une description ou une référence d\'origine.');
        }
    
        $items = [];
        $articles = null;
    
        // Cas 1 : Recherche par Référence (Item)
        if (!empty($itemFilter)) {
            $articles = $this->searchTecDoc($itemFilter);
    
            // Crée un tableau des références
            $articleNos = [["ArticleNo" => $itemFilter]];
            if ($articles) {
                foreach ($articles as $article) {
                    if (isset($article->directArticle->articleNo)) {
                        $articleNos[] = ["ArticleNo" => $article->directArticle->articleNo];
                        if (count($articleNos) >= 10) break;
                    }
                }
            }
    
            $items = $this->businessCentralService->getItemsByGroup(json_encode($articleNos));
        }
    
        // Cas 2 : Recherche par Description
        elseif (!empty($descriptionFilter)) {
            $items = $this->businessCentralService->getItemsByDescription($descriptionFilter);
        }
    
        // Cas 3 : Recherche par Référence d’Origine
        elseif (!empty($originReferenceFilter)) {
            $articles = $this->searchTecDoc($originReferenceFilter);
    
            $articleNos = [["OrigineNo" => $originReferenceFilter]];
            $seenOeNumbers = [];
    
            if ($articles) {
                foreach ($articles as $article) {
                    if (!empty($article->oenNumbers)) {
                        foreach ($article->oenNumbers as $oen) {
                            if (!in_array($oen->oeNumber, $seenOeNumbers)) {
                                $articleNos[] = ["OrigineNo" => $oen->oeNumber];
                                $seenOeNumbers[] = $oen->oeNumber;
                                if (count($articleNos) >= 10) break 2;
                            }
                        }
                    }
                }
            }
    
            $items = $this->businessCentralService->searchByRefOriginGroup(json_encode($articleNos));
        }
    
        // Post-traitement : suppression des doublons et tri
        $items = collect($items)->unique('ItemNo')->sortBy('Desc')->values()->all();
    
        // Si aucun résultat
        if (empty($items) && empty($articles)) {
            return redirect('/commandetest')->with('error', 'Aucun article trouvé pour cette recherche.');
        }
        $scrollTo = 'searchresultat';
        return view('commandetest', compact('vendors', 'selectedClient', 'clients', 'items', 'itemFilter', 'descriptionFilter', 'originReferenceFilter', 'articles', 'scrollTo'));
    }






    
}