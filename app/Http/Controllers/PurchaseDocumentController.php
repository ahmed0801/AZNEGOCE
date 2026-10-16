<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Store;
use App\Models\Supplier;
use App\Services\PriceAdvisorService;
use App\Services\PurchaseDocumentService;
use App\Services\PurchaseInsightService;
use App\Services\PurchaseException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Page unique de création d'un document d'achat (comme la page de vente) :
 * Projet d'achat · Commande achat · Réception · Réception + facture.
 * Toute la logique métier est dans PurchaseDocumentService (testée à part).
 */
class PurchaseDocumentController extends Controller
{
    /** @var PurchaseDocumentService */
    private $service;

    public function __construct(PurchaseDocumentService $service)
    {
        $this->service = $service;
    }

    public function create()
    {
        $suppliers = Supplier::with('tvaGroup')->orderBy('name')->get();
        $stores    = Store::orderBy('id')->get();

        return view('purchases.document_create', ['suppliers' => $suppliers, 'stores' => $stores, 'mode' => 'create', 'editDoc' => null,
            'prefill' => [], 'formAction' => route('purchase_docs.store'), 'formMethod' => 'POST']);
    }

    /**
     * Page de modification d'un projet ou d'une commande : la page de création, préremplie.
     * Les lignes sont fournies dans le même format que « old() » pour réutiliser la reprise de saisie de la page.
     *
     * @param  string  $mode  'project' | 'order'
     * @param  \App\Models\PurchaseProject|\App\Models\PurchaseOrder  $doc
     */
    public function renderEdit(string $mode, $doc)
    {
        $suppliers = Supplier::with('tvaGroup')->orderBy('name')->get();
        $stores    = Store::orderBy('id')->get();
        $supplier  = $suppliers->firstWhere('id', $doc->supplier_id);

        $prefill = [];
        $codes = $doc->lines->pluck('article_code')->unique()->values()->all();
        $items = Item::with(['brand', 'tvaGroup', 'stocks'])->whereIn('code', $codes)->get()->keyBy('code');
        foreach ($doc->lines as $i => $l) {
            $it = $items[$l->article_code] ?? null;
            $m  = $it ? $this->mapItem($it, $supplier) : null;
            $prefill[$i] = [
                'article_code' => $l->article_code, 'item_name' => $m['name'] ?? $l->article_code, 'quantity' => (int) $l->ordered_quantity,
                'unit_price_ht' => (float) $l->unit_price_ht, 'remise' => (float) $l->remise,
                'tva' => $l->tva !== null ? (float) $l->tva : (float) ($doc->tva_rate ?? 0),
                'sale_price' => $m['sale_price'] ?? 0, '_stock' => $m['stock_quantity'] ?? 0, '_own' => ($m['own_supplier'] ?? true) ? 1 : 0,
            ];
        }

        if ($mode === 'project') {
            $formAction = route('purchaseprojects.update', $doc->id);
            $formMethod = 'POST';
        } else {
            $formAction = route('purchases.update', $doc->numdoc);
            $formMethod = 'PUT';
        }

        return view('purchases.document_create', [
            'suppliers' => $suppliers, 'stores' => $stores, 'mode' => $mode, 'editDoc' => $doc,
            'prefill' => $prefill, 'formAction' => $formAction, 'formMethod' => $formMethod,
        ]);
    }

    /**
     * Recherche d'articles côté serveur.
     *  - ?query=...            : code (début) / désignation / code-barres
     *  - ?codes[]=A&codes[]=B  : relecture exacte (changement de fournisseur)
     * Le prix proposé est celui du fournisseur choisi quand l'article le référence,
     * sinon celui du fournisseur principal (signalé « autre fournisseur »).
     */
    public function searchItems(Request $request)
    {
        $supplier = $request->filled('supplier_id') ? Supplier::with('tvaGroup')->find($request->input('supplier_id')) : null;

        $q = Item::with(['brand', 'tvaGroup', 'stocks'])->where('is_active', 1);

        if ($request->has('codes')) {
            $q->whereIn('code', array_slice((array) $request->input('codes'), 0, 300));
        } else {
            $term = trim((string) $request->input('query', ''));
            if (mb_strlen($term) < 2) {
                return response()->json([]);
            }
            $like = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $term);
            $q->where(function ($w) use ($like, $term) {
                $w->where('code', 'like', $like . '%')
                  ->orWhere('name', 'like', '%' . $like . '%')
                  ->orWhere('barcode', $term);
            })->orderByRaw('CASE WHEN code LIKE ? THEN 0 ELSE 1 END', [$like . '%'])->orderBy('code');
        }

