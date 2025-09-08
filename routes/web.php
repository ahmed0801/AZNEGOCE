<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvoirController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeliveryNotesController;
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
use App\Http\Controllers\PurchaseProjectController;
use App\Http\Controllers\PurchaseSettingsController;
use App\Http\Controllers\SalesController;
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
Route::put('/purchases/{numdoc}', [PurchaseController::class, 'update'])->name('purchases.update');

Route::put('/purchases/{id}/validate', [PurchaseController::class, 'validateOrder'])->name('purchases.validate');
Route::get('/purchases/export', [PurchaseController::class, 'export'])->name('purchases.export');
Route::get('/purchases/{id}/export', [PurchaseController::class, 'exportSingle'])->name('purchases.export_single');

Route::get('/purchases/{id}/return', [PurchaseController::class, 'createReturn'])->name('purchases.return.create');
Route::post('/purchases/{id}/return', [PurchaseController::class, 'storeReturn'])->name('purchases.return.store');




Route::get('/purchaseprojects/list', [PurchaseProjectController::class, 'list'])->name('purchaseprojects.list');
Route::get('/purchaseprojects', [PurchaseProjectController::class, 'index'])->name('purchaseprojects.index');
Route::post('/purchaseprojects', [PurchaseProjectController::class, 'store'])->name('purchaseprojects.store');
Route::post('/purchaseprojects/import', [PurchaseProjectController::class, 'import'])->name('purchaseprojects.import');
Route::get('/purchaseprojects/export-template', [PurchaseProjectController::class, 'exportTemplate'])->name('purchaseprojects.export-template');
Route::post('/purchaseprojects/{projectId}/convert', [PurchaseProjectController::class, 'createOrderPurchaseFromProjectPurchase'])->name('purchaseprojects.convert');
Route::get('/purchaseprojects/{projectId}/edit', [PurchaseProjectController::class, 'edit'])->name('purchaseprojects.edit');
Route::post('/purchaseprojects/{projectId}/update', [PurchaseProjectController::class, 'update'])->name('purchaseprojects.update');

Route::get('/purchaseprojects/{projectId}/export', [PurchaseProjectController::class, 'exportSingle'])->name('purchaseprojects.export_single');
Route::get('/purchaseprojects/export', [PurchaseProjectController::class, 'export'])->name('purchaseprojects.export');

Route::view('/voice', 'voice');

Route::get('/articles/export', [ItemController::class, 'export'])->name('articles.export');
Route::get('/purchases/{id}/print', [PurchaseController::class, 'printSingle'])->name('purchases.print_single');

Route::get('/company-information', [PurchaseController::class, 'companyInformation'])->name('company_information.edit');
Route::post('/company-information', [PurchaseController::class, 'storeCompanyInformation'])->name('company_information.store');




Route::get('/purchase-settings', [PurchaseSettingsController::class, 'index'])->name('purchase-settings.index');
Route::put('/purchase-settings', [PurchaseSettingsController::class, 'store'])->name('purchase-settings.store');
Route::match(['get', 'post'], '/returns/search', [PurchaseController::class, 'searchReturns'])->name('returns.search');




Route::get('/returns', [PurchaseController::class, 'returnsList'])->name('returns.list');
Route::get('/returns/{id}', [PurchaseController::class, 'showReturn'])->name('purchases.return.show');
Route::get('/returns/export', [PurchaseController::class, 'exportReturns'])->name('returns.export');
Route::get('/returns/{id}/export', [PurchaseController::class, 'exportSingleReturn'])->name('returns.export_single');
Route::get('/returns/{id}/print', [PurchaseController::class, 'printSingleReturn'])->name('returns.print_single');





Route::get('notes', [PurchaseController::class, 'notesList'])->name('notes.list');

Route::get('notes/create/from-return', [PurchaseController::class, 'createNoteFromReturn'])->name('notes.create_from_return');
Route::get('notes/create/from-invoice', [PurchaseController::class, 'createNoteFromInvoice'])->name('notes.create_from_invoice');
Route::post('notes/get-return-lines', [PurchaseController::class, 'getReturnLines'])->name('notes.get_return_lines');
Route::post('notes/get-invoice-lines', [PurchaseController::class, 'getInvoiceLines'])->name('notes.get_invoice_lines');

