<?php

namespace App\Exports;

use App\Models\Item;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ArticleExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection(): Collection
    {
        $query = Item::with(['category', 'subcategory', 'brand', 'store', 'supplier', 'stocks', 'discountGroup']);

        // Filtre de recherche
        if ($this->request->filled('search')) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->request->search}%")
                  ->orWhere('code', 'like', "%{$this->request->search}%");
            });
        }

        // Filtres spécifiques (mêmes filtres que la page Articles)
        if ($this->request->filled('brand_id')) {
            $query->where('brand_id', $this->request->brand_id);
        }

        if ($this->request->filled('category_id')) {
            $query->where('category_id', $this->request->category_id);
        }

        if ($this->request->filled('subcategory_id')) {
            $query->where('subcategory_id', $this->request->subcategory_id);
        }

        if ($this->request->filled('store_id')) {
            $query->where('store_id', $this->request->store_id);
        }

        if ($this->request->filled('discount_group_id')) {
            $query->where('discount_group_id', $this->request->discount_group_id);
        }

        if ($this->request->filled('codefournisseur')) {
            $query->where('codefournisseur', $this->request->codefournisseur);
        }

        if ($this->request->has('is_active') && $this->request->is_active !== null && $this->request->is_active !== '') {
            $query->where('is_active', $this->request->is_active);
        }

        // Récupérer les résultats
        $items = $query->get();

        // Mapper les données pour l'export
        return $items->map(function ($item) {
            return (object) [
                'Code' => $item->code,
                'Nom' => $item->name,
                'Marque' => $item->brand ? $item->brand->name : '',
                'Famille' => $item->category ? $item->category->name : '',
                'Sous-famille' => $item->subcategory ? $item->subcategory->code . ' — ' . $item->subcategory->name : '',
                'Prix Achat HT' => $item->cost_price,
                'Remise Achat (%)' => $item->remise_achat,
                'Prix Vente HT' => $item->sale_price,
                'Groupe Remise' => $item->discountGroup ? $item->discountGroup->name : '',
                'Fournisseur' => $item->supplier ? $item->supplier->name : '',
                'Code Fournisseur' => $item->codefournisseur,
                'Stock' => $item->stocks->sum('quantity'),
                'Magasin' => $item->store ? $item->store->name : '',
                'Emplacement' => $item->location ?? '',
                'Statut' => $item->is_active ? 'Actif' : 'Bloqué',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Code', 'Nom', 'Marque', 'Famille', 'Sous-famille', 'Prix Achat HT', 'Remise Achat (%)', 'Prix Vente HT', 'Groupe Remise',
            'Fournisseur', 'Code Fournisseur', 'Stock', 'Magasin', 'Emplacement', 'Statut',
        ];
    }
}
