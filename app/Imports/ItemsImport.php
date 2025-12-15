<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Store;
use App\Models\TvaGroup;
use App\Models\DiscountGroup; // ← Ajout
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
                continue; // Code toujours obligatoire
            }

            // Recherche de l'article existant
            $item = Item::where('code', $code)->first();
            $isUpdate = $item !== null; // true = mise à jour, false = création

            // === Blocage uniquement à la création ===
            if (!$isUpdate) {
                // Pour les nouveaux articles : name obligatoire
                $name = trim($row['name'] ?? '');
                if (empty($name)) {
                    continue; // On ignore silencieusement la ligne (ou tu peux logger une erreur)
                }
            }

            // === Gestion des relations (conservation si vide) ===
            $category = null;
            if (!empty(trim($row['category'] ?? ''))) {
                $category = ItemCategory::firstOrCreate(['name' => trim($row['category'])]);
            } elseif ($isUpdate && $item->category) {
                $category = $item->category;
            }

            $unit = null;
            if (!empty(trim($row['unit'] ?? ''))) {
                $unit = Unit::firstOrCreate(['label' => trim($row['unit'])]);
            } elseif ($isUpdate && $item->unit) {
                $unit = $item->unit;
            }

            $store = null;
            if (!empty(trim($row['store'] ?? ''))) {
                $store = Store::firstOrCreate(['name' => trim($row['store'])]);
            } elseif ($isUpdate && $item->store) {
                $store = $item->store;
            }

            // TVA Group
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

            // Fournisseur
            $supplier = null;
            if (!empty(trim($row['supplier'] ?? ''))) {
                $supplier = Supplier::firstOrCreate(
                    ['name' => trim($row['supplier'])],
                    ['code' => substr(trim($row['supplier']), 0, 6)]
                );
            } elseif ($isUpdate && $item->supplier) {
                $supplier = $item->supplier;
            }

            // Groupe de remise
            $discountGroup = null;
            if (!empty(trim($row['discount_group'] ?? ''))) {
                $groupName = trim($row['discount_group']);
                $discountGroup = DiscountGroup::firstOrCreate(['name' => $groupName]);
            } elseif ($isUpdate && $item->discountGroup) {
                $discountGroup = $item->discountGroup;
            }

            // Prix
            $costPrice = !empty($row['cost_price']) ? floatval($row['cost_price']) : ($isUpdate ? $item->cost_price : 0);
            $salePrice = !empty($row['sale_price'])
                ? floatval($row['sale_price'])
                : ($isUpdate ? $item->sale_price : round($costPrice * 1.3, 2));

            // === updateOrCreate ===
            Item::updateOrCreate(
                ['code' => $code],
                [
                    // Name : obligatoire seulement à la création → sinon garde l'ancien
                    'name'              => $isUpdate
                        ? (trim($row['name'] ?? '') !== '' ? trim($row['name']) : $item->name)
                        : trim($row['name']),

                    'description'       => $row['description'] ?? ($isUpdate ? $item->description : null),
                    'category_id'       => $category->id,
                    'unit_id'           => $unit->id,
                    'barcode'           => $row['barcode'] ?? ($isUpdate ? $item->barcode : null),
                    'cost_price'        => $costPrice,
                    'sale_price'        => $salePrice,
                    'tva_group_id'      => $tvaGroup->id,
                    'stock_min'         => $row['stock_min'] ?? ($isUpdate ? $item->stock_min : 0),
                    'stock_max'         => $row['stock_max'] ?? ($isUpdate ? $item->stock_max : 0),
                    'store_id'          => $store->id,
                    'location'          => $row['location'] ?? ($isUpdate ? $item->location : null),
                    'is_active'         => isset($row['is_active'])
                        ? (bool)$row['is_active']
                        : ($isUpdate ? $item->is_active : true),
                    'codefournisseur'   => $supplier->code,
                    'Poids'             => $row['poids'] ?? ($isUpdate ? $item->Poids : null),
                    'Hauteur'           => $row['hauteur'] ?? ($isUpdate ? $item->Hauteur : null),
                    'Longueur'          => $row['longueur'] ?? ($isUpdate ? $item->Longueur : null),
                    'Largeur'           => $row['largeur'] ?? ($isUpdate ? $item->Largeur : null),
                    'Ref_TecDoc'        => $row['ref_tecdoc'] ?? ($isUpdate ? $item->Ref_TecDoc : null),
                    'Code_pays'         => $row['code_pays'] ?? ($isUpdate ? $item->Code_pays : null),
                    'Code_douane'       => $row['code_douane'] ?? ($isUpdate ? $item->Code_douane : null),
                    'discount_group_id' => $discountGroup->id,
                ]
            );
        }
    }
}