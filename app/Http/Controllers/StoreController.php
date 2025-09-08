<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
     public function index()
{
    $magasins = Store::orderBy('name')->get();
    return view('magasins', compact('magasins'));
}

public function store(Request $request)
{
    // dd($request);
    $request->validate([
        'name' => 'required|string|max:255|unique:stores,name',
        'description'    => 'required',
    ]);

    Store::create([ 
'name'    => $request->name,
 'description' => $request->description,
]);

    return back()->with('success', 'Unité créée avec succès.');
}

public function update(Request $request, $id)
{
    // $request->validate([
    //     'code' => 'required|string|max:255|unique:units,code',
    //     'label' => 'required',
    // ]);

    $category = Store::findOrFail($id);
    $category->update([ 'name' => $request->name,
'description'    => $request->description,
 ]);

    return back()->with('success', 'Magasin mise à jour.');
}

public function destroy($id)
{
    $category = Store::findOrFail($id);
    $category->delete();

    return back()->with('success', 'Magasin supprimée.');
}






public function generateLocations(Request $request)
{
    $validated = $request->validate([
        'store_id' => 'required|exists:stores,id',
        'floors' => 'required|integer|min:1',
        'rows' => 'required|integer|min:1',
        'columns' => 'required|integer|min:1',
    ]);

    $store = Store::findOrFail($validated['store_id']);

    for ($floor = 1; $floor <= $validated['floors']; $floor++) {
        for ($row = 1; $row <= $validated['rows']; $row++) {
            for ($col = 1; $col <= $validated['columns']; $col++) {
                \App\Models\Location::firstOrCreate([
                    'store_id' => $store->id,
                    'floor' => $floor,
                    'row' => $row,
                    'column' => $col,
                ], [
                    'label' => "E{$floor}-R{$row}-A{$col}",
                ]);
            }
        }
    }

    return back()->with('success', 'Emplacements générés avec succès pour ' . $store->name);
}




}
