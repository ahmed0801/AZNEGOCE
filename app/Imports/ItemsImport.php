<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Store;
use App\Models\TvaGroup;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class ItemsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // --- Gestion des clés étrangères par nom réel ---
            $category = !empty($row['category']) ? ItemCategory::firstOrCreate(['name' => $row['category']]) : null;
            $brand    = !empty($row['brand']) ? Brand::firstOrCreate(['name' => $row['brand']]) : null;
            $unit     = !empty($row['unit']) ? Unit::firstOrCreate(['label' => $row['unit']]) : null;
            $store    = !empty($row['store']) ? Store::firstOrCreate(['name' => $row['store']]) : null;

            // TVA par défaut = 20%
$tvaGroup = null;
if (!empty($row['tva_group'])) {
    // On cherche l'enregistrement existant dans la table tva_groups
    // ici on suppose que ton fichier Excel a "20" ou "ASSUJ (20.00%)"
    $tvaGroupName = $row['tva_group'];

    // Normaliser si nécessaire
    if (strpos($tvaGroupName, '(') !== false) {
        preg_match('/\(([\d\.]+)%\)/', $tvaGroupName, $matches);
        $tvaValue = isset($matches[1]) ? floatval($matches[1]) : 20;
        $tvaGroup = TvaGroup::where('rate', $tvaValue)->first();
    } else {
        // Si juste "20"
        $tvaGroup = TvaGroup::where('name', $tvaGroupName)->first();
    }

    // Si non trouvé, utiliser le tva par défaut = 20%
    if (!$tvaGroup) {
        $tvaGroup = TvaGroup::where('rate', 20)->first();
    }
}




            $supplier = !empty($row['supplier']) ? Supplier::firstOrCreate(
                ['name' => $row['supplier']],
                ['code' => substr($row['supplier'], 0, 3)]
            ) : null;

            $costPrice = isset($row['cost_price']) ? floatval($row['cost_price']) : 0;
            $salePrice = isset($row['sale_price']) ? floatval($row['sale_price']) : round($costPrice * 1.3, 2);

            // --- Création ou mise à jour ---
           Item::updateOrCreate(
    ['code' => $row['code']],
    [
        'name' => $row['name'] ?? null,
        'description' => $row['description'] ?? null,
        'category_id' => $category ? $category->id : null,
        'brand_id' => $brand ? $brand->id : null,
        'unit_id' => $unit ? $unit->id : null,
        'barcode' => $row['barcode'] ?? null,
        'cost_price' => $costPrice,
        'sale_price' => isset($row['sale_price']) ? floatval($row['sale_price']) : $salePrice,
        'tva_group_id' => $tvaGroup ? $tvaGroup->id : null,
        'stock_min' => $row['stock_min'] ?? 0,
        'stock_max' => $row['stock_max'] ?? 0,
        'store_id' => $store ? $store->id : null,
        'location' => $row['location'] ?? null,
        'is_active' => isset($row['is_active']) ? (bool)$row['is_active'] : true,
        'codefournisseur' => $supplier ? $supplier->code : null,
        'Poids' => $row['poids'] ?? null,
        'Hauteur' => $row['hauteur'] ?? null,
        'Longueur' => $row['longueur'] ?? null,
        'Largeur' => $row['largeur'] ?? null,
        'Ref_TecDoc' => $row['ref_tecdoc'] ?? null,
        'Code_pays' => $row['code_pays'] ?? null,
        'Code_douane' => $row['code_douane'] ?? null,
    ]
);

        }
    }
}
