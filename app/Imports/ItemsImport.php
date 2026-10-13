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
use App\Models\ItemSubcategory;
use App\Services\GoldaNomenclature;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class ItemsImport implements ToCollection, WithHeadingRow
{
    protected array $selectedColumns;
    protected string $updateMode;
    protected int $currentLine = 0;
    protected array $stats = ['created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => [], 'warnings' => []];

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
                $this->currentLine = $index + 2;
                $data = $this->buildData($row, $item, $isUpdate);
                Item::updateOrCreate(['code' => $code], $data);
                $isUpdate ? $this->stats['updated']++ : $this->stats['created']++;
            } catch (\Exception $e) {
                $this->stats['errors'][] = "Ligne " . ($index + 2) . " (code {$code}) : " . $this->shortError($e);
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

        $col = function (string $name) use ($all) {
            return $all || in_array($name, $this->selectedColumns);
        };

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

        // ── category (code famille ou nom ; ne crée plus de famille sans code) ──
        if ($col('category')) {
            $catName = trim($row['category'] ?? '');
            if ($catName !== '') {
                $cat = \App\Models\ItemCategory::where('id', $catName)->orWhere('name', $catName)->first();
                if ($cat) {
                    $data['category_id'] = $cat->id;
                } else {
                    $this->stats['warnings'][] = "Ligne {$this->currentLine} : famille « {$catName} » introuvable (code ou nom exact attendu).";
                    if (!$isUpdate) $data['category_id'] = null;
                }
            } elseif (!$isUpdate) {
                $data['category_id'] = null;
            }
        }

        // ── subcategory (code GOLDA ex: 0220 / 213, ou libellé ex: Garnitures) ──
        if ($col('subcategory')) {
            $subName = trim((string)($row['subcategory'] ?? ''));
            if ($subName !== '') {
                $catId = !empty($data['category_id']) ? $data['category_id'] : ($isUpdate ? $item->category_id : null);
                $sub   = $this->resolveSubcategory($subName, $catId);
                if ($sub) {
                    $data['subcategory_id'] = $sub->id;
                    $data['category_id']    = $sub->category_id;
                } else {
                    $this->stats['warnings'][] = "Ligne {$this->currentLine} : sous-famille « {$subName} » introuvable"
                        . ($catId ? '' : ' (renseignez aussi la famille pour la créer)') . ' — ignorée.';
                }
            } elseif (!$isUpdate) {
                $data['subcategory_id'] = null;
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

        // ── unit (libellé ou code ; crée l'unité si absente) ──
        if ($col('unit')) {
            $unitLabel = trim((string)($row['unit'] ?? ''));
            if ($unitLabel !== '') {
                $data['unit_id'] = $this->resolveUnit($unitLabel)->id;
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
                $data['tva_group_id'] = $fallback ? $fallback->id : null;  // PHP 7.4 compatible
            }
        }

        // ── supplier (fournisseur principal) ──
        if ($col('supplier')) {
            $supplierName = trim($row['supplier'] ?? '');
            if ($supplierName !== '') {
                $supplier = $this->resolveSupplier($supplierName);
                $data['codefournisseur'] = $supplier->code;
            } elseif (!$isUpdate) {
                $data['codefournisseur'] = null;
            }
        }

        // ── remise_achat (fournisseur principal) ──
        if ($col('remise_achat') && isset($row['remise_achat']) && trim((string)$row['remise_achat']) !== '') {
            $data['remise_achat'] = floatval($row['remise_achat']);
        } elseif (!$isUpdate) {
            $data['remise_achat'] = 0.00;
        }

        // ── Fournisseur 2 ──
        if ($col('supplier_2')) {
            $sup2Name = trim($row['supplier_2'] ?? '');
            if ($sup2Name !== '') {
                $sup2 = $this->resolveSupplier($sup2Name);
                $data['codefournisseur_2'] = $sup2->code;
            } elseif (!$isUpdate) {
                $data['codefournisseur_2'] = null;
            }
        }

        if ($col('cost_price_2') && isset($row['cost_price_2']) && trim((string)$row['cost_price_2']) !== '') {
            $data['cost_price_2'] = floatval($row['cost_price_2']);
        } elseif (!$isUpdate) {
            $data['cost_price_2'] = 0.00;
        }

        if ($col('remise_achat_2') && isset($row['remise_achat_2']) && trim((string)$row['remise_achat_2']) !== '') {
            $data['remise_achat_2'] = floatval($row['remise_achat_2']);
        } elseif (!$isUpdate) {
            $data['remise_achat_2'] = 0.00;
        }

        // ── Fournisseur 3 ──
        if ($col('supplier_3')) {
            $sup3Name = trim($row['supplier_3'] ?? '');
            if ($sup3Name !== '') {
                $sup3 = $this->resolveSupplier($sup3Name);
                $data['codefournisseur_3'] = $sup3->code;
            } elseif (!$isUpdate) {
                $data['codefournisseur_3'] = null;
            }
        }

        if ($col('cost_price_3') && isset($row['cost_price_3']) && trim((string)$row['cost_price_3']) !== '') {
            $data['cost_price_3'] = floatval($row['cost_price_3']);
        } elseif (!$isUpdate) {
            $data['cost_price_3'] = 0.00;
        }

        if ($col('remise_achat_3') && isset($row['remise_achat_3']) && trim((string)$row['remise_achat_3']) !== '') {
            $data['remise_achat_3'] = floatval($row['remise_achat_3']);
        } elseif (!$isUpdate) {
            $data['remise_achat_3'] = 0.00;
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

    // ════════════════ Helpers ════════════════

    protected function shortError(\Exception $e): string
    {
        $m = $e->getMessage();
        if (stripos($m, 'SQLSTATE') !== false) {
            // garde la partie lisible du message SQL
            if (preg_match("/(Field '[^']+' doesn't have a default value|Duplicate entry '[^']*' for key '[^']*'|Data too long for column '[^']*'|Incorrect [a-z]+ value: '[^']*' for column '[^']*')/i", $m, $x)) {
                return $x[1];
            }
            return substr($m, 0, 160);
        }
        return $m;
    }

    protected function resolveUnit(string $label)
    {
        $unit = Unit::where('label', $label)->orWhere('code', $label)->first();
        if ($unit) return $unit;

        $base = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $label), 0, 10)) ?: 'UN';
        $code = $base;
        $i = 1;
        while (Unit::where('code', $code)->exists()) {
            $code = substr($base, 0, 8) . $i++;
        }
        return Unit::create(['code' => $code, 'label' => $label]);
    }

    protected function resolveSupplier(string $name)
    {
        // "Exa - EXADIS" -> essaie le nom complet, puis le code avant " - "
        $supplier = Supplier::where('name', $name)->first();
        if (!$supplier && strpos($name, ' - ') !== false) {
            $parts = explode(' - ', $name, 2);
            $supplier = Supplier::where('code', trim($parts[0]))->first()
                     ?: Supplier::where('name', trim($parts[1]))->first();
        }
        if (!$supplier) $supplier = Supplier::where('code', $name)->first();
        if ($supplier) return $supplier;

        $base = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $name), 0, 6)) ?: 'SUP000';
        $code = $base;
        $i = 1;
        while (Supplier::where('code', $code)->exists()) {
            $code = substr($base, 0, 5) . $i++;
        }
        return Supplier::create(['code' => $code, 'name' => $name]);
    }

    /**
     * Sous-famille par code GOLDA (213 -> 0213 : Excel supprime les zéros), sinon par libellé.
     * Si elle n'existe pas : créée depuis la nomenclature GOLDA, ou sous la famille donnée.
     */
    protected function resolveSubcategory(string $value, $categoryId)
    {
        // Excel renvoie parfois 1702.0 (nombre) et supprime les zéros de tête (213 au lieu de 0213)
        if (preg_match('/^\d+\.0+$/', $value)) $value = substr($value, 0, strpos($value, '.'));
        $code = $value;
        if (ctype_digit($value) && strlen($value) < 4) $code = str_pad($value, 4, '0', STR_PAD_LEFT);

        $q = ItemSubcategory::query();
        $sub = (clone $q)->where('code', $code)->first();

        if (!$sub) {
            $byName = (clone $q)->whereRaw('LOWER(name) = ?', [mb_strtolower($value)]);
            if ($categoryId) $byName->where('category_id', $categoryId);
            $sub = $byName->first();
        }
        if ($sub) return $sub;

        // libellé présent dans la nomenclature GOLDA (même famille) -> on crée avec le bon code
        foreach (GoldaNomenclature::allSubfamilies() as $ncode => $ref) {
            if (mb_strtolower($ref['name']) === mb_strtolower($value) && (!$categoryId || $ref['family'] === $categoryId)) {
                return GoldaNomenclature::ensureSubcategory($ncode, $categoryId ? ItemCategory::find($categoryId) : null);
            }
        }

        // code GOLDA connu de la nomenclature
        if (GoldaNomenclature::subfamily($code)) {
            return GoldaNomenclature::ensureSubcategory($code, $categoryId ? ItemCategory::find($categoryId) : null);
        }

        // nouveau libellé libre : on le crée sous la famille indiquée
        if ($categoryId) {
            $base = strtoupper(substr(preg_replace('/[^A-Za-z0-9]+/', '_', $this->stripAccents($value)), 0, 24)) ?: 'SF';
            $newCode = $base;
            $i = 1;
            while (ItemSubcategory::where('code', $newCode)->exists()) {
                $newCode = substr($base, 0, 20) . '_' . $i++;
            }
            return ItemSubcategory::create(['category_id' => $categoryId, 'code' => $newCode, 'name' => $value]);
        }
        return null;
    }

    protected function stripAccents(string $t): string
    {
        $r = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $t);
        return $r !== false ? $r : $t;
    }
}