Route::post('/notes/store', function (Request $request) {
    $controller = app(PurchaseController::class);
    if ($request->type === 'from_return') {
        return $controller->storeReturnNote($request);
    }
    return $controller->storeInvoiceNote($request);
})->name('notes.store');



Route::get('notes/{id}/edit', [PurchaseController::class, 'editNote'])->name('notes.edit');
Route::put('notes/{numdoc}', [PurchaseController::class, 'updateNote'])->name('notes.update');
Route::get('notes/{id}/show', [PurchaseController::class, 'showNote'])->name('notes.show');
Route::get('notes/{id}/export', [PurchaseController::class, 'exportSingleNote'])->name('notes.exportSingle');
Route::get('notes/export', [PurchaseController::class, 'exportNotes'])->name('notes.export');
Route::get('notes/{id}/print', [PurchaseController::class, 'printSingleNote'])->name('notes.printSingle');

Route::get('/invoices/search', [PurchaseController::class, 'searchInvoices'])->name('invoices.search');

Route::get('/receptions/create', [ReceptionController::class, 'create'])->name('receptions.create');
Route::post('/receptions', [ReceptionController::class, 'store'])->name('receptions.store');
Route::get('/receptions/{id}', [ReceptionController::class, 'show'])->name('receptions.show');
Route::get('/receptions/{id}/edit', [ReceptionController::class, 'edit'])->name('receptions.edit');
Route::put('/receptions/{id}', [ReceptionController::class, 'update'])->name('receptions.update');

Route::post('/magasin/generate-locations', [StoreController::class, 'generateLocations'])->name('generate.locations');


Route::get('/purchase-invoices/create', [PurchaseInvoiceController::class, 'create'])->name('purchase_invoices.create');


    // Factures achat
    Route::get('/invoices', [PurchaseController::class, 'invoicesList'])->name('invoices.list');
    Route::get('/invoices/create/direct/{order}', [PurchaseController::class, 'createDirectInvoice'])->name('invoices.create_direct');
    Route::get('/invoices/create/grouped', [PurchaseController::class, 'createGroupedInvoice'])->name('invoices.create_grouped');
    Route::get('/invoices/create/free', [PurchaseController::class, 'createFreeInvoice'])->name('invoices.create_free');
    Route::post('/invoices', [PurchaseController::class, 'storeInvoice'])->name('invoices.store');
    Route::get('/invoices/{id}/edit', [PurchaseController::class, 'editInvoice'])->name('invoices.edit');
    Route::put('/invoices/{numdoc}', [PurchaseController::class, 'updateInvoice'])->name('invoices.update');
    Route::get('/invoices/{id}/export', [PurchaseController::class, 'exportSingleInvoice'])->name('invoices.export_single');
    Route::get('/invoices/{id}/print', [PurchaseController::class, 'printSingleInvoice'])->name('invoices.print_single');
Route::get('/invoices/export', [PurchaseController::class, 'exportinvoices'])->name('invoices.export');
Route::get('/purchases/search', [PurchaseController::class, 'search'])->name('purchases.search');

Route::get('/setting', function () {
            return view('parametres');
    });


Route::post('/stock/update', [StockController::class, 'updateOrCreate'])->name('stock.update');
Route::post('/stock/movement', [StockMovementController::class, 'store'])->name('stock.movement.store');











// la partie vente

// Sales Orders
    Route::get('/sales', [SalesController::class, 'list'])->name('sales.list');
    Route::get('/sales/create', [SalesController::class, 'index'])->name('sales.create');
    Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');
    Route::get('/sales/{id}/edit', [SalesController::class, 'edit'])->name('sales.edit');
    Route::put('/sales/{numdoc}', [SalesController::class, 'update'])->name('sales.update');
    Route::post('/sales/{id}/validate', [SalesController::class, 'validateOrder'])->name('sales.validate');
Route::get('/export/{id}', [SalesController::class, 'exportSingle'])->name('sales.export_single');
    Route::get('/sales/print/{id}', [SalesController::class, 'printSingle'])->name('sales.print_single');


    Route::get('/items/stock-details', [SalesController::class, 'stockDetails'])->name('items.stock.details');



