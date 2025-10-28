<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
{
    $categories = ItemCategory::orderBy('id')->get();
    return view('categories', compact('categories'));
}

public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|string|max:20|unique:item_categories,id',
            'name' => 'required|string|max:255|unique:item_categories,name',
            'description' => 'nullable|string',
            'default_sale_margin' => 'required|numeric|min:0|max:999.99',
        ]);

        ItemCategory::create([
            'id' => $request->id,
            'name' => $request->name,
            'description' => $request->description,
            'default_sale_margin' => $request->default_sale_margin,
        ]);

        return back()->with('success', 'Catégorie créée avec succès.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id' => 'required|string|max:20|unique:item_categories,id,' . $id,
            'name' => 'required|string|max:255|unique:item_categories,name,' . $id,
            'description' => 'nullable|string',
            'default_sale_margin' => 'required|numeric|min:0|max:999.99',
        ]);

        $category = ItemCategory::findOrFail($id);
        $category->update([
            'id' => $request->id,
            'name' => $request->name,
            'description' => $request->description,
             'default_sale_margin' => $request->default_sale_margin,
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
