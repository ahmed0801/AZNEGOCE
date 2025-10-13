<?php

namespace App\Http\Controllers;

use App\Models\TvaGroup;
use Illuminate\Http\Request;

class TvaController extends Controller
{
    public function index()
{
    $grouptvas = TvaGroup::orderBy('rate')->get();
    return view('grouptva', compact('grouptvas'));
}

public function store(Request $request)
{
    // dd($request);
    $request->validate([
        'name' => 'required|string|max:255|unique:item_categories,name',
        'rate'    => 'required',
        'code'    => 'required',
    ]);

    TvaGroup::create([ 
        'name' => $request->name,
'rate'    => $request->rate,
'code'    => $request->code,
]);

    return back()->with('success', 'Groupe TVA créée avec succès.');
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:item_categories,name,' . $id
    ]);

    $category = TvaGroup::findOrFail($id);
    $category->update([ 'name' => $request->name,
'rate'    => $request->rate,
'code'    => $request->code,
 ]);

    return back()->with('success', 'Catégorie mise à jour.');
}

public function destroy($id)
{
    $category = TvaGroup::findOrFail($id);
    $category->delete();

    return back()->with('success', 'Groupe TVA supprimée.');
}
}
