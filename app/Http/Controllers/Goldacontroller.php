<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use App\Models\Brand;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\GoldaImportSetting;
use League\Csv\Reader;
use League\Csv\Statement;

class GoldaController extends Controller
{
    // ══════════════════════════════════════════════════════════
    // PAGE PRINCIPALE
    // ══════════════════════════════════════════════════════════
    public function index()
    {
        $marques    = GoldaImportSetting::with('brand')->orderBy('nom_marque')->get();
        $suppliers  = Supplier::orderBy('name')->get(['code', 'name']);
        $brands     = Brand::orderBy('name')->get(['id', 'name']);
        $lastImport = cache('golda_last_import');

        return view('admin.golda', compact('marques', 'suppliers', 'brands', 'lastImport'));
    }

    // ══════════════════════════════════════════════════════════
    // SYNCHRONISER LA LISTE DES MARQUES DEPUIS LE FTP
    // ══════════════════════════════════════════════════════════
    public function syncMarques()
    {
        try {
            $marques = $this->fetchMarquesFromFtp();
            $created = 0;
            $existing = 0;

            foreach ($marques as $m) {
                // code_marque = Nom_Marque en majuscules (ex: "GATES", "VALEO")
                $setting = GoldaImportSetting::firstOrNew(['code_marque' => $m['code_marque']]);

                if (!$setting->exists) {
                    $setting->nom_marque         = $m['nom_marque'];
                    $setting->prefixe_tarif      = $m['prefixe'];
                    $setting->active             = false; // inactif par défaut
                    $setting->update_cost_price  = true;
                    $setting->create_new_items   = true;
                    $setting->deactivate_removed = true;

                    // Auto-lier une Brand si elle existe déjà avec le même nom
                    $brand = Brand::whereRaw('LOWER(name) = ?', [strtolower($m['nom_marque'])])->first();
                    if ($brand) {
                        $setting->brand_id = $brand->id;
                    }

                    $setting->save();
                    $created++;
                } else {
                    // Mettre à jour le nom et préfixe au cas où GOLDA les a changés
                    $setting->nom_marque    = $m['nom_marque'];
                    $setting->prefixe_tarif = $m['prefixe'];
                    $setting->save();
                    $existing++;
                }
            }

            return response()->json([
                'success'  => true,
                'created'  => $created,
                'existing' => $existing,
                'total'    => count($marques),
            ]);

        } catch (\Exception $e) {
            Log::error("GOLDA syncMarques: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ══════════════════════════════════════════════════════════
    // SAUVEGARDER LES PARAMÈTRES D'UNE MARQUE
    // ══════════════════════════════════════════════════════════
    public function saveSettings(Request $request)
    {
        $validated = $request->validate([
            'code_marque'        => 'required|string',
            'active'             => 'boolean',
            'brand_id'           => 'nullable|integer|exists:brands,id',
            'supplier_code'      => 'nullable|string',
            'update_cost_price'  => 'boolean',
            'update_sale_price'  => 'boolean',
            'update_description' => 'boolean',
            'update_barcode'     => 'boolean',
            'update_dimensions'  => 'boolean',
            'create_new_items'   => 'boolean',
            'deactivate_removed' => 'boolean',
            'margin_override'    => 'nullable|numeric|min:0|max:200',
        ]);

        $setting = GoldaImportSetting::where('code_marque', $validated['code_marque'])->firstOrFail();
        $setting->update($validated);

        return response()->json(['success' => true]);
    }

    // ══════════════════════════════════════════════════════════
    // LANCER L'IMPORT (une marque ou toutes)
    // ══════════════════════════════════════════════════════════
    public function runImport(Request $request)
    {
        $codeMarque = $request->input('code_marque');
        $dryRun     = (bool) $request->input('dry_run', false);

        try {
            set_time_limit(600);
            ini_set('max_execution_time', 600);

            $params = [];
            if ($codeMarque) $params['--marque']   = $codeMarque;
            if ($dryRun)     $params['--dry-run']  = true;

            Artisan::call('golda:import', $params);
            $output = Artisan::output();

            cache(['golda_last_import' => now()->format('d/m/Y à H:i')], 86400);

            $marques = GoldaImportSetting::orderBy('nom_marque')->get();

            return response()->json([
                'success' => true,
                'output'  => $output,
                'marques' => $marques->map(function ($m) {
                    return [
                        'code_marque'            => $m->code_marque,
                        'last_import_count'      => $m->last_import_count,
                        'last_deactivated_count' => $m->last_deactivated_count,
                        'last_imported_at'       => $m->last_imported_at
                            ? $m->last_imported_at->format('d/m H:i')
                            : null,
                    ];
                }),
            ]);

        } catch (\Exception $e) {
            Log::error("GOLDA runImport: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // ══════════════════════════════════════════════════════════
    // HELPER : LIRE LE FTP — infos_tarifs.csv UNIQUEMENT
    // Rapide : télécharge un seul fichier de quelques Ko
    // ══════════════════════════════════════════════════════════
    private function fetchMarquesFromFtp(): array
    {
        $ftp = Storage::createFtpDriver([
            'host'     => env('GOLDA_FTP_HOST', 'golda.fr'),
            'username' => env('GOLDA_FTP_USER', 'tdlfg8223'),
            'password' => env('GOLDA_FTP_PASS', 'B7w42bz!36'),
            'root'     => '/tarifs/',
            'passive'  => true,
            'ssl'      => false,
        ]);

        Storage::disk('local')->makeDirectory('golda');
        Storage::disk('local')->put('golda/infos_tarifs.csv', $ftp->get('infos_tarifs.csv'));
        $localFile = storage_path('app/golda/infos_tarifs.csv');

        // Nettoyage BOM + encodage
        $content = file_get_contents($localFile);
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        $content = mb_convert_encoding(
            $content, 'UTF-8',
            mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true) ?: 'UTF-8'
        );
        file_put_contents($localFile, $content);

        $csv = Reader::createFromPath($localFile, 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);

        $ignorer = ['C-CARPARTS', 'DRIV', 'ELECTRICFIL SERVICE', 'FRANCELEC',
                    'IRONTEK', 'LRT AUTOMOTIVE GMBH', 'EXADIS'];

        // Index par Nom_Marque (majuscules) — priorité "En vigueur" > "Futur"
        $marques = [];

        foreach (Statement::create()->process($csv) as $row) {
            $nomMarque    = trim($row['Nom_Marque']      ?? '');
            $nomFourn     = trim($row['Nom_Fournisseur'] ?? '');
            $prefixe      = trim($row['Prefixe_Tarif']  ?? '');
            $tarifType    = trim($row['Tarif_Type']      ?? '');
            $fichier      = trim($row['Nom_Fichier_CSV'] ?? '');
            $fichierSuppr = trim($row['Nom_Fichier_Articles_Supprimes'] ?? '');

            if (!$nomMarque || !$prefixe || !$fichier) continue;

            // Ignorer les marques/fournisseurs blacklistés
            if (in_array(strtoupper($nomMarque), array_map('strtoupper', $ignorer))) continue;
            if (in_array(strtoupper($nomFourn),  array_map('strtoupper', $ignorer))) continue;

            $cle = strtoupper($nomMarque);

            // Écraser uniquement si pas encore défini ou si "En vigueur"
            if (!isset($marques[$cle]) || $tarifType === 'En vigueur') {
                $marques[$cle] = [
                    'code_marque'     => $cle,
                    'nom_marque'      => $nomMarque,
                    'nom_fournisseur' => $nomFourn,
                    'prefixe'         => $prefixe,
                    'tarif_type'      => $tarifType,
                    'fichier'         => $fichier,
                    'fichier_suppr'   => $fichierSuppr,
                ];
            }
        }

        // Trier par nom de marque
        usort($marques, function ($a, $b) {
            return strcmp($a['nom_marque'], $b['nom_marque']);
        });

        return array_values($marques);
    }
}