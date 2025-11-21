<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ArticleImportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvoirController;
use App\Http\Controllers\BotController;
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
use App\Http\Controllers\GeneralAccountsController;
use App\Http\Controllers\ImmatController;
use App\Http\Controllers\PayementmodeController;
use App\Http\Controllers\PayementtermController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanificationTourneeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseInvoiceController;
use App\Http\Controllers\PurchaseProjectController;
use App\Http\Controllers\PurchaseSettingsController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SalesInvoicesController;
use App\Http\Controllers\SalesReturnController;
use App\Http\Controllers\SoucheController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TvaController;
use App\Http\Controllers\TvaGroupController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VoiceCommandController;
use App\Models\Arrivage;
use App\Models\Brand;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\PurchaseOrder;
use App\Models\Store;
use App\Models\TvaGroup;
use App\Models\Unit;
use App\Models\User;
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
// créer un client depuis la page vente
Route::get('/newcustomer', [CustomerController::class, 'newforsale'])->name('customer.new');

Route::post('/customer', [CustomerController::class, 'store'])->name('customer.store');
Route::delete('/customer/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
Route::put('/customer/{id}', [CustomerController::class, 'update'])->name('customer.update');


// Vehicle routes vehicule
Route::post('/customers/{customer}/vehicles', [CustomerController::class, 'storeVehicle'])->name('customer.vehicle.store');
Route::delete('/customers/{customer}/vehicles/{vehicle}', [CustomerController::class, 'destroyVehicle'])->name('customer.vehicle.destroy');
Route::get('/customers/{customer}/vehicles/{vehicle}/catalog', [CustomerController::class, 'viewCatalog'])->name('customer.vehicle.catalog');


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

// avis de retrait
Route::post('/purchases/{id}/withdrawal_notice', [PurchaseController::class, 'withdrawalNotice'])->name('purchases.withdrawal_notice');



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

Route::get('/sales/printsansref/{id}', [SalesController::class, 'printSinglesansref'])->name('sales.print_singlesansref');


    Route::get('/items/stock-details', [SalesController::class, 'stockDetails'])->name('items.stock.details');




        Route::post('/salesdevis', [SalesController::class, 'storedevis'])->name('devis.store');







Route::prefix('delivery_notes')->group(function () {
    Route::get('/list', [DeliveryNotesController::class, 'list'])->name('delivery_notes.list');
    Route::get('/export/{id}', [DeliveryNotesController::class, 'exportSingle'])->name('delivery_notes.export_single');
    Route::get('/print/{id}', [DeliveryNotesController::class, 'printSingle'])->name('delivery_notes.print_single');

        Route::get('/{id}/edit', [DeliveryNotesController::class, 'edit'])->name('delivery_notes.edit');
    Route::put('/{id}', [DeliveryNotesController::class, 'update'])->name('delivery_notes.update');
Route::post('/delivery_notes/{id}/validate', [DeliveryNotesController::class, 'markAsValidated'])->name('delivery_notes.validate');
Route::post('/delivery_notes/{id}/ship', [DeliveryNotesController::class, 'markAsShipped'])->name('delivery_notes.ship');

    Route::put('/delivery_notes/{id}/cancel', [DeliveryNotesController::class, 'cancel'])->name('delivery_notes.cancel');

// imprimer bordereau d envoi
Route::post('/delivery_notes/{id}/shipping_note', [DeliveryNotesController::class, 'shippingNote'])->name('delivery_notes.shipping_note');

        Route::get('/returns/list', [DeliveryNotesController::class, 'returnsList'])->name('delivery_notes.salesreturns.list');
    Route::get('/{id}/returns/create', [DeliveryNotesController::class, 'createReturn'])->name('delivery_notes.salesreturns.create');
    Route::post('/{id}/returns', [DeliveryNotesController::class, 'storeReturn'])->name('delivery_notes.salesreturns.store');
    Route::get('/returns/{id}', [DeliveryNotesController::class, 'showReturn'])->name('delivery_notes.salesreturns.show');
    Route::get('/returns/{id}/export', [DeliveryNotesController::class, 'exportSingleReturn'])->name('delivery_notes.salesreturns.export_single');
    Route::get('/returns/{id}/print', [DeliveryNotesController::class, 'printSingleReturn'])->name('delivery_notes.salesreturns.print_single');
    Route::get('/returns/{id}/edit', [DeliveryNotesController::class, 'editReturn'])->name('delivery_notes.returns.edit');
    Route::put('/returns/{id}', [DeliveryNotesController::class, 'updateReturn'])->name('delivery_notes.returns.update');
    

});

Route::post('/purchase/{id}/ship', [PurchaseController::class, 'markAsShipped'])->name('purchase.ship');



    // Delivery Notes
    Route::get('/sales/delivery/create', [SalesController::class, 'createDirectDeliveryNote'])->name('sales.delivery.create');
    Route::post('/sales/delivery', [SalesController::class, 'storeDirectDeliveryNote'])->name('sales.delivery.store');



// 1. Création RAPIDE depuis une simple immatriculation (API plaque ou fallback)
Route::post('/customers/{customer}/vehicles/from-plate', [CustomerController::class, 'storeFromPlate'])
    ->name('customers.vehicles.from-plate');

// 2. Création COMPLÈTE via TecDoc (le modal classique)
Route::post('/customers/{customer}/vehicles/quick-store', [CustomerController::class, 'quickStoreVehicle'])
    ->name('customers.vehicles.quick-store');



// Sales Invoices
Route::get('/salesinvoices', [SalesInvoicesController::class, 'invoicesList'])->name('salesinvoices.index');
Route::get('/salesinvoices/create_direct/{deliveryNoteId}', [SalesInvoicesController::class, 'createDirectInvoice'])->name('salesinvoices.create_direct');
Route::post('/salesinvoices/store_direct/{deliveryNoteId}', [SalesInvoicesController::class, 'storeDirectInvoice'])->name('salesinvoices.store_direct');
Route::get('/salesinvoices/create_grouped', [SalesInvoicesController::class, 'createGroupedInvoice'])->name('salesinvoices.create_grouped');
Route::post('/salesinvoices/store_grouped', [SalesInvoicesController::class, 'storeGroupedInvoice'])->name('salesinvoices.store_grouped');
Route::get('/salesinvoices/create_free', [SalesInvoicesController::class, 'createFreeInvoice'])->name('salesinvoices.create_free');
Route::post('/salesinvoices/store_free', [SalesInvoicesController::class, 'storeFreeInvoice'])->name('salesinvoices.store_free');
Route::get('/salesinvoices/{id}/edit', [SalesInvoicesController::class, 'editInvoice'])->name('salesinvoices.edit');
Route::put('/salesinvoices/{numdoc}', [SalesInvoicesController::class, 'updateInvoice'])->name('salesinvoices.update');
Route::put('/salesinvoices/{id}/paid', [SalesInvoicesController::class, 'markAsPaid'])->name('salesinvoices.markAsPaid');
Route::get('/salesinvoices/{id}/print', [SalesInvoicesController::class, 'printSingleInvoice'])->name('salesinvoices.print');
Route::get('/salesinvoices/{id}/printduplicata', [SalesInvoicesController::class, 'printSingleInvoiceduplicata'])->name('salesinvoices.printduplicata');
Route::get('/salesinvoices/{id}/printsansref', [SalesInvoicesController::class, 'printSingleInvoicesansref'])->name('salesinvoices.printsansref');
Route::get('/salesinvoices/{id}/printsansrem', [SalesInvoicesController::class, 'printSingleInvoicesansrem'])->name('salesinvoices.printsansrem');
Route::get('/salesinvoices/{id}/printsans2', [SalesInvoicesController::class, 'printSingleInvoicesans2'])->name('salesinvoices.printsans2');

Route::get('/salesinvoices/{id}/export', [SalesInvoicesController::class, 'exportSingleInvoice'])->name('salesinvoices.export_single');
Route::get('/salesinvoices/export', [SalesInvoicesController::class, 'exportInvoices'])->name('salesinvoices.export');
Route::get('/sales/orders/search', [SalesInvoicesController::class, 'search'])->name('sales.orders.search');




// Payments reglement
Route::get('/paymentlist', [PaymentController::class, 'index'])->name('payments.index');
Route::get('/paymentlist/export/pdf', [PaymentController::class, 'exportPdf'])->name('payments.export_pdf');
Route::get('/paymentlist/export/excel', [PaymentController::class, 'exportExcel'])->name('payments.export_excel');
Route::post('/salesinvoices/{id}/pay', [PaymentController::class, 'makePayment'])->name('salesinvoices.make_payment');
Route::post('/purchaseinvoices/{id}/pay', [PaymentController::class, 'makePaymentPurchase'])->name('purchaseinvoices.make_payment');
Route::put('/purchaseinvoices/{id}/mark-as-paid', [PaymentController::class, 'markAsPaid'])->name('purchaseinvoices.markAsPaid');

    Route::post('/salesnotes/{id}/make_payment', [PaymentController::class, 'makePaymentSalesNote'])->name('salesnotes.make_payment');
 Route::post('/notes/{id}/make_payment', [PaymentController::class, 'makePaymentPurchaseNote'])->name('notes.make_payment');


 Route::post('/payments/{payment}/transfer', [PaymentController::class, 'transfer'])->name('payments.transfer');
Route::delete('/transfers/{transfer}/cancel', [PaymentController::class, 'cancelTransfer'])->name('payments.cancel_transfer');
// New routes for deposit and withdraw
Route::post('/payments/deposit', [PaymentController::class, 'deposit'])->name('payments.deposit');
Route::post('/payments/withdraw', [PaymentController::class, 'withdraw'])->name('payments.withdraw');

// annulation reglement 
Route::post('/payments/{id}/cancel', [PaymentController::class, 'cancelPayment'])->name('payments.cancel');


// mail messages
Route::post('/salesinvoices/{id}/send-email', [SalesInvoicesController::class, 'sendEmail'])->name('salesinvoices.sendEmail');
Route::post('/salesinvoices/{id}/send-order-ready', 
    [SalesInvoicesController::class, 'sendOrderReadyEmail'])
    ->name('salesinvoices.sendOrderReadyEmail');




 
// General Accounts Routes
Route::get('/generalaccounts', [GeneralAccountsController::class, 'index'])->name('generalaccounts.index');
Route::post('/generalaccounts', [GeneralAccountsController::class, 'store'])->name('generalaccounts.store');
Route::put('/generalaccounts/{id}', [GeneralAccountsController::class, 'update'])->name('generalaccounts.update');
Route::delete('/generalaccounts/{id}', [GeneralAccountsController::class, 'destroy'])->name('generalaccounts.destroy');

Route::get('/generalaccounts/{id}/reconcile', [GeneralAccountsController::class, 'reconcile'])->name('generalaccounts.reconcile');
Route::post('/generalaccounts/{id}/reconcile', [GeneralAccountsController::class, 'storeReconciliation'])->name('generalaccounts.storeReconciliation');
Route::get('/generalaccounts/{id}/transactions', [GeneralAccountsController::class, 'transactions'])->name('generalaccounts.transactions');




Route::get('/customers/export', [CustomerController::class, 'export'])->name('customers.export');
Route::get('/suppliers/export', [SupplierController::class, 'export'])->name('suppliers.export');



// routes factures fournisseurs
Route::post('/invoices/{id}/upload_supplier_invoice', [PurchaseController::class, 'uploadSupplierInvoice'])->name('invoices.upload_supplier_invoice');
Route::get('/invoices/{id}/download_supplier_invoice', [PurchaseController::class, 'downloadSupplierInvoice'])->name('invoices.download_supplier_invoice');
Route::delete('/invoices/{id}/delete_supplier_invoice', [PurchaseController::class, 'deleteSupplierInvoice'])->name('invoices.delete_supplier_invoice');


// ecriture client historique client
Route::get('/customers/{customer}/accounting-entries', [CustomerController::class, 'getAccountingEntries'])->name('customer.accounting-entries');
// ecriture toutes client historique client
Route::get('/allcustomers/accounting-entries', [CustomerController::class, 'getAllAccountingEntries'])->name('allcustomer.accounting-entries');
// ecriture toutes client historique client HT pour compte general vente marchandises
Route::get('/allcustomers/accounting-entriesHT', [CustomerController::class, 'getAllAccountingEntriesHT'])->name('allcustomer.accounting-entriesHT');


// ecriture client historique fournisseurs
Route::get('/suppliers/{customer}/accounting-entries', [SupplierController::class, 'getAccountingEntries'])->name('supplier.accounting-entries');
// ecriture toutes fournisseur historique client
Route::get('/allcsuppliers/accounting-entries', [SupplierController::class, 'getAllAccountingEntries'])->name('allsupplier.accounting-entries');
// ecriture toutes client historique client HT pour compte general vente marchandises
Route::get('/allcsuppliers/accounting-entriesHT', [SupplierController::class, 'getAllAccountingEntriesHT'])->name('allsupplier.accounting-entriesHT');

// ecriture compte tva collectée
Route::get('/TVA/accounting-entries', [GeneralAccountsController::class, 'getAllAccountingEntriesTVA'])->name('TVA.accounting-entries');
// ecriture compte tva déductible
Route::get('/TVAD/accounting-entries', [GeneralAccountsController::class, 'getAllAccountingEntriesTVAD'])->name('TVAD.accounting-entries');
// ecriture compte stock
Route::get('/stock/accounting-entries', [GeneralAccountsController::class, 'getAllAccountingEntriesStock'])->name('stock.accounting-entries');
Route::get('/stock/accounting-entries/export', [GeneralAccountsController::class, 'exportStockEntries'])
    ->name('stock.accounting-entries.export');




// recherche client 
Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');

// validation comptable
Route::post('/payments/{payment}/validate', [PaymentController::class, 'validatePayment'])
    ->name('payments.validate');



    // routes pour test new recherche immatr
    Route::get('/vehicle/catalog', [ImmatController::class, 'index'])->name('vehicle.catalog.form');
Route::post('/vehicle/catalog/fetch', [ImmatController::class, 'fetchByPlate'])->name('vehicle.catalog.fetch');
Route::post('/tecdoc/search-by-plate', [VehicleController::class, 'searchByPlate'])->name('tecdoc.search.plate');

//Routes pour Nego bot negobot
Route::get('/voice-command', [VoiceCommandController::class, 'sendSentenceToModel'])->name('predict');
Route::post('/chat-bot', [BotController::class, 'callBot'])->name('chat-bot');


// Sales Notes (Avoirs)
// Route::get('/salesnotes/create_from_return', [SalesInvoicesController::class, 'createReturnNote'])->name('salesnotes.create_return');
// Route::post('/salesnotes/store_return', [SalesInvoicesController::class, 'storeReturnNote'])->name('salesnotes.store_return');
// Route::get('/salesnotes/create_from_invoice', [SalesInvoicesController::class, 'createInvoiceNote'])->name('salesnotes.create_invoice');
// Route::post('/salesnotes/store_invoice', [SalesInvoicesController::class, 'storeInvoiceNote'])->name('salesnotes.store_invoice');
// Route::get('/salesnotes/return/lines', [SalesInvoicesController::class, 'getReturnLines'])->name('salesnotes.return_lines');
// Route::get('/salesnotes/invoice/lines', [SalesInvoicesController::class, 'getInvoiceLines'])->name('salesnotes.invoice_lines');
// Route::get('/salesnotes', [SalesInvoicesController::class, 'notesList'])->name('salesnotes.list');
// Route::get('/salesnotes/{id}/print', [SalesInvoicesController::class, 'printSingleNote'])->name('salesnotes.print_single');
// Route::get('/salesnotes/{id}/export', [SalesInvoicesController::class, 'printSingleNote'])->name('salesnotes.export_single');

// Sales Notes (Avoirs) avoir vente nouveau
Route::get('/salesnotes/source/documents', [SalesInvoicesController::class, 'getSourceDocuments'])->name('salesnotes.source.documents');
Route::get('/salesnotes/source/lines', [SalesInvoicesController::class, 'getSourceLines'])->name('salesnotes.source.lines');
Route::get('/salesnotes/create', [SalesInvoicesController::class, 'createSalesNote'])->name('salesnotes.create');
Route::post('/salesnotes/store', [SalesInvoicesController::class, 'storeSalesNote'])->name('salesnotes.store_sales_note');


    Route::get('/salesnotes/list', [SalesInvoicesController::class, 'notesList'])->name('salesnotes.list');
    Route::get('/salesnotes/edit/{id}', [SalesInvoicesController::class, 'editSalesNote'])->name('salesnotes.edit');
    Route::put('/salesnotes/update/{id}', [SalesInvoicesController::class, 'updateSalesNote'])->name('salesnotes.update');
    Route::get('/salesnotes/export', [SalesInvoicesController::class, 'exportNotes'])->name('salesnotes.export');
    Route::get('/salesnotes/export/{id}', [SalesInvoicesController::class, 'exportSingleNote'])->name('salesnotes.export_single');
    Route::get('/salesnotes/print/{id}', [SalesInvoicesController::class, 'printSingleNote'])->name('salesnotes.print_single');


    Route::get('/vehicles/{vehicle}/history', [VehicleController::class, 'index'])
     ->name('vehicles.history');
Route::get('/vehicles/{vehicle}/history/pdf', [VehicleController::class, 'pdf'])->name('vehicles.history.pdf');





Route::post('/sales/delivery/store-and-invoice', [SalesController::class, 'storedeliveryandinvoice'])->name('sales.delivery.store_and_invoice');


    // Quotes - projet de commande
    Route::get('/sales/quotes', [SalesController::class, 'quotesList'])->name('sales.quotes.list');
    Route::get('/sales/quotes/create', [SalesController::class, 'createQuote'])->name('sales.quotes.create');
    Route::post('/sales/quotes', [SalesController::class, 'storeQuote'])->name('sales.quotes.store');
    Route::post('/sales/quotes/{id}/convert', [SalesController::class, 'convertQuoteToOrder'])->name('sales.quotes.convert');
    Route::get('/sales/quotes/{id}/export', [SalesController::class, 'exportSingleQuote'])->name('sales.quotes.export');
    Route::get('/sales/quotes/{id}/print', [SalesController::class, 'printSingleQuote'])->name('sales.quotes.print');



// suivi livraison
    Route::get('/planification-tournee', [PlanificationTourneeController::class, 'index'])->name('planification.tournee.index');
    Route::get('/planification-tournee/creer', [PlanificationTourneeController::class, 'create'])->name('planification.tournee.create');
    Route::post('/planification-tournee', [PlanificationTourneeController::class, 'store'])->name('planification.tournee.store');
    Route::get('/planification-tournee/{id}/editer', [PlanificationTourneeController::class, 'edit'])->name('planification.tournee.edit');
    Route::put('/planification-tournee/{id}', [PlanificationTourneeController::class, 'update'])->name('planification.tournee.update');
    Route::delete('/planification-tournee/{id}', [PlanificationTourneeController::class, 'destroy'])->name('planification.tournee.destroy');
    Route::get('/planification-tournee/planning-chauffeur', [PlanificationTourneeController::class, 'planningChauffeur'])->name('planification.tournee.planning.chauffeur');
    Route::post('/planification-tournee/scan', [PlanificationTourneeController::class, 'scan'])->name('planification.tournee.scan');
    Route::post('/planification-tournee/{id}/valider', [PlanificationTourneeController::class, 'valider'])->name('planification.tournee.valider');
    Route::get('/planification-tournee/rapport', [PlanificationTourneeController::class, 'rapport'])->name('planification.tournee.rapport');








    // Search Routes
    Route::get('/sales/search', [SalesController::class, 'search'])->name('sales.search');
    Route::get('/sales/items/search', [SalesController::class, 'searchItems'])->name('sales.items.search');
    Route::get('/sales/items/{itemId}/history', [SalesController::class, 'itemHistory'])->name('sales.items.history');


Route::get('/analytics', [AnalyticsController::class, 'index'])
    ->name('analytics');








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


Route::get('/contact', [SupportController::class, 'index'])->name('contact');
Route::post('/contact', [SupportController::class, 'send'])->name('contact.send');
// Back-office (admin)
Route::get('/tickets', [SupportController::class, 'list'])->name('tickets.list');
Route::put('/tickets/{id}/status', [SupportController::class, 'updateStatus'])->name('tickets.updateStatus');



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


Route::get('/receptions/{id}/scan', [ReceptionController::class, 'scan'])->name('receptions.scan');
Route::post('/receptions/{id}/scan', [ReceptionController::class, 'scanReception'])->name('receptions.scan.update');
Route::get('/receptions/{id}/generate-pdf', [ReceptionController::class, 'generatePdf'])->name('receptions.generate_pdf');



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

// rechercher les vehicules d un client
Route::get('/customers/{id}/vehicles', [CustomerController::class, 'getVehicles'])->name('customer.vehicles');




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









        Route::get('/articles/import', [ArticleImportController::class, 'showForm'])->name('articles.import.form');
    Route::get('/articles/import/template', [ArticleImportController::class, 'downloadTemplate'])->name('articles.import.template');
    Route::post('/articles/import', [ArticleImportController::class, 'import'])->name('articles.import');
Route::post('/articles/preview', [ArticleImportController::class, 'preview'])->name('articles.import.preview');









    
    // Autres routes protégées...
});



// Routes pour les administrateurs
Route::get('/', [AuthController::class, 'loginFormAdmin'])->name('login.form.admin');
Route::post('/admin/login', [AuthController::class, 'loginAdmin'])->name('login.admin');
Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard')->middleware('auth');
Route::post('/admin/logout', [AuthController::class, 'logoutAdmin'])->name('logout.admin');






// tv client
Route::get('/tvclient', [DeliveryNotesController::class, 'tvClient'])->name('tvclient');
Route::get('/tvclient/data', [DeliveryNotesController::class, 'tvClientData'])->name('tvclient.data');





