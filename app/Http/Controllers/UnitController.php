<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
{
    $units = Unit::orderBy('code')->get();
    return view('units', compact('units'));
}

public function store(Request $request)
{
    // dd($request);
    $request->validate([
        'label' => 'required|string|max:255|unique:item_categories,name',
        'code'    => 'required',
    ]);

    Unit::create([ 
'code'    => $request->code,
 'label' => $request->label,
]);

    return back()->with('success', 'Unité créée avec succès.');
}

public function update(Request $request, $id)
{
    // $request->validate([
    //     'code' => 'required|string|max:255|unique:units,code',
    //     'label' => 'required',
    // ]);

    $category = Unit::findOrFail($id);
    $category->update([ 'label' => $request->label,
'code'    => $request->code,
 ]);

    return back()->with('success', 'Unité mise à jour.');
}

public function destroy($id)
{
    $category = Unit::findOrFail($id);
    $category->delete();

    return back()->with('success', 'Unité supprimée.');
}
}
