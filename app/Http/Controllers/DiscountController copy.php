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
    ]);

    DiscountGroup::create([ 
        'name' => $request->name,
'discount_rate'    => $request->rate,
]);

    return back()->with('success', 'Groupe Remise créée avec succès.');
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:discount_groups,name,' . $id
    ]);

    $category = DiscountGroup::findOrFail($id);
    $category->update([ 'name' => $request->name,
'discount_rate'    => $request->rate,
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
