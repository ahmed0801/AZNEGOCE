<?php

namespace App\Services;

use App\Models\ItemCategory;
use App\Models\ItemSubcategory;

/**
 * Nomenclature unique GOLDA (familles NUxx / sous-familles 0100…)
 * Source : database/data/golda_nomenclature.json
 */
class GoldaNomenclature
{
    private static $data = null;

    private static function data()
    {
        if (self::$data === null) {
            $path = database_path('data/golda_nomenclature.json');
            $json = is_file($path) ? json_decode(file_get_contents($path), true) : null;
            self::$data = is_array($json) ? $json : ['families' => [], 'subfamilies' => []];
        }
        return self::$data;
    }

    public static function familyName($code)
    {
        $d = self::data();
        return isset($d['families'][$code]) ? $d['families'][$code] : null;
    }

    public static function allSubfamilies()
    {
        $d = self::data();
        return $d['subfamilies'];
    }

    public static function subfamily($code)
    {
        $d = self::data();
        return isset($d['subfamilies'][$code]) ? $d['subfamilies'][$code] : null;
    }

    /** Famille existante, sinon créée depuis la nomenclature (null si inconnue). */
    public static function ensureCategory($code)
    {
        if ($code === '' || $code === null) return null;
        $cat = ItemCategory::find($code);
        if ($cat) return $cat;

        $name = self::familyName($code);
        if (!$name) return null;
        if (ItemCategory::where('name', $name)->exists()) {
            $name = $name . ' (' . $code . ')'; // name est unique
        }
        return ItemCategory::create([
            'id' => $code, 'name' => $name, 'default_sale_margin' => 30.00,
        ]);
    }

    /**
     * Sous-famille par code GOLDA. Créée si absente (nom issu de la nomenclature,
     * à défaut le code). La famille de rattachement est celle de la nomenclature
     * si connue, sinon celle fournie.
     */
    public static function ensureSubcategory($code, $category)
    {
        if ($code === '' || $code === null) return null;

        $ref = self::subfamily($code);

        $sub = ItemSubcategory::where('code', $code)->first();
        if ($sub) {
            // Auto-réparation : nom vide ou égal au code -> libellé officiel GOLDA
            if ($ref && ($sub->name === '' || $sub->name === $sub->code)) {
                $sub->update(['name' => $ref['name']]);
            }
            return $sub;
        }

        $cat = $category;
        if ($ref) {
            $fromRef = self::ensureCategory($ref['family']);
            if ($fromRef) $cat = $fromRef;
        }
        if (!$cat) return null;

        return ItemSubcategory::create([
            'category_id' => $cat->id,
            'code'        => $code,
            'name'        => $ref ? $ref['name'] : $code,
        ]);
    }

    /**
     * Charge toute la nomenclature (idempotent). Ne renomme jamais ce que
     * l'utilisateur a personnalisé (seuls les noms vides ou égaux au code sont remplacés).
     * @return array [familles créées, sous-familles créées, sous-familles renommées]
     */
    public static function syncAll()
    {
        $d = self::data();
        $fc = $sc = $sr = 0;

        foreach ($d['families'] as $code => $name) {
            if (!ItemCategory::find($code)) { if (self::ensureCategory($code)) $fc++; }
        }
        foreach ($d['subfamilies'] as $code => $ref) {
            $sub = ItemSubcategory::where('code', $code)->first();
            if (!$sub) {
                if (self::ensureSubcategory($code, null)) $sc++;
            } elseif ($sub->name === '' || $sub->name === $sub->code) {
                $sub->update(['name' => $ref['name']]);
                $sr++;
            }
        }
        return [$fc, $sc, $sr];
    }
}
