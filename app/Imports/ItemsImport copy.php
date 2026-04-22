<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Brand;
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
    protected array $selectedColumns;
    protected string $updateMode;
    protected array $stats = ['created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => []];

    /**
     * @param array  $selectedColumns Colonnes à traiter. Vide = toutes.
     * @param string $updateMode      'update' ou 'skip'
     */
    public function __construct(array $selectedColumns = [], string $updateMode = 'update')
    {
        $this->selectedColumns = $selectedColumns;
        $this->updateMode      = $updateMode;
    }

    public function getStats(): array
    {
        return $this->stats;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // Ignorer lignes vides
            if ($row->filter()->isEmpty()) continue;

            $code = trim($row['code'] ?? '');
            if (empty($code)) continue;

            $item     = Item::where('code', $code)->first();
            $isUpdate = $item !== null;

            // Mode skip : on n'écrase pas les existants
            if ($isUpdate && $this->updateMode === 'skip') {
                $this->stats['skipped']++;
                continue;
            }

            // Nom obligatoire à la création
            if (!$isUpdate && empty(trim($row['name'] ?? ''))) {
                $this->stats['errors'][] = "Code {$code} : Nom manquant (création impossible)";
                continue;
            }

            try {
                $data = $this->buildData($row, $item, $isUpdate);
                Item::updateOrCreate(['code' => $code], $data);
                $isUpdate ? $this->stats['updated']++ : $this->stats['created']++;
            } catch (\Exception $e) {
                $this->stats['errors'][] = "Code {$code} : " . $e->getMessage();
            }
        }
    }

    protected function buildData($row, ?Item $item, bool $isUpdate): array
    {
        $data = [];
        $all  = empty($this->selectedColumns); // si aucune sélection → tout traiter

        // ────────────────────────────────────────────────────────
        // Helpers : résout une valeur ou garde la valeur existante
        // ────────────────────────────────────────────────────────
        $val = function (string $col, $default = null) use ($row, $item, $isUpdate) {
            $v = $row[$col] ?? null;
            return ($v !== null && trim((string)$v) !== '')
                ? $v
                : ($isUpdate ? ($item->{$col} ?? $default) : $default);
        };

        $col = fn(string $name) => $all || in_array($name, $this->selectedColumns);

        // ── name ──
        if ($col('name')) {
            $name = trim($row['name'] ?? '');
            $data['name'] = $name !== '' ? $name : ($isUpdate ? $item->name : '');
        }

        // ── description ──
        if ($col('description') && isset($row['description'])) {
            $data['description'] = $row['description'] !== '' ? $row['description'] : ($isUpdate ? $item->description : null);
        }

        // ── barcode ──
        if ($col('barcode') && isset($row['barcode'])) {
            $data['barcode'] = $row['barcode'] !== '' ? $row['barcode'] : ($isUpdate ? $item->barcode : null);
        }

        // ── category ──
        if ($col('category')) {
            $catName = trim($row['category'] ?? '');
            if ($catName !== '') {
                $cat = \App\Models\ItemCategory::firstOrCreate(['name' => $catName]);
                $data['category_id'] = $cat->id;
            } elseif ($isUpdate) {
                // ne pas écraser
            } else {
                $data['category_id'] = null;
            }
        }

        // ── brand (FIX : brand_id correctement résolu) ──
        if ($col('brand')) {
            $brandName = trim($row['brand'] ?? '');
            if ($brandName !== '') {
                $brand = Brand::firstOrCreate(['name' => $brandName]);
                $data['brand_id'] = $brand->id;
            } elseif (!$isUpdate) {
                $data['brand_id'] = null;
            }
            // si update + champ vide → on ne touche pas brand_id
        }

        // ── unit ──
        if ($col('unit')) {
            $unitLabel = trim($row['unit'] ?? '');
            if ($unitLabel !== '') {
                $unit = \App\Models\Unit::firstOrCreate(['label' => $unitLabel]);
                $data['unit_id'] = $unit->id;
            } elseif (!$isUpdate) {
                $data['unit_id'] = null;
            }
        }

        // ── store ──
        if ($col('store')) {
            $storeName = trim($row['store'] ?? '');
            if ($storeName !== '') {
                $store = \App\Models\Store::firstOrCreate(['name' => $storeName]);
                $data['store_id'] = $store->id;
            } elseif (!$isUpdate) {
                $data['store_id'] = null;
            }
        }

        // ── tva_group ──
        if ($col('tva_group')) {
            $tvaName = trim($row['tva_group'] ?? '');
            $tvaGroup = null;
            if ($tvaName !== '') {
                if (preg_match('/\(([\d\.]+)%\)/', $tvaName, $m)) {
                    $tvaGroup = \App\Models\TvaGroup::where('rate', floatval($m[1]))->first();
                } else {
                    $tvaGroup = \App\Models\TvaGroup::where('name', $tvaName)->first();
                }
            }
            if ($tvaGroup) {
                $data['tva_group_id'] = $tvaGroup->id;
            } elseif (!$isUpdate) {
                $fallback = \App\Models\TvaGroup::where('rate', 20)->first();
                $data['tva_group_id'] = $fallback ? $fallback->id : null;
            }
        }

        // ── supplier ──
        if ($col('supplier')) {
            $supplierName = trim($row['supplier'] ?? '');
            if ($supplierName !== '') {
                $supplier = \App\Models\Supplier::firstOrCreate(
                    ['name' => $supplierName],
                    ['code' => strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $supplierName), 0, 6)) ?: 'SUP000']
                );
                $data['codefournisseur'] = $supplier->code;
            } elseif (!$isUpdate) {
                $data['codefournisseur'] = null;
            }
        }

        // ── discount_group ──
        if ($col('discount_group')) {
            $dgName = trim($row['discount_group'] ?? '');
            if ($dgName !== '') {
                $dg = \App\Models\DiscountGroup::firstOrCreate(['name' => $dgName]);
                $data['discount_group_id'] = $dg->id;
            } elseif (!$isUpdate) {
                $data['discount_group_id'] = null;
            }
        }

        // ── Champs numériques / simples ──
        if ($col('cost_price') && isset($row['cost_price']) && trim((string)$row['cost_price']) !== '') {
            $data['cost_price'] = floatval($row['cost_price']);
        } elseif (!$isUpdate) {
            $data['cost_price'] = 0;
        }

        if ($col('sale_price') && isset($row['sale_price']) && trim((string)$row['sale_price']) !== '') {
            $data['sale_price'] = floatval($row['sale_price']);
        } elseif (!$isUpdate) {
            $data['sale_price'] = round(($data['cost_price'] ?? 0) * 1.3, 2);
        }

        if ($col('stock_min') && isset($row['stock_min']) && trim((string)$row['stock_min']) !== '') {
            $data['stock_min'] = intval($row['stock_min']);
        } elseif (!$isUpdate) {
            $data['stock_min'] = 0;
        }

        if ($col('stock_max') && isset($row['stock_max']) && trim((string)$row['stock_max']) !== '') {
            $data['stock_max'] = intval($row['stock_max']);
        } elseif (!$isUpdate) {
            $data['stock_max'] = 0;
        }

        if ($col('is_active') && isset($row['is_active']) && trim((string)$row['is_active']) !== '') {
            $data['is_active'] = (bool)$row['is_active'];
        } elseif (!$isUpdate) {
            $data['is_active'] = true;
        }

        // ── Champs texte simples ──
        foreach ([
            'location'    => 'location',
            'poids'       => 'Poids',
            'hauteur'     => 'Hauteur',
            'longueur'    => 'Longueur',
            'largeur'     => 'Largeur',
            'ref_tecdoc'  => 'Ref_TecDoc',
            'code_pays'   => 'Code_pays',
            'code_douane' => 'Code_douane',
        ] as $excelKey => $dbKey) {
            if ($col($excelKey) && isset($row[$excelKey]) && trim((string)$row[$excelKey]) !== '') {
                $data[$dbKey] = $row[$excelKey];
            } elseif (!$isUpdate) {
                $data[$dbKey] = null;
            }
        }

        return $data;
    }
}