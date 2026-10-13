<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use App\Models\ItemSubcategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
{
    $categories = ItemCategory::with('subcategories')->withCount(['items','subcategories'])->orderBy('id')->get();
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
    

    // ───────────── SOUS-FAMILLES ─────────────

    /** JSON : sous-familles d'une famille (selects en cascade) */
    public function subcategoriesByCategory($categoryId)
    {
        return response()->json(
            ItemSubcategory::where('category_id', $categoryId)
                ->orderBy('code')->get(['id', 'code', 'name'])
        );
    }

    public function storeSubcategory(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:item_categories,id',
            'code' => 'required|string|max:30|unique:item_subcategories,code',
            'name' => 'required|string|max:255',
            'default_sale_margin' => 'nullable|numeric|min:0|max:999.99',
        ]);

        ItemSubcategory::create([
            'category_id' => $request->category_id,
            'code' => trim($request->code),
            'name' => trim($request->name),
            'default_sale_margin' => $request->filled('default_sale_margin') ? $request->default_sale_margin : null,
        ]);

        return back()->with('success', 'Sous-famille créée.');
    }

    public function updateSubcategory(Request $request, $id)
    {
        $sub = ItemSubcategory::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:item_categories,id',
            'code' => 'required|string|max:30|unique:item_subcategories,code,' . $sub->id,
            'name' => 'required|string|max:255',
            'default_sale_margin' => 'nullable|numeric|min:0|max:999.99',
        ]);

        $sub->update([
            'category_id' => $request->category_id,
            'code' => trim($request->code),
            'name' => trim($request->name),
            'default_sale_margin' => $request->filled('default_sale_margin') ? $request->default_sale_margin : null,
        ]);

        // Les articles suivent leur sous-famille si elle change de famille
        \App\Models\Item::where('subcategory_id', $sub->id)->update(['category_id' => $sub->category_id]);

        return back()->with('success', 'Sous-famille mise à jour.');
    }

    public function destroySubcategory($id)
    {
        // items.subcategory_id passe à NULL (nullOnDelete)
        ItemSubcategory::findOrFail($id)->delete();

        return back()->with('success', 'Sous-famille supprimée.');
    }
}
