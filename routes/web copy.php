<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvoirController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\SenMailController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\TecdocController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\PayementmodeController;
use App\Http\Controllers\PayementtermController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseInvoiceController;
use App\Http\Controllers\SoucheController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TvaController;
use App\Http\Controllers\TvaGroupController;
use App\Http\Controllers\UnitController;
use App\Models\Arrivage;
use App\Models\Brand;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\PurchaseOrder;
use App\Models\Store;
use App\Models\TvaGroup;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/login', [AuthController::class, 'loginFormAdmin'])->name('login.form');
Route::post('/login', [AuthController::class, 'loginFormAdmin'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes protégées par le middleware personnalisé
Route::middleware('auth')->group(function () {


Route::get('/items/search', [ItemController::class, 'search'])->name('items.search');

Route::get('/dashboard', [AuthController::class, 'adminDashboard'])->name('dashboard');


Route::get('/commande', [ItemController::class, 'commande'])->name('commande');

Route::get('/commandetest', [ItemController::class, 'commandetest'])->name('commandetest');
Route::post('/itemstest/search', [ItemController::class, 'searchtest'])->name('items.searchtest');


Route::get('/commande/refresh-clients', function () {
    session()->forget('clients');
    return redirect('/commande');
});

Route::get('/actualiser', function () {
    session()->forget('clients');
    return redirect()->back();
});



Route::post('/client/selectionner', function (Request $request) {
    $customerNo = $request->client;
    $clients = session('clients', []);
    $client = collect($clients)->where('CustomerNo', $customerNo)->first();

    if ($client) {
        session()->forget('selectedClient');
        session()->put('selectedClient', $client);
        session()->save();
        

        // Log pour vérifier la session
        // \Log::info('Client sélectionné:', ['client' => $client]);
        // \Log::info('Session après mise à jour:', ['selectedClient' => session('selectedClient')]);

        return response()->json([
            'success' => true,
            'client' => $client,
            'selectedClient' => session('selectedClient')
        ]);
    }

    return response()->json(['success' => false]);
})->name('client.selectionner');




    Route::get('/contact', function () {
        return view('contact');
    });






        Route::get('/articlesold', function () {
            $categories = ItemCategory::all();
$brands = Brand::all();
$units = Unit::all();
$stores = Store::all();
$tvaGroups = TvaGroup::all();
    $items = Item::with(['category', 'brand', 'tvaGroup'])->orderBy('name')->get();


        return view('articles', compact('items','stores','categories', 'brands', 'units', 'tvaGroups'));
    });

Route::get('/articles', [ItemController::class, 'index'])->name('articles.index');



Route::post('/items', [ItemController::class, 'store'])->name('items.store');
Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');
Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');

Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
Route::put('/category/{id}', [CategoryController::class, 'update'])->name('category.update');

Route::get('/grouptvas', [TvaController::class, 'index'])->name('grouptva.index');
Route::post('/grouptva', [TvaController::class, 'store'])->name('grouptva.store');
Route::delete('/grouptva/{id}', [TvaController::class, 'destroy'])->name('grouptva.destroy');
Route::put('/grouptva/{id}', [TvaController::class, 'update'])->name('grouptva.update');

Route::get('/groupremises', [DiscountController::class, 'index'])->name('groupremise.index');
Route::post('/groupremise', [DiscountController::class, 'store'])->name('groupremise.store');
Route::delete('/groupremise/{id}', [DiscountController::class, 'destroy'])->name('groupremise.destroy');
Route::put('/groupremise/{id}', [DiscountController::class, 'update'])->name('groupremise.update');

Route::get('/paymentmodes', [PayementmodeController::class, 'index'])->name('paymentmode.index');
Route::post('/paymentmode', [PayementmodeController::class, 'store'])->name('paymentmode.store');
Route::delete('/paymentmode/{id}', [PayementmodeController::class, 'destroy'])->name('paymentmode.destroy');
Route::put('/paymentmode/{id}', [PayementmodeController::class, 'update'])->name('paymentmode.update');

Route::get('/paymentterms', [PayementtermController::class, 'index'])->name('paymentterm.index');
Route::post('/paymentterm', [PayementtermController::class, 'store'])->name('paymentterm.store');
Route::delete('/paymentterm/{id}', [PayementtermController::class, 'destroy'])->name('paymentterm.destroy');
Route::put('/paymentterm/{id}', [PayementtermController::class, 'update'])->name('paymentterm.update');


Route::get('/souches', [SoucheController::class, 'index'])->name('souches.index');
Route::post('/souche', [SoucheController::class, 'store'])->name('souche.store');
Route::delete('/souche/{id}', [SoucheController::class, 'destroy'])->name('souche.destroy');
Route::put('/souche/{id}', [SoucheController::class, 'update'])->name('souche.update');





Route::get('/units', [UnitController::class, 'index'])->name('unit.index');
Route::post('/unit', [UnitController::class, 'store'])->name('unit.store');
Route::delete('/unit/{id}', [UnitController::class, 'destroy'])->name('unit.destroy');
Route::put('/unit/{id}', [UnitController::class, 'update'])->name('unit.update');

Route::get('/brands', [BrandController::class, 'index'])->name('brand.index');
Route::post('/brand', [BrandController::class, 'store'])->name('brand.store');
Route::delete('/brand/{id}', [BrandController::class, 'destroy'])->name('brand.destroy');
Route::put('/brand/{id}', [BrandController::class, 'update'])->name('brand.update');


Route::get('/customers', [CustomerController::class, 'index'])->name('customer.index');
Route::post('/customer', [CustomerController::class, 'store'])->name('customer.store');
Route::delete('/customer/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
Route::put('/customer/{id}', [CustomerController::class, 'update'])->name('customer.update');



Route::get('/suppliers', [SupplierController::class, 'index'])->name('supplier.index');
Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.store');
Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');



Route::get('/magasins', [StoreController::class, 'index'])->name('magasin.index');
Route::post('/magasin', [StoreController::class, 'store'])->name('magasin.store');
Route::delete('/magasin/{id}', [StoreController::class, 'destroy'])->name('magasin.destroy');
Route::put('/magasin/{id}', [StoreController::class, 'update'])->name('magasin.update');


Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
Route::get('/purchases/list', [PurchaseController::class, 'list'])->name('purchases.list');
Route::get('/purchases/{id}/edit', [PurchaseController::class, 'edit'])->name('purchases.edit');
Route::put('/purchases/{id}', [PurchaseController::class, 'update'])->name('purchases.update');


Route::get('/receptions/create', [ReceptionController::class, 'create'])->name('receptions.create');
Route::post('/receptions', [ReceptionController::class, 'store'])->name('receptions.store');

Route::get('/purchase-invoices/create', [PurchaseInvoiceController::class, 'create'])->name('purchase_invoices.create');




Route::get('/setting', function () {
            return view('parametres');
    });


Route::post('/stock/update', [StockController::class, 'updateOrCreate'])->name('stock.update');
Route::post('/stock/movement', [StockMovementController::class, 'store'])->name('stock.movement.store');




    Route::get('/tecdoc', function () {
        $reference="";
        $response = Http::withHeaders([
            'X-Api-Key' => '2BeBXg6LDMZPdqWdaoq9CP19qGL6bTDHB9qBJEu6K264jC2Yv8wg'
        ])->post('https://webservice.tecalliance.services/pegasus-3-0/services/TecdocToCatDLB.jsonEndpoint', [
            "getLinkageTargets" => [
                "provider" => env('TECDOC_PROVIDER_ID'),
                "linkageTargetCountry" => "TN", 
                "lang" => "fr",
                "linkageTargetType" => "P", 
                "perPage" => 0,
                "page" => 1,
                "includeMfrFacets" => true
            ]
        ]);
        // Décoder la réponse JSON
        $data = $response->json();
    
        // Vérifier si les marques existent et extraire les marques
        if (isset($data['mfrFacets']['counts'])) {
            $brands = $data['mfrFacets']['counts']; // Liste des marques
        } else {
            $brands = []; // Si pas de marques disponibles
        }
        return view('tecdoc',compact('reference','brands'));
    });

    Route::get('/searchtecdoc', [TecdocController::class, 'search'])->name('tecdoc.search');
    Route::get('/search2', [TecdocController::class, 'search2'])->name('tecdoc.search2');
    Route::get('/vehicle/{vin}', [TecDocController::class, 'getVehicleByVin']);
    Route::get('/get-article/{articleNumber}', [TecDocController::class,'getArticle']);


    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');



    Route::post('/clients/create', [AdminController::class, 'create'])->name('clients.create');


Route::post('/panier/ajouter', [PanierController::class, 'ajouterAuPanier'])->name('panier.ajouter');
Route::get('/panier/lister', [PanierController::class, 'listerPanier'])->name('panier.lister');
Route::post('/panier/supprimer', [PanierController::class, 'supprimerDuPanier'])->name('panier.supprimer');
Route::post('/panier/vider', [PanierController::class, 'vider'])->name('panier.vider');
Route::post('/panier/valider', [PanierController::class, 'validerPanier'])->name('panier.valider');

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{numCommande}', [OrderController::class, 'show'])->name('orders.show');

Route::get('/facturer/{numCommande}', [OrderController::class, 'facturer'])->name('facturer');

Route::get('/createdevis', [PanierController::class, 'createdevis'])->name('createdevis');
Route::get('/createbrouillon', [PanierController::class, 'createbrouillon'])->name('createbrouillon');
Route::get('/createdemain', [PanierController::class, 'createdemain'])->name('createdemain');

Route::post('/create-credit-memo', [InvoiceController::class, 'createCreditMemo'])->name('create.credit.memo');
Route::post('/invoices/creditmemo-total', [InvoiceController::class, 'createTotalCreditMemo'])->name('invoices.creditmemo.total');



Route::get('/listdevis', [DevisController::class, 'index'])->name('listdevis');
Route::get('/listbrouillon', [DevisController::class, 'indexbrouillon'])->name('listbrouillon');

Route::get('/devis/{id}', [DevisController::class, 'show'])->name('devis.show');
Route::get('/brouillon/{id}', [DevisController::class, 'showbrouillon'])->name('brouillon.show');

Route::get('/devis/{id}/pdf', [DevisController::class, 'exportPdf'])->name('devis.exportPdf');
Route::get('/devis/{id}/load-panier', [DevisController::class, 'loadPanier'])->name('devis.loadPanier');
Route::get('/items/history', [ItemController::class, 'history']);
Route::get('/devissansref/{id}/pdf', [DevisController::class, 'exportPdfsansref'])->name('devis.exportPdfsansref');
Route::get('/devissansremise/{id}/pdf', [DevisController::class, 'exportPdfsansremise'])->name('devis.exportPdfsansremise');
Route::get('/devissans2/{id}/pdf', [DevisController::class, 'exportPdfsans2'])->name('devis.exportPdfsans2');


Route::get('/orders/{NumBL}/{orderNo}/{CustomerNo}/export-pdf/{DateBL}', [OrderController::class, 'exportPdf'])
     ->name('orders.exportPdf');

Route::get('/orderssansref/{NumBL}/{orderNo}/{CustomerNo}/export-pdf/{DateBL}', [OrderController::class, 'exportPdfsansref'])
     ->name('orders.exportPdfsansref');

     Route::get('/orderssansremise/{NumBL}/{orderNo}/{CustomerNo}/export-pdf/{DateBL}', [OrderController::class, 'exportPdfsansremise'])
     ->name('orders.exportPdfsansremise');

     Route::get('/orderssans2/{NumBL}/{orderNo}/{CustomerNo}/export-pdf/{DateBL}', [OrderController::class, 'exportPdfsans2'])
     ->name('orders.exportPdfsans2');

Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
Route::get('/invoices/{NumFacture}', [InvoiceController::class, 'show'])->name('invoices.show');


Route::post('/modify-password', [AuthController::class, 'modifyPassword'])->name('modify.password');

    
Route::get('/passwordform', function () {
    return view('modifypassword'); 
});


Route::get('/receptions', [ReceptionController::class, 'index'])->name('receptions.index');
Route::get('/receptions/{NumReception}', [ReceptionController::class, 'show'])->name('receptions.show');
Route::post('/receptions/pdf', [ReceptionController::class, 'genererPDF'])->name('receptions.pdf');
Route::post('/receptions/print-multiple', [ReceptionController::class, 'printMultiple'])->name('receptions.printMultiple');
Route::get('/receptions/multiple/{Nums}', [ReceptionController::class, 'showMultiple'])->name('receptions.showMultiple');



Route::get('/force-new-password', [AuthController::class, 'showForceNewPasswordForm'])->name('force.new.password');
Route::post('/force-new-password', [AuthController::class, 'forceNewPassword'])->name('force.new.password.submit');
Route::get('/invoices/{NumFacture}/export-pdf/{DateFacture}/{CodeClient}', [InvoiceController::class, 'exportPdfsansref'])
     ->name('invoices.exportPdfsansref');

     Route::get('/invoicessansref/{NumFacture}/export-pdf/{DateFacture}/{CodeClient}', [InvoiceController::class, 'exportPdf'])
     ->name('invoices.exportPdf');

     Route::get('/invoicesduplic/{NumFacture}/export-pdf/{DateFacture}/{CodeClient}', [InvoiceController::class, 'exportPdfduplic'])
     ->name('invoices.exportPdfduplic');


Route::get('/avoirs', [AvoirController::class, 'index'])->name('avoirs.index');
Route::get('/avoirs/{NumAvoir}', [AvoirController::class, 'show'])->name('avoirs.show');
Route::get('/avoirs/{NumAvoir}/export-pdf/{DateFacture}/{CustomerNo}', [AvoirController::class, 'exportCreditNotePdf'])->name('avoirs.export_pdf');


Route::get('/arrivage', [AdminController::class, 'arrivage'])->name('arrivage');





Route::get('/Brands', [TecdocController::class, 'getBrands'])->name('getBrands');

Route::get('/Models', [TecdocController::class, 'getModels'])->name('getModels');

Route::get('/Engines', [TecdocController::class, 'getEngines'])->name('getEngines');

Route::get('/Categories', [TecdocController::class, 'getCategories'])->name('getCategories');

Route::get('/getparts', [TecdocController::class, 'getparts'])->name('getparts');
Route::get('/persoget', [TecdocController::class, 'persoget'])->name('persoget');





Route::post('/cataloguesearch', [ItemController::class, 'cataloguesearch'])->name('cataloguesearch');
Route::get('/lastarrivage/{id}', [AdminController::class, 'showArrivage'])->name('lastarrivage');





Route::post('/users', [AuthController::class, 'store'])->name('users.store');

    // Autres routes protégées...
});



// Routes pour les administrateurs
Route::get('/', [AuthController::class, 'loginFormAdmin'])->name('login.form.admin');
Route::post('/admin/login', [AuthController::class, 'loginAdmin'])->name('login.admin');
Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard')->middleware('auth');
Route::post('/admin/logout', [AuthController::class, 'logoutAdmin'])->name('logout.admin');







