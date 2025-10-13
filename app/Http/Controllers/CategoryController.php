<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
{
    $categories = ItemCategory::orderBy('name')->get();
    return view('categories', compact('categories'));
}

public function store(Request $request)
{
    // dd($request);
    $request->validate([
        'name' => 'required|string|max:255|unique:item_categories,name',
        'description'    => 'nullable|string',
    ]);

    ItemCategory::create([ 
        'name' => $request->name,
'description'    => $request->description,
]);

    return back()->with('success', 'Catégorie créée avec succès.');
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:item_categories,name,' . $id
    ]);

    $category = ItemCategory::findOrFail($id);
    $category->update([ 'name' => $request->name,
'description'    => $request->description,
 ]);

    return back()->with('success', 'Catégorie mise à jour.');
}

public function destroy($id)
{
    $category = ItemCategory::findOrFail($id);
    $category->delete();

    return back()->with('success', 'Catégorie supprimée.');
}
}
