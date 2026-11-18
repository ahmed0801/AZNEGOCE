<?php

namespace App\Services;

/** Fonctions d'affichage partagées par la page statistiques et ses fragments AJAX */
class StatsFormat
{
    public static function closures()
    {
        $fm  = function ($v, $d = 2) { return number_format((float) $v, $d, ',', ' '); };
        $eur = function ($v, $d = 0) { return number_format((float) $v, $d, ',', ' ') . ' €'; };
        $fr  = function ($s) { return $s ? date('d/m/Y', strtotime($s)) : '—'; };
        $num = function ($v, $dec = 2, $extra = '') use ($fm) {
            if ($v === null || $v === '') return '<td class="num text-muted" data-v="-1">—</td>';
            return '<td class="num ' . $extra . '" data-v="' . $v . '">' . $fm($v, $dec) . '</td>';
        };
        return ['fm' => $fm, 'eur' => $eur, 'fr' => $fr, 'num' => $num];
    }
}
