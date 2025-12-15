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
            'code','name','description','category','brand','unit','barcode',
            'cost_price','sale_price','tva_group','stock_min','stock_max','store',
            'location','is_active','supplier','Poids','Hauteur','Longueur','Largeur',
            'Ref_TecDoc','Code_pays','Code_douane'
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($columns, NULL, 'A1');

        // Exemple complet pour guider l'utilisateur
        $example = [
            '57044',
            'SUPPORT MOTEUR LA PIECE',
            '',
            'LIAISON AU SOL',
            'MULTIMARQUES',
            'PIECE',
            '8435108604300',
            '141.35',
            '141.35',
            'ASSUJ (20.00%)',
            '2',
            '0',
            'MAGASIN CENTRALE',
            'Emplacement',
            '1',
            'Exa - EXADIS',
            '', '', '', '', '', '', ''
        ];

        $sheet->fromArray($example, NULL, 'A2');

        $sheet->getStyle('A1:W1')->getFont()->setBold(true);
        foreach (range('A', 'W') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(false);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file'=>'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            Excel::import(new ItemsImport, $request->file('file'));
            return redirect()->back()->with('success','Importation réussie !');
        } catch (\Exception $e) {
            return redirect()->back()->with('error','Erreur lors de l’import : '.$e->getMessage());
        }
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file'=>'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $rows = $spreadsheet->getActiveSheet()->toArray();
        $headers = array_map('strtolower', $rows[0]);

        $dataPreview = [];
        $errors = [];

        for ($i=1; $i<count($rows); $i++) {
            $row = array_combine($headers, $rows[$i]);

            $isEmpty = true;
            foreach ($row as $v) { if(!empty($v)) { $isEmpty=false; break; } }
            if($isEmpty) continue;

            $existing = Item::where('code', $row['code'] ?? '')->first();
            $status = $existing ? '⚠️ Déjà existant' : '✅ Nouveau';

            if(empty($row['code']) || empty($row['name'])) {
                $errors[] = "Ligne ".($i+1)." : Code ou Nom manquant";
                $status = '❌ Erreur';
            }

            $dataPreview[] = array_merge(['row_number'=>$i+1,'status'=>$status], $row);
        }

        return response()->json(['preview'=>$dataPreview,'errors'=>$errors]);
    }
}
