<?php

namespace App\Http\Controllers;

use App\Exports\CustomersExport;
use App\Models\Customer;
use App\Models\DiscountGroup;
use App\Models\PaymentMode;
use App\Models\PaymentTerm;
use App\Models\Souche;
use App\Models\TvaGroup;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

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
        $paymentModes = PaymentMode::all();
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
    $query = Customer::with(['vehicles', 'tvaGroup', 'discountGroup', 'paymentMode', 'paymentTerm']);

    // Appliquer les mêmes filtres que dans index
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

    if ($request->filled('tva_group_id')) {
        $query->where('tva_group_id', $request->tva_group_id);
    }

    if ($request->filled('discount_group_id')) {
        $query->where('discount_group_id', $request->discount_group_id);
    }

    // Récupérer les customers avec leurs relations
    $customers = $query->get();
    
    // Si aucun customer trouvé, retourner une collection vide
    if ($customers->isEmpty()) {
        $customers = collect([]);
    }

    return Excel::download(new CustomersExport($customers), 'clients_' . date('Y-m-d_H-i-s') . '.xlsx');
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
        ]);

        $souche = Souche::where('type', 'client')->first();
        if (!$souche) {
            return back()->with('error', 'Souche client manquante');
        }

        $nextNumber = str_pad($souche->last_number + 1, $souche->number_length, '0', STR_PAD_LEFT);
        $code = ($souche->prefix ?? '') . ($souche->suffix ?? '') . $nextNumber;

        $customer = new Customer($request->except('code'));
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



}