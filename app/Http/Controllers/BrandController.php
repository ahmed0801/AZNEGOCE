<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
{
    $brands = Brand::orderBy('name')->get();
    return view('brands', compact('brands'));
}

public function store(Request $request)
{
    // dd($request);
    $request->validate([
        'name' => 'required|string|max:255|unique:brands,name',
    ]);

    Brand::create([ 
'name'    => $request->name,
]);

    return back()->with('success', 'Unité créée avec succès.');
}

public function update(Request $request, $id)
{
    // $request->validate([
    //     'code' => 'required|string|max:255|unique:units,code',
    //     'label' => 'required',
    // ]);

    $category = Brand::findOrFail($id);
    $category->update([ 'name' => $request->name,
 ]);

    return back()->with('success', 'Unité mise à jour.');
}

public function destroy($id)
{
    $category = Brand::findOrFail($id);
    $category->delete();

    return back()->with('success', 'Marque supprimée.');
}
}