Route::prefix('delivery_notes')->group(function () {
    Route::get('/list', [DeliveryNotesController::class, 'list'])->name('delivery_notes.list');
    Route::get('/export/{id}', [DeliveryNotesController::class, 'exportSingle'])->name('delivery_notes.export_single');
    Route::get('/print/{id}', [DeliveryNotesController::class, 'printSingle'])->name('delivery_notes.print_single');
});


    // Delivery Notes
    Route::get('/sales/delivery/create', [SalesController::class, 'createDirectDeliveryNote'])->name('sales.delivery.create');
    Route::post('/sales/delivery', [SalesController::class, 'storeDirectDeliveryNote'])->name('sales.delivery.store');

    // Invoices
    Route::get('/sales/invoices', [SalesController::class, 'invoicesList'])->name('sales.invoices.list');
    Route::get('/sales/invoices/create/direct/{orderId}', [SalesController::class, 'createDirectInvoice'])->name('sales.invoices.create_direct');
    Route::get('/sales/invoices/create/grouped', [SalesController::class, 'createGroupedInvoice'])->name('sales.invoices.create_grouped');
    Route::get('/sales/invoices/create/free', [SalesController::class, 'createFreeInvoice'])->name('sales.invoices.create_free');
    Route::post('/sales/invoices', [SalesController::class, 'storeInvoice'])->name('sales.invoices.store');
    Route::get('/sales/invoices/{id}/edit', [SalesController::class, 'editInvoice'])->name('sales.invoices.edit');
    Route::put('/sales/invoices/{numdoc}', [SalesController::class, 'updateInvoice'])->name('sales.invoices.update');
    Route::get('/sales/invoices/{id}/export', [SalesController::class, 'exportSingleInvoice'])->name('sales.invoices.export');
    Route::get('/sales/invoices/{id}/print', [SalesController::class, 'printSingleInvoice'])->name('sales.invoices.print');

    // Quotes
    Route::get('/sales/quotes', [SalesController::class, 'quotesList'])->name('sales.quotes.list');
    Route::get('/sales/quotes/create', [SalesController::class, 'createQuote'])->name('sales.quotes.create');
    Route::post('/sales/quotes', [SalesController::class, 'storeQuote'])->name('sales.quotes.store');
    Route::post('/sales/quotes/{id}/convert', [SalesController::class, 'convertQuoteToOrder'])->name('sales.quotes.convert');
    Route::get('/sales/quotes/{id}/export', [SalesController::class, 'exportSingleQuote'])->name('sales.quotes.export');
    Route::get('/sales/quotes/{id}/print', [SalesController::class, 'printSingleQuote'])->name('sales.quotes.print');

    // Search Routes
    Route::get('/sales/search', [SalesController::class, 'search'])->name('sales.search');
    Route::get('/sales/items/search', [SalesController::class, 'searchItems'])->name('sales.items.search');
    Route::get('/sales/items/{itemId}/history', [SalesController::class, 'itemHistory'])->name('sales.items.history');











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

Route::get('/invoices_old', [InvoiceController::class, 'index'])->name('invoices.index');
Route::get('/invoices_old/{NumFacture}', [InvoiceController::class, 'show'])->name('invoices.show');


Route::post('/modify-password', [AuthController::class, 'modifyPassword'])->name('modify.password');

    
Route::get('/passwordform', function () {
    return view('modifypassword'); 
});


Route::get('/receptions', [ReceptionController::class, 'index'])->name('receptions.index');
Route::get('/receptions/{NumReception}', [ReceptionController::class, 'show'])->name('receptions.shownav');
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







    // Liste des utilisateurs
    Route::get('/users', [AuthController::class, 'index'])->name('users.index');

    // Création utilisateur
  Route::post('/users', [AuthController::class, 'store'])->name('users.store');

    // Mise à jour utilisateur (modification)
    Route::post('/users/{user}', [AuthController::class, 'update'])->name('users.update');

    // Suppression utilisateur
    Route::delete('/users/{user}', [AuthController::class, 'destroy'])->name('users.destroy');


    // Autres routes protégées...
});



// Routes pour les administrateurs
Route::get('/', [AuthController::class, 'loginFormAdmin'])->name('login.form.admin');
Route::post('/admin/login', [AuthController::class, 'loginAdmin'])->name('login.admin');
Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard')->middleware('auth');
Route::post('/admin/logout', [AuthController::class, 'logoutAdmin'])->name('logout.admin');







