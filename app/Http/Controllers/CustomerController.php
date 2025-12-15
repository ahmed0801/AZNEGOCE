<?php

namespace App\Http\Controllers;

use App\Exports\CustomersExport;
use App\Models\Customer;
use App\Models\DiscountGroup;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMode;
use App\Models\PaymentTerm;
use App\Models\SalesNote;
use App\Models\Souche;
use App\Models\TvaGroup;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log; // Added Log facade

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with(['vehicles', 'tvaGroup', 'discountGroup', 'paymentMode', 'paymentTerm']);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('phone1', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('city', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('blocked', $request->status == 'blocked' ? 1 : 0);
        }

        if ($request->filled('city')) {
            $query->where('city', 'LIKE', "%{$request->city}%");
        }

        if ($request->filled('min_solde')) {
            $query->where('solde', '>=', $request->min_solde);
        }

        if ($request->filled('max_solde')) {
            $query->where('solde', '<=', $request->max_solde);
        }

        $customers = $query->orderBy('name')->paginate(20);

        $tvaGroups = TvaGroup::all();
        $discountGroups = DiscountGroup::all();
        $paymentModes = PaymentMode::where('type', 'encaissement')->get();

        $paymentTerms = PaymentTerm::all();

        // Villes uniques pour le filtre
        $cities = Customer::distinct()->pluck('city')->filter()->sort()->values();

        // Fetch TecDoc brands
        $response = Http::withHeaders([
            'X-Api-Key' => env('TECDOC_API_KEY', '2BeBXg6LDMZPdqWdaoq9CP19qGL6bTDHB9qBJEu6K264jC2Yv8wg')
        ])->post('https://webservice.tecalliance.services/pegasus-3-0/services/TecdocToCatDLB.jsonEndpoint', [
            "getLinkageTargets" => [
                "provider" => env('TECDOC_PROVIDER_ID', 23454),
                "linkageTargetCountry" => env('TECDOC_COUNTRY', 'TN'),
                "lang" => env('TECDOC_LANG', 'fr'),
                "linkageTargetType" => "P",
                "perPage" => 0,
                "page" => 1,
                "includeMfrFacets" => true
            ]
        ]);

        $brands = $response->successful() && isset($response->json()['mfrFacets']['counts'])
            ? $response->json()['mfrFacets']['counts']
            : [];

        return view('customers', compact(
            'customers', 
            'tvaGroups', 
            'discountGroups', 
            'paymentModes', 
            'paymentTerms', 
            'brands',
            'cities'
        ));
    }

   
    

    public function export(Request $request)
{
    // Passer directement la Request à l'export
    return \Maatwebsite\Excel\Facades\Excel::download(
        new CustomersExport($request), 
        'clients_' . date('Y-m-d_H-i-s') . '.xlsx'
    );
}



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone1' => 'nullable|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'address_delivery' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'matfiscal' => 'nullable|string|max:100',
            'bank_no' => 'nullable|string|max:100',
            'solde' => 'nullable|numeric|min:0',
            'plafond' => 'nullable|numeric|min:0',
            'risque' => 'nullable|string|max:100',
            'tva_group_id' => 'nullable|exists:tva_groups,id',
            'discount_group_id' => 'nullable|exists:discount_groups,id',
            'payment_mode_id' => 'nullable|exists:payment_modes,id',
            'payment_term_id' => 'nullable|exists:payment_terms,id',
            'type' => 'nullable|in:particulier,jobber,professionnel', // Validation
        ]);

        $souche = Souche::where('type', 'client')->first();
        if (!$souche) {
            return back()->with('error', 'Souche client manquante');
        }

        $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
        $code = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

        $customer = new Customer($request->except('code'));
        $data['type'] = $request->type ?? 'particulier'; // Forcer par défaut
        $customer->code = $code;
        $customer->save();

        $souche->last_number += 1;
        $souche->save();

        return redirect()->back()->with('success', 'Client créé avec succès');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone1' => 'nullable|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'address_delivery' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'matfiscal' => 'nullable|string|max:100',
            'bank_no' => 'nullable|string|max:100',
            'solde' => 'nullable|numeric|min:0',
            'plafond' => 'nullable|numeric|min:0',
            'risque' => 'nullable|string|max:100',
            'tva_group_id' => 'nullable|exists:tva_groups,id',
            'discount_group_id' => 'nullable|exists:discount_groups,id',
            'payment_mode_id' => 'nullable|exists:payment_modes,id',
            'payment_term_id' => 'nullable|exists:payment_terms,id',
            'type' => 'required|in:particulier,jobber,professionnel',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->all());
        return redirect()->route('customer.index')->with('success', 'Client modifié avec succès');
    }

    public function destroy($id)
    {
        Customer::findOrFail($id)->delete();
        return redirect()->route('customer.index')->with('success', 'Client supprimé avec succès');
    }

    public function storeVehicle(Request $request, $customer)
    {
        $request->validate([
            'brand_id' => 'required|integer',
            'brand_name' => 'required|string|max:255',
            'model_id' => 'required|integer',
            'model_name' => 'required|string|max:255',
            'engine_id' => 'required|integer',
            'engine_description' => 'required|string|max:255',
            'linkage_target_id' => 'required|integer',
            'license_plate' => 'required|string|max:50',
        ]);

        $customer = Customer::findOrFail($customer);
        Vehicle::create([
            'customer_id' => $customer->id,
            'brand_id' => $request->brand_id,
            'brand_name' => $request->brand_name,
            'model_id' => $request->model_id,
            'model_name' => $request->model_name,
            'engine_id' => $request->engine_id,
            'engine_description' => $request->engine_description,
            'linkage_target_id' => $request->linkage_target_id,
            'license_plate' => $request->license_plate,
        ]);

        return redirect()->route('customer.index')->with('success', 'Véhicule associé avec succès');
    }

    public function destroyVehicle($customer, $vehicle)
    {
        $vehicle = Vehicle::where('customer_id', $customer)->findOrFail($vehicle);
        $vehicle->delete();
        return redirect()->route('customer.index')->with('success', 'Véhicule supprimé avec succès');
    }

    public function viewCatalog($customer, $vehicle)
    {
        $vehicle = Vehicle::where('customer_id', $customer)->findOrFail($vehicle);
        return view('vehicles.catalog', compact('vehicle'));
    }