        $items = $q->limit(40)->get()->map(function (Item $item) use ($supplier) {
            return $this->mapItem($item, $supplier);
        })->values();

        return response()->json($items);
    }

    /** Représentation JSON d'un article pour la page (prix / remise / TVA selon le fournisseur). */
    private function mapItem(Item $item, ?Supplier $supplier): array
    {
        [$cost, $remise, $mine] = $this->service->costFor($item, $supplier);

        return [
            'code'           => $item->code,
            'name'           => $item->name,
            'brand'          => $item->brand->name ?? null,
            'location'       => $item->location,
            'stock_quantity' => (float) $item->stocks->sum('quantity'),
            'cost_price'     => (float) $cost,
            'remise_achat'   => (float) $remise,
            'own_supplier'   => $mine,            // false => le prix vient d'un autre fournisseur
            'sale_price'     => (float) $item->sale_price,
            'tva'            => $supplier ? $this->service->defaultTva($supplier, $item) : (float) ($item->tvaGroup->rate ?? 0),
        ];
    }

    /* ═════════════ Excel : modèle, export des lignes, import (façon « Modifier dans Excel » de Business Central) ═════════════ */

    private const XLS_HEADERS = ['Référence *', 'Désignation', 'Quantité *', 'Prix achat HT', 'Remise %', 'TVA %', 'Prix de vente HT'];

    /** Normalise un titre de colonne : sans accent, minuscules, lettres/chiffres seulement. */
    private function normHeader($v): string
    {
        return preg_replace('/[^a-z0-9]/', '', strtolower(Str::ascii((string) $v)));
    }

    private function colKey(string $h): ?string
    {
        $map = [
            'ref'     => ['reference', 'ref', 'refarticle', 'code', 'codearticle', 'article', 'referenceobligatoire'],
            'name'    => ['designation', 'libelle', 'nom', 'description', 'name'],
            'qty'     => ['quantite', 'qte', 'qty', 'quantity'],
            'pu'      => ['prixachatht', 'prixachat', 'pu', 'puht', 'prixht', 'prixunitaire', 'costprice', 'cost'],
            'remise'  => ['remise', 'rem', 'remiseachat'],
            'tva'     => ['tva', 'tauxtva'],
            'pv'      => ['prixventeht', 'prixdeventeht', 'prixdevente', 'prixvente', 'pv', 'pvht'],
        ];
        foreach ($map as $key => $aliases) {
            if (in_array($h, $aliases, true)) return $key;
        }
        return null;
    }

    private function buildSheet(array $rows, string $help): Spreadsheet
    {
        $book = new Spreadsheet();
        $ws = $book->getActiveSheet();
        $ws->setTitle('Lignes');
        foreach (self::XLS_HEADERS as $i => $h) {
            $ws->setCellValueByColumnAndRow($i + 1, 1, $h);
        }
        $ws->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F2937']],
        ]);
        $ws->getStyle('A1')->getFill()->getStartColor()->setRGB('B91C1C');
        $ws->getStyle('C1')->getFill()->getStartColor()->setRGB('B91C1C');
        foreach (['A' => 20, 'B' => 42, 'C' => 12, 'D' => 15, 'E' => 11, 'F' => 9, 'G' => 17] as $col => $w) {
            $ws->getColumnDimension($col)->setWidth($w);
        }
        $ws->freezePane('A2');

        $r = 2;
        foreach ($rows as $row) {
            $ws->setCellValueExplicitByColumnAndRow(1, $r, (string) $row['code'], DataType::TYPE_STRING);   // texte : pas d'interprétation (ni formule, ni notation scientifique)
            $ws->setCellValueExplicitByColumnAndRow(2, $r, (string) $row['name'], DataType::TYPE_STRING);
            $ws->setCellValueByColumnAndRow(3, $r, $row['qty']);
            foreach ([4 => 'pu', 5 => 'remise', 6 => 'tva', 7 => 'pv'] as $c => $k) {
                if ($row[$k] !== null && $row[$k] !== '') $ws->setCellValueByColumnAndRow($c, $r, (float) $row[$k]);
            }
            $r++;
        }
        $last = max(2000, $r + 500);
        $ws->getStyle("A2:A{$last}")->getNumberFormat()->setFormatCode('@');
        $ws->getStyle("D2:D{$last}")->getNumberFormat()->setFormatCode('#,##0.00');
        $ws->getStyle("G2:G{$last}")->getNumberFormat()->setFormatCode('#,##0.00');
        $ws->getStyle("E2:F{$last}")->getNumberFormat()->setFormatCode('0.00');

        $dv = new DataValidation();
        $dv->setType(DataValidation::TYPE_DECIMAL)->setOperator(DataValidation::OPERATOR_GREATERTHAN)->setFormula1('0')
           ->setShowErrorMessage(true)->setErrorTitle('Quantité')->setError('La quantité doit être supérieure à 0.');
        $ws->setDataValidation("C2:C{$last}", $dv);
        $dv2 = new DataValidation();
        $dv2->setType(DataValidation::TYPE_DECIMAL)->setOperator(DataValidation::OPERATOR_BETWEEN)->setFormula1('0')->setFormula2('100')
            ->setShowErrorMessage(true)->setErrorTitle('Pourcentage')->setError('Saisissez un pourcentage entre 0 et 100.');
        $ws->setDataValidation("E2:F{$last}", $dv2);

        $aide = $book->createSheet();
        $aide->setTitle('Aide');
        $aide->getColumnDimension('A')->setWidth(120);
        foreach (explode("\n", $help) as $i => $line) {
            $aide->setCellValueExplicitByColumnAndRow(1, $i + 1, $line, DataType::TYPE_STRING);
        }
        $aide->getStyle('A1')->getFont()->setBold(true)->setSize(13);
        $book->setActiveSheetIndex(0);

        return $book;
    }

    private function sendBook(Spreadsheet $book, string $filename)
    {
        return response()->streamDownload(function () use ($book) {
            (new Xlsx($book))->save('php://output');
        }, $filename, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
    }

    private function helpText(): string
    {
        return "Comment remplir ce fichier\n"
            . "1. Une ligne par article, à partir de la ligne 2 de la feuille « Lignes ». Ne changez pas les titres de colonnes (l'ordre des colonnes n'a pas d'importance).\n"
            . "2. Référence * : code article (ou code-barres). Colonne obligatoire.\n"
            . "3. Quantité * : obligatoire, supérieure à 0.\n"
            . "4. Prix achat HT, Remise %, TVA % : facultatifs. Laissés vides, ils sont repris de la fiche article / du fournisseur choisi dans la page.\n"
            . "5. Désignation : facultative pour un article existant (elle est ignorée). Pour une référence inconnue, renseigner la désignation crée une ligne « nouvel article » (créé à l'enregistrement du document) ; sinon la ligne est refusée.\n"
            . "6. Prix de vente HT : utilisé uniquement pour un nouvel article. Le prix de vente d'un article existant n'est jamais modifié par un achat.\n"
            . "7. Une même référence présente sur plusieurs lignes est regroupée (quantités additionnées).\n"
            . "8. Enregistrez le fichier (.xlsx) puis utilisez « Importer Excel » dans la page. Les lignes importées s'ajoutent au document ou le remplacent, selon votre choix. Rien n'est enregistré avant que vous cliquiez sur Commande / Réception.\n"
            . "9. Astuce : « Exporter les lignes » télécharge les lignes déjà saisies dans ce même format : modifiez-les dans Excel puis réimportez-les (mode « Remplacer »).";
    }

    /** GET : modèle vide à remplir. */
    public function downloadTemplate(Request $request)
    {
        $book = $this->buildSheet([], $this->helpText());
        return $this->sendBook($book, 'modele_lignes_achat.xlsx');
    }

    /** POST : exporte les lignes actuellement saisies dans la page (même format que le modèle). */
    public function exportLines(Request $request)
    {
        $raw = json_decode((string) $request->input('lines_json', '[]'), true);
        if (!is_array($raw)) $raw = [];
        $rows = [];
        foreach (array_slice($raw, 0, 2000) as $l) {
            if (!is_array($l)) continue;
            $rows[] = [
                'code'   => (string) ($l['code'] ?? ''),
                'name'   => (string) ($l['name'] ?? ''),
                'qty'    => (float) ($l['qty'] ?? 0),
                'pu'     => isset($l['pu']) ? (float) $l['pu'] : null,
                'remise' => isset($l['remise']) ? (float) $l['remise'] : null,
                'tva'    => isset($l['tva']) ? (float) $l['tva'] : null,
                'pv'     => !empty($l['isNew']) && isset($l['sale']) ? (float) $l['sale'] : null,
            ];
        }
        $supplier = $request->filled('supplier_id') ? Supplier::find($request->input('supplier_id')) : null;
        $name = 'lignes_achat_' . ($supplier ? Str::slug($supplier->code ?: $supplier->name) . '_' : '') . now()->format('Ymd_His') . '.xlsx';
        return $this->sendBook($this->buildSheet($rows, $this->helpText()), $name);
    }

    private function cellText($v): string
    {
        if (is_float($v) && floor($v) == $v && abs($v) < 1e15) return (string) (int) $v;   // 57044 et non 57044.0
        return trim((string) $v);
    }

    private function cellNum($v): ?float
    {
        if ($v === null || $v === '') return null;
        if (is_numeric($v)) return (float) $v;
        $t = str_replace(["\xc2\xa0", ' ', '€', '%'], '', (string) $v);
        $t = str_replace(',', '.', $t);
        return is_numeric($t) ? (float) $t : NAN;
    }

    /** POST : lit le fichier rempli et renvoie les lignes prêtes à être ajoutées à la page (rien n'est enregistré). */
    public function importLines(Request $request)
    {
        $request->validate([
            'file'        => 'required|file|max:5120|mimes:xlsx,xls,csv,txt',
            'supplier_id' => 'required|exists:suppliers,id',
        ], [
            'file.required'        => 'Choisissez un fichier Excel.',
            'file.mimes'           => 'Format non pris en charge (.xlsx, .xls ou .csv).',
            'file.max'             => 'Fichier trop volumineux (5 Mo maximum).',
            'supplier_id.required' => "Choisissez d'abord un fournisseur.",
        ]);

        $supplier = Supplier::with('tvaGroup')->findOrFail($request->input('supplier_id'));

        try {
            $reader = IOFactory::createReaderForFile($request->file('file')->getRealPath());
            $reader->setReadDataOnly(true);
            $sheet = $reader->load($request->file('file')->getRealPath())->getSheet(0);
            $grid = $sheet->toArray(null, false, false, false);
        } catch (\Throwable $e) {
            return response()->json(['ok' => false, 'message' => 'Fichier illisible : ' . $e->getMessage()], 422);
        }

        // ligne de titres : la première (parmi les 10 premières) qui contient Référence et Quantité
        $cols = []; $headerRow = null;
        foreach (array_slice($grid, 0, 10, true) as $ri => $row) {
            $found = [];
            foreach ($row as $ci => $cell) {
                $k = $this->colKey($this->normHeader($cell));
                if ($k && !isset($found[$k])) $found[$k] = $ci;
            }
            if (isset($found['ref'], $found['qty'])) { $cols = $found; $headerRow = $ri; break; }
        }
        if ($headerRow === null) {
            return response()->json(['ok' => false, 'message' => 'Colonnes « Référence » et « Quantité » introuvables sur la première feuille. Utilisez le modèle téléchargeable.'], 422);
        }

        $parsed = []; $errors = []; $warnings = [];
        $count = 0;
        foreach ($grid as $ri => $row) {
            if ($ri <= $headerRow) continue;
            $get = function ($k) use ($cols, $row) { return isset($cols[$k]) ? ($row[$cols[$k]] ?? null) : null; };
            $code = $this->cellText($get('ref'));
            $qtyRaw = $get('qty');
            if ($code === '' && ($qtyRaw === null || $qtyRaw === '') && $this->cellText($get('name')) === '') continue;   // ligne vide
            if (++$count > 1000) { $errors[] = 'Import limité à 1000 lignes : le reste du fichier a été ignoré.'; break; }
            $n = $ri + 1;                                                                                                   // numéro de ligne Excel

            if ($code === '') { $errors[] = "Ligne $n : référence manquante."; continue; }
            if (strpbrk($code[0], '=+-@') !== false) { $errors[] = "Ligne $n : référence « $code » invalide (caractère interdit en début de référence)."; continue; }
            $qty = $this->cellNum($qtyRaw);
            if ($qty === null || is_nan($qty) || $qty <= 0) { $errors[] = "Ligne $n ($code) : quantité manquante ou invalide."; continue; }

            $over = [];
            foreach (['pu', 'remise', 'tva', 'pv'] as $k) {
                $v = $this->cellNum($get($k));
                if ($v !== null && is_nan($v)) { $errors[] = "Ligne $n ($code) : valeur non numérique dans « " . $k . " »."; continue 2; }
                if ($v !== null && ($v < 0 || (in_array($k, ['remise', 'tva'], true) && $v > 100))) { $errors[] = "Ligne $n ($code) : valeur hors limites dans « " . $k . " »."; continue 2; }
                $over[$k] = $v;
            }
            $parsed[] = ['n' => $n, 'code' => $code, 'name' => $this->cellText($get('name')), 'qty' => $qty] + $over;
        }

        // résolution des articles (code puis code-barres), insensible à la casse
        $codes = array_values(array_unique(array_map(function ($p) { return $p['code']; }, $parsed)));
        $byKey = [];
        if ($codes) {
            $found = Item::with(['brand', 'tvaGroup', 'stocks'])->where('is_active', 1)
                ->where(function ($w) use ($codes) { $w->whereIn('code', $codes)->orWhereIn('barcode', $codes); })->get();
            foreach ($found as $it) {
                $byKey[mb_strtolower($it->code)] = $it;
                if ($it->barcode && !isset($byKey[mb_strtolower($it->barcode)])) $byKey[mb_strtolower($it->barcode)] = $it;
            }
        }

        $lines = []; $index = [];
        foreach ($parsed as $p) {
            $item = $byKey[mb_strtolower($p['code'])] ?? null;
            if ($item) {
                $m = $this->mapItem($item, $supplier);
                $line = [
                    'code' => $m['code'], 'name' => $m['name'], 'stock' => $m['stock_quantity'], 'qty' => $p['qty'],
                    'pu' => $p['pu'] ?? $m['cost_price'], 'remise' => $p['remise'] ?? $m['remise_achat'], 'tva' => $p['tva'] ?? $m['tva'],
                    'sale' => $m['sale_price'], 'own' => $m['own_supplier'], 'isNew' => false,
                ];
                $key = 'e:' . mb_strtolower($m['code']);
            } elseif ($p['name'] !== '') {
                $line = [
                    'code' => mb_strtoupper($p['code']), 'name' => $p['name'], 'stock' => 0, 'qty' => $p['qty'],
                    'pu' => $p['pu'] ?? 0, 'remise' => $p['remise'] ?? 0, 'tva' => $p['tva'] ?? $this->service->defaultTva($supplier, null),
                    'sale' => $p['pv'] ?? 0, 'own' => true, 'isNew' => true,
                ];
                $warnings[] = "Ligne {$p['n']} : « {$p['code']} » n'existe pas — ligne « nouvel article » (créé à l'enregistrement).";
                $key = 'n:' . mb_strtolower($p['code']);
            } else {
                $errors[] = "Ligne {$p['n']} : article « {$p['code']} » introuvable (ajoutez une désignation pour le créer).";
                continue;
            }
            if (isset($index[$key])) {                                                                                     // même référence : regroupée
                $lines[$index[$key]]['qty'] += $line['qty'];
                $warnings[] = "Ligne {$p['n']} : « {$p['code']} » déjà présente — quantités regroupées.";
                continue;
            }
            $index[$key] = count($lines);
            $lines[] = $line;
        }

        return response()->json([
            'ok' => true, 'lines' => $lines, 'errors' => $errors, 'warnings' => $warnings,
            'rows' => $count,
        ]);
    }

    /** POST : propositions de prix de vente pour les lignes saisies (rien n'est modifié). */
    public function priceReview(Request $request, PriceAdvisorService $advisor)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'lines'       => 'required|array|min:1|max:300',
            'strategy'    => 'nullable|in:keep,target,category',
            'margin'      => 'nullable|numeric|min:0|max:1000',
            'min_margin'  => 'nullable|numeric|min:0|max:1000',
            'round'       => 'nullable|in:none,0.05,0.10,1,0.90',
        ]);
        $supplier = Supplier::with('tvaGroup')->findOrFail($request->input('supplier_id'));
        $rows = $advisor->review($supplier, array_values((array) $request->input('lines')), $request->only(['strategy', 'margin', 'min_margin', 'round']));
        return response()->json(['ok' => true, 'rows' => $rows]);
    }

    /** POST : applique les prix de vente cochés par l'utilisateur (historisés, annulables). */
    public function applyPrices(Request $request, PriceAdvisorService $advisor)
    {
        $request->validate([
            'changes'            => 'required|array|min:1|max:300',
            'changes.*.code'     => 'required|string|max:60',
            'changes.*.new_pv'   => 'required|numeric|min:0.01|max:9999999',
            'changes.*.net'      => 'nullable|numeric',
            'strategy'           => 'nullable|string|max:30',
            'source'             => 'nullable|in:achat,annulation',
        ]);
        $done = $advisor->apply((array) $request->input('changes'), optional($request->user())->id, (string) $request->input('strategy', 'manual'), (string) $request->input('source', 'achat'));
        return response()->json(['ok' => true, 'done' => $done]);
    }

    /** GET : aide à la décision — ventes, stock, déjà commandé, quantité suggérée pour les articles de la page. */
    public function insights(Request $request, PurchaseInsightService $insights)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'codes'       => 'required|array|max:400',
            'cover'       => 'nullable|integer|min:7|max:365',
            'lead'        => 'nullable|integer|min:0|max:180',
        ]);
        $supplier = Supplier::findOrFail($request->input('supplier_id'));
        $res = $insights->metrics($supplier, array_map('strval', (array) $request->input('codes')), $request->only(['cover', 'lead', 'exclude_order_id']));
        return response()->json(['ok' => true] + $res);
    }

    /** GET : articles du fournisseur à réapprovisionner qui ne sont pas encore dans le document. */
    public function replenishment(Request $request, PurchaseInsightService $insights)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'cover'       => 'nullable|integer|min:7|max:365',
            'lead'        => 'nullable|integer|min:0|max:180',
        ]);
        $supplier = Supplier::findOrFail($request->input('supplier_id'));
        $res = $insights->replenishment($supplier, array_map('strval', (array) $request->input('exclude', [])), $request->only(['cover', 'lead', 'exclude_order_id']));
        return response()->json(['ok' => true] + $res);
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'  => 'required|exists:suppliers,id',
            'action'       => 'required|in:project,order,reception,receive_invoice',
            'order_date'   => 'nullable|date',
            'store_id'     => 'nullable|integer',
            'due_date'     => 'nullable|date',
            'lines'        => 'required|array|min:1',
        ], [
            'supplier_id.required' => 'Choisissez un fournisseur.',
            'lines.required'       => 'Ajoutez au moins une ligne au document.',
        ]);

        $supplier = Supplier::with('tvaGroup')->findOrFail($request->input('supplier_id'));
        $header = [
            'date'         => $request->input('order_date') ?: now()->toDateString(),
            'notes'        => $request->input('notes'),
            'store_id'     => $request->input('store_id') ?: null,
            'supplier_ref' => $request->input('supplier_ref'),
            'invoice_date' => $request->input('invoice_date') ?: ($request->input('order_date') ?: now()->toDateString()),
            'due_date'     => $request->input('due_date') ?: null,
        ];
        $lines = (array) $request->input('lines');

        try {
            switch ($request->input('action')) {
                case 'project':
                    $doc = $this->service->createProject($supplier, $header, $lines);
                    return redirect()->route('purchaseprojects.list')->with('success', "Projet d'achat {$doc->numdoc} créé.");

                case 'order':
                    $header['type'] = 'Commande stock';
                    $doc = $this->service->createOrder($supplier, $header, $lines);
                    return redirect()->route('purchases.list')->with('success', "Commande d'achat {$doc->numdoc} créée. Le stock entrera à la réception.");

                case 'reception':
                    $doc = $this->service->createReception($supplier, $header, $lines);
                    return redirect()->route('receptions.index')->with('success', "Réception {$doc->numdoc} enregistrée : le stock est mis à jour.");

                default: // receive_invoice
                    $res = $this->service->receiveAndInvoice($supplier, $header, $lines);
                    return redirect()->route('invoices.list')->with(
                        'success',
                        "Réception {$res['reception']->numdoc} et facture {$res['invoice']->numdoc} enregistrées : stock et solde fournisseur mis à jour."
                    );
            }
        } catch (PurchaseException $e) {
            return back()->withInput()->with('error', e($e->getMessage()));
        }
    }
}
