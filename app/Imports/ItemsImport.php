<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Unit;
use App\Models\Store;
use App\Models\TvaGroup;
use App\Models\DiscountGroup;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class ItemsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Ignorer les lignes complètement vides
            if ($row->filter()->isEmpty()) {
                continue;
            }

            $code = trim($row['code'] ?? '');
            if (empty($code)) {
                continue;
            }

            // Recherche de l'article existant
            $item = Item::where('code', $code)->first();
            $isUpdate = $item !== null;

            // Nom obligatoire à la création
            if (!$isUpdate && empty(trim($row['name'] ?? ''))) {
                continue;
            }

            // === Catégorie ===
            $category = null;
            if (!empty(trim($row['category'] ?? ''))) {
                $category = ItemCategory::firstOrCreate(['name' => trim($row['category'])]);
            } elseif ($isUpdate && $item->category) {
                $category = $item->category;
            }
            // Sécurisation : si toujours null (création + colonne vide) → on garde null mais on passe par l'ID direct
            $categoryId = $category ? $category->id : null;

            // === Unité ===
            $unit = null;
            if (!empty(trim($row['unit'] ?? ''))) {
                $unit = Unit::firstOrCreate(['label' => trim($row['unit'])]);
            } elseif ($isUpdate && $item->unit) {
                $unit = $item->unit;
            }
            $unitId = $unit ? $unit->id : null;

            // === Magasin ===
            $store = null;
            if (!empty(trim($row['store'] ?? ''))) {
                $store = Store::firstOrCreate(['name' => trim($row['store'])]);
            } elseif ($isUpdate && $item->store) {
                $store = $item->store;
            }
            $storeId = $store ? $store->id : null;

            // === Groupe TVA ===
            $tvaGroup = null;
            if (!empty(trim($row['tva_group'] ?? ''))) {
                $tvaName = trim($row['tva_group']);
                if (preg_match('/\(([\d\.]+)%\)/', $tvaName, $matches)) {
                    $rate = floatval($matches[1]);
                    $tvaGroup = TvaGroup::where('rate', $rate)->first();
                } else {
                    $tvaGroup = TvaGroup::where('name', $tvaName)->first();
                }
            }
            if (!$tvaGroup) {
                $tvaGroup = $isUpdate && $item->tvaGroup ? $item->tvaGroup : TvaGroup::where('rate', 20)->first();
            }
            // Sécurisation : si le taux 20% n'existe pas → null
            $tvaGroupId = $tvaGroup ? $tvaGroup->id : null;

            // === Fournisseur ===
            $supplier = null;
            if (!empty(trim($row['supplier'] ?? ''))) {
                $supplier = Supplier::firstOrCreate(
                    ['name' => trim($row['supplier'])],
                    ['code' => substr(trim($row['supplier']), 0, 6) ?: 'SUP000']
                );
            } elseif ($isUpdate && $item->supplier) {
                $supplier = $item->supplier;
            }
            $supplierCode = $supplier ? $supplier->code : null;

            // === Groupe de remise ===
            $discountGroup = null;
            if (!empty(trim($row['discount_group'] ?? ''))) {
                $discountGroup = DiscountGroup::firstOrCreate(['name' => trim($row['discount_group'])]);
            } elseif ($isUpdate && $item->discountGroup) {
                $discountGroup = $item->discountGroup;
            }
            $discountGroupId = $discountGroup ? $discountGroup->id : null;

            // === Prix ===
            $costPrice = !empty($row['cost_price']) ? floatval($row['cost_price']) : ($isUpdate ? $item->cost_price : 0);
            $salePrice = !empty($row['sale_price'])
                ? floatval($row['sale_price'])
                : ($isUpdate ? $item->sale_price : round($costPrice * 1.3, 2));

            // === updateOrCreate avec IDs sécurisés ===
            Item::updateOrCreate(
                ['code' => $code],
                [
                    'name'              => $isUpdate
                        ? (trim($row['name'] ?? '') !== '' ? trim($row['name']) : $item->name)
                        : trim($row['name']),

                    'description'       => $row['description'] ?? ($isUpdate ? $item->description : null),
                    'category_id'       => $categoryId,
                    'unit_id'           => $unitId,
                    'barcode'           => $row['barcode'] ?? ($isUpdate ? $item->barcode : null),
                    'cost_price'        => $costPrice,
                    'sale_price'        => $salePrice,
                    'tva_group_id'      => $tvaGroupId,
                    'stock_min'         => $row['stock_min'] ?? ($isUpdate ? $item->stock_min : 0),
                    'stock_max'         => $row['stock_max'] ?? ($isUpdate ? $item->stock_max : 0),
                    'store_id'          => $storeId,
                    'location'          => $row['location'] ?? ($isUpdate ? $item->location : null),
                    'is_active'         => isset($row['is_active'])
                        ? (bool)$row['is_active']
                        : ($isUpdate ? $item->is_active : true),
                    'codefournisseur'   => $supplierCode,
                    'Poids'             => $row['poids'] ?? ($isUpdate ? $item->Poids : null),
                    'Hauteur'           => $row['hauteur'] ?? ($isUpdate ? $item->Hauteur : null),
                    'Longueur'          => $row['longueur'] ?? ($isUpdate ? $item->Longueur : null),
                    'Largeur'           => $row['largeur'] ?? ($isUpdate ? $item->Largeur : null),
                    'Ref_TecDoc'        => $row['ref_tecdoc'] ?? ($isUpdate ? $item->Ref_TecDoc : null),
                    'Code_pays'         => $row['code_pays'] ?? ($isUpdate ? $item->Code_pays : null),
                    'Code_douane'       => $row['code_douane'] ?? ($isUpdate ? $item->Code_douane : null),
                    'discount_group_id' => $discountGroupId,
                ]
            );
        }
    }
}