<?php

namespace App\Http\Controllers;

use App\Models\DiscountGroup;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
{
    $groupremises = DiscountGroup::orderBy('name')->get();
    return view('groupremise', compact('groupremises'));
}

public function store(Request $request)
{
    // dd($request);
    $request->validate([
        'name' => 'required|string|max:255|unique:discount_groups,name',
        'rate'    => 'required',
        'rate_jobber' => 'required|numeric|min:0|max:100',
            'rate_professionnel' => 'required|numeric|min:0|max:100',
        'type' => 'required|in:article,client',
        'price_tier' => 'nullable|in:general,jobber,professionnel',
        'price_mode' => 'nullable|in:profile,fixed',
    ]);

    DiscountGroup::create([ 
        'name' => $request->name,
        'type' => $request->type,
        'price_tier' => $request->type === 'client' && $request->filled('price_tier') ? $request->price_tier : null,
        'price_mode' => $request->type === 'client' && $request->price_mode === 'fixed' ? 'fixed' : 'profile',
'discount_rate'    => $request->rate,
'discount_rate_jobber'  => $request->rate_jobber,
            'discount_rate_professionnel' => $request->rate_professionnel,
]);

    return back()->with('success', 'Groupe Remise créée avec succès.');
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:discount_groups,name,' . $id,
        'rate' => 'required|numeric|min:0|max:100',
            'rate_jobber' => 'required|numeric|min:0|max:100',
            'rate_professionnel' => 'required|numeric|min:0|max:100',
        'type' => 'required|in:article,client',
        'price_tier' => 'nullable|in:general,jobber,professionnel',
        'price_mode' => 'nullable|in:profile,fixed',
    ]);

    $category = DiscountGroup::findOrFail($id);

    // Le type ne change plus dès que le groupe est utilisé (sinon articles/clients se retrouveraient avec un groupe du mauvais type)
    if ($request->type !== $category->type) {
        $nbItems = \Illuminate\Support\Facades\DB::table('items')->where('discount_group_id', $id)->count();
        $nbCust  = \Illuminate\Support\Facades\DB::table('customers')->where('discount_group_id', $id)->count();
        if ($nbItems + $nbCust > 0) {
            return back()->with('error', 'Le type de « ' . $category->name . ' » ne peut pas changer : ' . $nbItems . ' article(s) et ' . $nbCust . ' client(s) l\'utilisent.');
        }
    }

    $category->update([ 'name' => $request->name,
        'type' => $request->type,
        'price_tier' => $request->type === 'client' && $request->filled('price_tier') ? $request->price_tier : null,
        'price_mode' => $request->type === 'client' && $request->price_mode === 'fixed' ? 'fixed' : 'profile',
'discount_rate'    => $request->rate,
'discount_rate_jobber'  => $request->rate_jobber,
            'discount_rate_professionnel' => $request->rate_professionnel,
 ]);

    return back()->with('success', 'Groupe Remise mise à jour.');
}

public function destroy($id)
{
    $category = DiscountGroup::findOrFail($id);
    $category->delete();

    return back()->with('success', 'Groupe Remise supprimée.');
}
}
