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
        $request->validate([
            'id' => 'required|string|max:20|unique:item_categories,id',
            'name' => 'required|string|max:255|unique:item_categories,name',
            'description' => 'nullable|string',
        ]);

        ItemCategory::create([
            'id' => $request->id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Catégorie créée avec succès.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id' => 'required|string|max:20|unique:item_categories,id,' . $id,
            'name' => 'required|string|max:255|unique:item_categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $category = ItemCategory::findOrFail($id);
        $category->update([
            'id' => $request->id,
            'name' => $request->name,
            'description' => $request->description,
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
