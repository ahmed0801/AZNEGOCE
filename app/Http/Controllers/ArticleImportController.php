<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ItemsImport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ArticleImportController extends Controller
{
    public function showForm()
    {
        return view('articles.import');
    }

    public function downloadTemplate()
    {
        $fileName = 'articles_template.xlsx';
        $filePath = storage_path('app/' . $fileName);

        if (file_exists($filePath)) unlink($filePath);

        $columns = [
            'code', 'name', 'description', 'category', 'brand', 'unit', 'barcode',
            'cost_price', 'remise_achat', 'sale_price', 'tva_group', 'stock_min', 'stock_max', 'store',
            'location', 'is_active',
            'supplier',   'remise_achat',
            'supplier_2', 'cost_price_2', 'remise_achat_2',
            'supplier_3', 'cost_price_3', 'remise_achat_3',
            'Poids', 'Hauteur', 'Longueur', 'Largeur',
            'Ref_TecDoc', 'Code_pays', 'Code_douane', 'discount_group'
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($columns, NULL, 'A1');

        $example = [
            '57044', 'SUPPORT MOTEUR LA PIECE', '', 'LIAISON AU SOL', 'MULTIMARQUES',
            'PIECE', '8435108604300',
            '141.35', '0',                          // cost_price, remise_achat (fourn. 1)
            '183.76', 'ASSUJ (20.00%)',
            '2', '0', 'MAGASIN CENTRALE', 'A-12-3', '1',
            'Exa - EXADIS', '5.00',                  // supplier, remise_achat
            '', '', '',                              // supplier_2, cost_price_2, remise_achat_2
            '', '', '',                              // supplier_3, cost_price_3, remise_achat_3
            '', '', '', '',                          // Poids, Hauteur, Longueur, Largeur
            '', '', '', 'R0'                         // Ref_TecDoc, Code_pays, Code_douane, discount_group
        ];

        $nbCols  = count($columns);
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($nbCols);

        $sheet->fromArray($example, NULL, 'A2');
        $sheet->getStyle('A1:' . $lastCol . '1')->getFont()->setBold(true);

        // Colorier les groupes fournisseurs pour lisibilité
        $groupColors = [
            // fourn. 1 : colonnes supplier(Q=17) + remise_achat(R=18) → bleu clair
            ['Q', 'R', 'dce6f1'],
            // fourn. 2 : S, T, U → vert clair
            ['S', 'T', 'U', 'e2efda'],
            // fourn. 3 : V, W, X → orange clair
            ['V', 'W', 'X', 'fce4d6'],
        ];
        foreach ($groupColors as $grp) {
            $color = array_pop($grp);
            foreach ($grp as $letter) {
                $sheet->getStyle($letter . '1:' . $letter . '2')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB($color);
            }
        }

        for ($c = 1; $c <= $nbCols; $c++) {
            $sheet->getColumnDimensionByColumn($c)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(false);
    }
    

    /**
     * Import with selected columns only
     */
    public function import(Request $request)
    {
        $request->validate([
            'file'            => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'selected_columns' => 'nullable|array',
            'update_mode'     => 'nullable|in:skip,update',
        ]);

        $selectedColumns = $request->input('selected_columns', []);
        $updateMode      = $request->input('update_mode', 'update');

        try {
            $importer = new ItemsImport($selectedColumns, $updateMode);
            Excel::import($importer, $request->file('file'));

            $stats = $importer->getStats();

            $msg = "Importation réussie ! {$stats['created']} créé(s), {$stats['updated']} mis à jour, {$stats['skipped']} ignoré(s).";
            if (!empty($stats['errors'])) {
                $msg .= ' ' . count($stats['errors']) . ' erreur(s).';
            }

            return redirect()->back()->with('success', $msg)->with('import_stats', $stats);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'import : ' . $e->getMessage());
        }
    }

    /**
     * Preview the file and return columns + rows
     */
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $file        = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $rows        = $spreadsheet->getActiveSheet()->toArray();

        if (empty($rows)) {
            return response()->json(['error' => 'Fichier vide'], 422);
        }

        $headers    = array_map('strtolower', array_map('trim', $rows[0]));
        $dataPreview = [];
        $errors      = [];

        $limit = min(count($rows), 11); // header + 10 rows max for preview

        for ($i = 1; $i < $limit; $i++) {
            if (count($rows[$i]) < count($headers)) {
                $rows[$i] = array_pad($rows[$i], count($headers), '');
            }
            $row = array_combine($headers, array_slice($rows[$i], 0, count($headers)));

            $isEmpty = true;
            foreach ($row as $v) {
                if (trim((string)$v) !== '') { $isEmpty = false; break; }
            }
            if ($isEmpty) continue;

            $existing = Item::where('code', trim($row['code'] ?? ''))->first();
            $status   = $existing ? 'update' : 'new';

            if (empty(trim($row['code'] ?? ''))) {
                $errors[] = "Ligne " . ($i + 1) . " : Code manquant";
                $status   = 'error';
            }

            $dataPreview[] = array_merge(['row_number' => $i + 1, 'status' => $status], $row);
        }

        $totalRows = count($rows) - 1;

        return response()->json([
            'preview'    => $dataPreview,
            'errors'     => $errors,
            'headers'    => $headers,
            'total_rows' => $totalRows,
        ]);
    }
}