// recherche pour BL
        public function getVehicles(Request $request, $id)
    {
        $customer = Customer::with('vehicles')->findOrFail($id);
        return response()->json($customer->vehicles->map(function ($vehicle) {
            return [
                'id' => $vehicle->id,
                'license_plate' => $vehicle->license_plate,
                'brand_name' => $vehicle->brand_name,
                'model_name' => $vehicle->model_name,
                'engine_description' => $vehicle->engine_description,
            ];
        }));
    }





  public function getAccountingEntries($customerId)
    {
        try {
            Log::info("Fetching accounting entries for customer ID: $customerId");

            // Check if customer exists
            $customer = Customer::find($customerId);
            if (!$customer) {
                Log::warning("Customer not found for ID: $customerId");
                return response()->json(['entries' => []], 200);
            }

            // Fetch invoices
            $invoices = Invoice::where('customer_id', $customerId)
                ->select('id', 'numdoc', 'invoice_date as date', 'total_ttc as amount', 'paid', 'numdoc as reference')
                ->get()
                ->map(function ($invoice) {
                    return [
                        'type' => 'Facture',
                        'numdoc' => $invoice->numdoc ?? '-',
                        'date' => $invoice->date ? \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') : '-',
                        'amount' => is_numeric($invoice->amount) ? (float) $invoice->amount : 0,
                        'status' => $invoice->paid ? 'Payée' : 'Non payée',
                        'reference' => $invoice->reference ?? '-'
                    ];
                });

            // Fetch sales notes
            $salesNotes = SalesNote::where('customer_id', $customerId)
                ->select('id', 'numdoc', 'note_date as date', 'total_ttc as amount', 'paid', 'numdoc as reference')
                ->get()
                ->map(function ($note) {
                    return [
                        'type' => 'Avoir',
                        'numdoc' => $note->numdoc ?? '-',
                        'date' => $note->date ? \Carbon\Carbon::parse($note->date)->format('d/m/Y') : '-',
                        'amount' => is_numeric($note->amount) ? -(float) $note->amount : 0,
                        'status' => $note->paid ? 'Payée' : 'Non payée',
                        'reference' => $note->reference ?? '-'
                    ];
                });

            // Fetch payments
            $payments = Payment::where('customer_id', $customerId)
                ->select('id',  'payment_mode', 'reference', 'payment_date as date', 'amount', 'reconciled as status', 'lettrage_code as reference')
                ->get()
                ->map(function ($payment) {
                    return [
                        'type' => $payment->payment_mode,
                        'numdoc' => $payment->reference ?? '-',
                        'date' => $payment->date ? \Carbon\Carbon::parse($payment->date)->format('d/m/Y') : '-',
                        'amount' => is_numeric($payment->amount) ? (float) $payment->amount : 0,
                        'status' => $payment->status ? 'Validé' : 'Validé',
                    ];
                });

            // Merge and sort entries
            $entries = $invoices->merge($salesNotes)->merge($payments)->sortByDesc('date')->values();

            Log::info("Successfully fetched accounting entries for customer ID: $customerId", ['entry_count' => $entries->count()]);

            return response()->json(['entries' => $entries], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching accounting entries for customer ID: $customerId", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Erreur serveur: Impossible de charger les écritures comptables'], 500);
        }
    }








     public function getAllAccountingEntries()
{
    try {
        Log::info("Fetching all accounting entries");

        // Fetch invoices with customer relation
        $invoices = Invoice::with('customer')
            ->select('id', 'customer_id', 'numdoc', 'invoice_date as date', 'total_ttc as amount', 'paid', 'numdoc as reference')
            ->get()
            ->map(function ($invoice) {
                return [
                    'type' => 'Facture',
                    'customer_id' => $invoice->customer_id,
                    'customer_name' => $invoice->customer ? $invoice->customer->name : '-',
                    'numdoc' => $invoice->numdoc ?? '-',
                    'date' => $invoice->date ? \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') : '-',
                    'amount' => is_numeric($invoice->amount) ? (float) $invoice->amount : 0,
                    'status' => $invoice->paid ? 'Payée' : 'Non payée',
                    'reference' => $invoice->reference ?? '-'
                ];
            });

        // Fetch sales notes with customer relation
        $salesNotes = SalesNote::with('customer')
            ->select('id', 'customer_id', 'numdoc', 'note_date as date', 'total_ttc as amount', 'paid', 'numdoc as reference')
            ->get()
            ->map(function ($note) {
                return [
                    'type' => 'Avoir',
                    'customer_id' => $note->customer_id,
                    'customer_name' => $note->customer ? $note->customer->name : '-',
                    'numdoc' => $note->numdoc ?? '-',
                    'date' => $note->date ? \Carbon\Carbon::parse($note->date)->format('d/m/Y') : '-',
                    'amount' => is_numeric($note->amount) ? -(float) $note->amount : 0,
                    'status' => $note->paid ? 'Payée' : 'Non payée',
                    'reference' => $note->reference ?? '-'
                ];
            });

        // Fetch payments with customer relation
        $payments = Payment::with('customer')
        ->where('customer_id','!=',null)
            ->select('id', 'customer_id', 'payment_mode', 'reference', 'payment_date as date', 'amount', 'reconciled as status', 'lettrage_code as reference')
            ->get()
            ->map(function ($payment) {
                return [
                    'type' => $payment->payment_mode,
                    'customer_id' => $payment->customer_id,
                    'customer_name' => $payment->customer ? $payment->customer->name : '-',
                    'numdoc' => $payment->reference ?? '-',
                    'date' => $payment->date ? \Carbon\Carbon::parse($payment->date)->format('d/m/Y') : '-',
                    'amount' => is_numeric($payment->amount) ? (float) $payment->amount : 0,
                    'status' => $payment->status ? 'Validé' : 'Validé',
                    'reference' => $payment->reference ?? '-'
                ];
            });

        // Merge and sort entries
        $entries = $invoices->merge($salesNotes)->merge($payments)->sortByDesc('date')->values();

        Log::info("Successfully fetched all accounting entries", ['entry_count' => $entries->count()]);

        return response()->json(['entries' => $entries], 200);
    } catch (\Exception $e) {
        Log::error("Error fetching all accounting entries", [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => 'Erreur serveur: Impossible de charger les écritures comptables'], 500);
    }
}









public function getAllAccountingEntriesHT()
{
    try {
        Log::info("Fetching all accounting entries");

        // Fetch invoices with customer relation
        $invoices = Invoice::with('customer')
            ->select('id', 'customer_id', 'numdoc', 'invoice_date as date', 'total_ht as amount', 'paid', 'numdoc as reference')
            ->get()
            ->map(function ($invoice) {
                return [
                    'type' => 'Facture',
                    'customer_id' => $invoice->customer_id,
                    'customer_name' => $invoice->customer ? $invoice->customer->name : '-',
                    'numdoc' => $invoice->numdoc ?? '-',
                    'date' => $invoice->date ? \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') : '-',
                    'amount' => is_numeric($invoice->amount) ? (float) $invoice->amount : 0,
                    'status' => $invoice->paid ? 'Payée' : 'Non payée',
                    'reference' => $invoice->reference ?? '-'
                ];
            });

        // Fetch sales notes with customer relation
        $salesNotes = SalesNote::with('customer')
            ->select('id', 'customer_id', 'numdoc', 'note_date as date', 'total_ht as amount', 'paid', 'numdoc as reference')
            ->get()
            ->map(function ($note) {
                return [
                    'type' => 'Avoir',
                    'customer_id' => $note->customer_id,
                    'customer_name' => $note->customer ? $note->customer->name : '-',
                    'numdoc' => $note->numdoc ?? '-',
                    'date' => $note->date ? \Carbon\Carbon::parse($note->date)->format('d/m/Y') : '-',
                    'amount' => is_numeric($note->amount) ? -(float) $note->amount : 0,
                    'status' => $note->paid ? 'Payée' : 'Non payée',
                    'reference' => $note->reference ?? '-'
                ];
            });

        

        // Merge and sort entries
        $entries = $invoices->merge($salesNotes)->sortByDesc('date')->values();

        Log::info("Successfully fetched all accounting entries", ['entry_count' => $entries->count()]);

        return response()->json(['entries' => $entries], 200);
    } catch (\Exception $e) {
        Log::error("Error fetching all accounting entries", [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => 'Erreur serveur: Impossible de charger les écritures comptables'], 500);
    }
}











   public function search(Request $request)
{
    $query = $request->input('query');
    $customers = Customer::with('tvaGroup')
        ->where('name', 'LIKE', "%{$query}%")
        ->orWhere('code', 'LIKE', "%{$query}%")
        ->orWhere('email', 'LIKE', "%{$query}%")
        ->take(50)
        ->latest()
        ->get()
        ->map(function ($customer) {
            return [
                'id' => $customer->id,
                'text' => ($customer->code ? $customer->code . ' ⭆ ' : '') . $customer->name . ($customer->blocked ? ' 🔒' : ''),
                'tva' => $customer->tvaGroup->rate ?? 0,
                'solde' => $customer->solde ?? 0,
                'code' => $customer->code ?? '',
                'name' => $customer->name,
                'email' => $customer->email ?? '',
                'phone1' => $customer->phone1 ?? '',
                'phone2' => $customer->phone2 ?? '',
                'address' => $customer->address ?? '',
                'address_delivery' => $customer->address_delivery ?? '',
                'city' => $customer->city ?? '',
                'country' => $customer->country ?? '',
                'type' => $customer->type,
                'blocked' => $customer->blocked,
                'disabled' => $customer->blocked // Add disabled flag for Select2
            ];
        });

    return response()->json($customers);
}












 public function newforsale(Request $request)
    {
        $query = Customer::with(['vehicles', 'tvaGroup', 'discountGroup', 'paymentMode', 'paymentTerm']);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('phone1', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('city', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('blocked', $request->status == 'blocked' ? 1 : 0);
        }

        if ($request->filled('city')) {
            $query->where('city', 'LIKE', "%{$request->city}%");
        }

        if ($request->filled('min_solde')) {
            $query->where('solde', '>=', $request->min_solde);
        }

        if ($request->filled('max_solde')) {
            $query->where('solde', '<=', $request->max_solde);
        }

        $customers = $query->latest()->paginate(30);


        $tvaGroups = TvaGroup::all();
        $discountGroups = DiscountGroup::all();
        $paymentModes = PaymentMode::where('type', 'encaissement')->get();

        $paymentTerms = PaymentTerm::all();

        // Villes uniques pour le filtre
        $cities = Customer::distinct()->pluck('city')->filter()->sort()->values();

        // Fetch TecDoc brands
        $response = Http::withHeaders([
            'X-Api-Key' => env('TECDOC_API_KEY', '2BeBXg6LDMZPdqWdaoq9CP19qGL6bTDHB9qBJEu6K264jC2Yv8wg')
        ])->post('https://webservice.tecalliance.services/pegasus-3-0/services/TecdocToCatDLB.jsonEndpoint', [
            "getLinkageTargets" => [
                "provider" => env('TECDOC_PROVIDER_ID', 23454),
                "linkageTargetCountry" => env('TECDOC_COUNTRY', 'TN'),
                "lang" => env('TECDOC_LANG', 'fr'),
                "linkageTargetType" => "P",
                "perPage" => 0,
                "page" => 1,
                "includeMfrFacets" => true
            ]
        ]);

        $brands = $response->successful() && isset($response->json()['mfrFacets']['counts'])
            ? $response->json()['mfrFacets']['counts']
            : [];

        return view('customernew', compact(
            'customers', 
            'tvaGroups', 
            'discountGroups', 
            'paymentModes', 
            'paymentTerms', 
            'brands',
            'cities'
        ));
    }










public function quickStoreVehicle(Request $request, $customer)
{
    
    $request->validate([
        'license_plate' => 'required|string|max:50',
        'brand_id' => 'required',
        'brand_name' => 'required|string',
        'model_id' => 'required',
        'model_name' => 'required|string',
        'engine_id' => 'required',
        'engine_description' => 'required|string',
        'linkage_target_id' => 'required',
    ]);

    $customer = Customer::findOrFail($customer);

    $vehicle = Vehicle::create([
        'customer_id' => $customer->id,
        'license_plate' => strtoupper($request->license_plate),
        'brand_id' => $request->brand_id,
        'brand_name' => $request->brand_name,
        'model_id' => $request->model_id,
        'model_name' => $request->model_name,
        'engine_id' => $request->engine_id,
        'engine_description' => $request->engine_description,
        'linkage_target_id' => $request->linkage_target_id,
    ]);

return response()->json([
    'success' => true,
    'vehicle' => [
        'id'   => $vehicle->id,
        'text' => $vehicle->license_plate . ' (' . $vehicle->brand_name . ' ' . $vehicle->model_name . ' - ' . ($vehicle->engine_description ?: 'Motorisation inconnue') . ')',
    ]
]);
}






// 1. Création ultra-rapide depuis une plaque (même si pas dans TecDoc)
public function storeFromPlate(Request $request, $customerId)
{
    $request->validate([
        'license_plate' => 'required|string|max:20',
        'brand_name'    => 'required|string|max:100',
        'model_name'    => 'nullable|string|max:100',
        'engine_description' => 'nullable|string|max:150',
    ]);

    $customer = Customer::findOrFail($customerId);

    $vehicle = Vehicle::updateOrCreate(
        [
            'customer_id'   => $customer->id,
            'license_plate' => strtoupper(str_replace([' ', '-'], '', $request->license_plate)),
        ],
        [
            'brand_id'           => -1, // marque inconnue TecDoc
            'brand_name'         => $request->brand_name,
            'model_id'           => -1,
            'model_name'         => $request->model_name ?? 'Modèle non répertorié',
            'engine_id'          => -1,
            'engine_description' => $request->engine_description ?? 'Motorisation inconnue',
            'linkage_target_id'  => -1,
        ]
    );

// Dans storeFromPlate()
return response()->json([
    'success' => true,
    'vehicle' => [
        'id'   => $vehicle->id,
        'text' => $vehicle->license_plate . ' (' . $vehicle->brand_name . ' ' . ($vehicle->model_name ?: 'Modèle inconnu') . ' - ' . ($vehicle->engine_description ?: 'Motorisation inconnue') . ')',
    ]
]);

}



}