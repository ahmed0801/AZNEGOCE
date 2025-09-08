<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Session;



class PurchaseProjectImport implements ToArray, WithHeadingRow
{
    protected $supplierId;

    public function __construct($supplierId)
    {
        $this->supplierId = $supplierId;
    }

    public function array(array $rows)
    {
        $lines = [];
        $errors = [];

        foreach ($rows as $index => $row) {
            $lineIndex = $index + 1;
            $article = Item::where('code', $row['article_code'])->first();

            if (!$article) {
                $errors[] = "Ligne {$lineIndex}: Article {$row['article_code']} non trouvé.";
                continue;
            }

            $lines[] = [
                'article_code' => $row['article_code'],
                'ordered_quantity' => $row['ordered_quantity'] ?? 1,
                'unit_price_ht' => $row['unit_price_ht'] ?? $article->cost_price,
                'remise' => $row['remise'] ?? 0,
            ];
        }

        if (!empty($errors)) {
            Session::flash('error', implode('<br>', $errors));
        }

        Session::flash('imported_lines', $lines);
Session::flash('imported_supplier_id', $this->supplierId);
    }
}