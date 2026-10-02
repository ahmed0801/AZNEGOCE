<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Brand;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\GoldaImportSetting;
use App\Mail\GoldaImportReport;
use League\Csv\Reader;
use League\Csv\Statement;

class ImportGoldaTarifs extends Command
{
    protected $signature = 'golda:import
                            {--marque= : Code marque à importer uniquement}
                            {--dry-run : Simuler sans écrire en base}';

    protected $description = 'Importe les tarifs GOLDA (par marque, avec règles configurables)';

    // PHP 7.4 : pas de typed properties
    private $dryRun = false;

    public function handle()
    {
        $this->dryRun   = (bool) $this->option('dry-run');
        $marqueFilter   = $this->option('marque') ? strtoupper(trim($this->option('marque'))) : null;

        if ($this->dryRun) {
            $this->warn('⚠️  MODE SIMULATION — aucune écriture en base');
        }

        $this->info("🚀 Début import GOLDA" . ($marqueFilter ? " — marque : {$marqueFilter}" : " — toutes marques actives"));

        $report = ['marques' => [], 'totalImported' => 0, 'totalDeactivated' => 0, 'errors' => []];

        // ── Connexion FTP ────────────────────────────────────────
        try {
            $ftp = Storage::createFtpDriver([
                'host'     => env('GOLDA_FTP_HOST', 'golda.fr'),
                'username' => env('GOLDA_FTP_USER', 'tdlfg8223'),
                'password' => env('GOLDA_FTP_PASS', 'B7w42bz!36'),
                'root'     => '/tarifs/',
                'passive'  => true,
                'ssl'      => false,
            ]);
        } catch (\Exception $e) {
            $this->error("❌ Connexion FTP impossible : " . $e->getMessage());
            return 1;
        }

        Storage::disk('local')->makeDirectory('golda/csv');

        // ── Télécharger infos_tarifs.csv ─────────────────────────
        $localInfoFile = storage_path('app/golda/infos_tarifs.csv');
        Storage::disk('local')->put('golda/infos_tarifs.csv', $ftp->get('infos_tarifs.csv'));
        $this->cleanCsvFile($localInfoFile);

        // ── Lire infos_tarifs ────────────────────────────────────
        $csv = Reader::createFromPath($localInfoFile, 'r');
        $csv->setDelimiter(';'); // confirmé
        $csv->setHeaderOffset(0);
        $infoRecords = Statement::create()->process($csv);

        $this->info('📋 En-têtes infos_tarifs : ' . implode(' | ', $csv->getHeader()));

        // ── Construire index par Nom_Marque (majuscules) ─────────
        // Priorité : "En vigueur" > "Futur"
        // Une marque peut avoir plusieurs préfixes (ex: DRI avec 5 fichiers)
        // → on garde le plus récent "En vigueur"
        $marquesFichiers = [];
        foreach ($infoRecords as $row) {
            $nomMarque    = trim($row['Nom_Marque']     ?? '');
            $nomFourn     = trim($row['Nom_Fournisseur'] ?? '');
            $prefixe      = trim($row['Prefixe_Tarif']  ?? '');
            $tarifType    = trim($row['Tarif_Type']     ?? '');
            $fichier      = trim($row['Nom_Fichier_CSV'] ?? '');
            $fichierSuppr = trim($row['Nom_Fichier_Articles_Supprimes'] ?? '');

            if (!$nomMarque || !$prefixe || !$fichier) continue;

            $cle = strtoupper($nomMarque);

            // Écraser seulement si pas encore défini, ou si on a "En vigueur"
            if (!isset($marquesFichiers[$cle]) || $tarifType === 'En vigueur') {
                $marquesFichiers[$cle] = [
                    'prefixe'         => $prefixe,
                    'fichier'         => $fichier,
                    'suppr_fichier'   => $fichierSuppr,
                    'nom_fournisseur' => $nomFourn,
                ];
            }
        }

        // ── Charger les settings des marques actives ─────────────
        $settingsQuery = GoldaImportSetting::where('active', true);
        if ($marqueFilter) {
            $settingsQuery->where('code_marque', $marqueFilter);
        }
        $settings = $settingsQuery->get()->keyBy('code_marque');

        if ($settings->isEmpty()) {
            $this->warn('⚠️  Aucune marque active trouvée. Lancez d\'abord la synchronisation depuis l\'interface.');
            return 0;
        }

        // ── Boucle principale ────────────────────────────────────
        foreach ($settings as $codeMarque => $setting) {

            // Recherche directe par Nom_Marque
            $fichierInfo = isset($marquesFichiers[strtoupper($codeMarque)])
                ? $marquesFichiers[strtoupper($codeMarque)]
                : null;

            if (!$fichierInfo) {
                $this->warn("⚠️  Pas de fichier FTP pour la marque : {$codeMarque}");
                continue;
            }

            $this->info("➡️  Traitement marque : {$codeMarque} ({$fichierInfo['fichier']})");

            $marqueReport = [
                'marque'      => $codeMarque,
                'imported'    => 0,
                'updated'     => 0,
                'skipped'     => 0,
                'deactivated' => 0,
                'errors'      => [],
            ];

            // ── Brand ────────────────────────────────────────────
            $brand = null;
            if ($setting->brand_id) {
                $brand = Brand::find($setting->brand_id);
            }
            if (!$brand && !$this->dryRun) {
                $brand = Brand::firstOrCreate(
                    ['name' => $setting->nom_marque ?: $codeMarque]
                );
                if (!$setting->brand_id) {
                    $setting->update(['brand_id' => $brand->id]);
                }
            }
            $this->info("  🏷️  Brand '{$codeMarque}' : " .
                ($this->dryRun ? '[DRY]' : 'id ' . ($brand ? $brand->id : '?')));

            // ── Télécharger fichier tarif ─────────────────────────
            try {
                $localFile = storage_path("app/golda/csv/{$fichierInfo['fichier']}");
                Storage::disk('local')->put(
                    "golda/csv/{$fichierInfo['fichier']}",
                    $ftp->get("csv/{$fichierInfo['fichier']}")
                );
                $this->cleanCsvFile($localFile);
                $this->info("  📂 Fichier téléchargé : {$fichierInfo['fichier']}");
            } catch (\Exception $e) {
                $err = "Téléchargement impossible pour {$codeMarque} : " . $e->getMessage();
                $this->error("  ❌ {$err}");
                $marqueReport['errors'][] = $err;
                $report['errors'][]       = $err;
                continue;
            }

            // ── Traiter les articles ─────────────────────────────
            try {
                $localFile = storage_path("app/golda/csv/{$fichierInfo['fichier']}");
                $csvItems  = Reader::createFromPath($localFile, 'r');
                $csvItems->setDelimiter(';');
                $csvItems->setHeaderOffset(0);

                $headers = $csvItems->getHeader();
                $this->info("  📋 En-têtes : " . implode(' | ', array_slice($headers, 0, 10)) . "...");

                foreach (Statement::create()->process($csvItems) as $i) {

                    $ref      = trim($i['Ref_fournisseur'] ?? '');
                    $nameRaw  = trim($i['Description']     ?? '');
                    $price    = floatval(str_replace(',', '.', $i['Prix_euro'] ?? 0));
                    $ean      = trim($i['Code_EAN']        ?? '');

                    // ── Filtre : vérifier que l'article appartient à cette marque ──
                    $codeMarqueArticle = strtoupper(trim($i['Code_marque']   ?? ''));
                    $prefixeArticle    = strtoupper(trim($i['Prefixe_tarif'] ?? ''));

                    if (!$ref || !$nameRaw) continue;

                    // Si Code_marque est renseigné ET ne correspond pas → vérifier le préfixe
                    if ($codeMarqueArticle !== '' && $codeMarqueArticle !== strtoupper($codeMarque)) {
                        if ($prefixeArticle !== strtoupper($fichierInfo['prefixe'])) {
                            $marqueReport['skipped']++;
                            continue;
                        }
                    }

                    $name       = $this->cleanString($nameRaw);
                    $categoryId = trim($i['Code_famille_NU'] ?? '');
                    $refTecDoc  = trim($i['Ref_TecDoc']      ?? '');
                    $codePays   = trim($i['Code_pays']       ?? '');
                    $codeDouane = trim($i['Code_douane']     ?? '');
                    $poids      = $this->parseFloat($i['Poids']    ?? null);
                    $hauteur    = $this->parseFloat($i['Hauteur']   ?? null);
                    $longueur   = $this->parseFloat($i['Longueur']  ?? null);
                    $largeur    = $this->parseFloat($i['Largeur']   ?? null);

                    // ── Calcul marge & prix vente ────────────────
                    $category  = $categoryId ? ItemCategory::find($categoryId) : null;
                    $margin    = $setting->margin_override !== null
                        ? (float) $setting->margin_override
                        : ($category ? (float) $category->default_sale_margin : 30.00);

                    // Prix vente toujours calculé (même si 0)
                    $salePrice = $price > 0
                        ? round($price * (1 + $margin / 100), 2)
                        : 0.00;

                    $item = Item::where('code', $ref)->first();

                    if ($item) {
                        // ── MISE À JOUR ──────────────────────────
                        $updateData = [];

                        if ($setting->update_cost_price && $price > 0) {
                            $updateData['cost_price'] = $price;
                        }
                        if ($setting->update_sale_price && $price > 0) {
                            $updateData['sale_price'] = $salePrice;
                        }
                        if ($setting->update_description && $name) {
                            $updateData['name'] = $name;
                        }
                        if ($setting->update_barcode && $ean) {
                            $updateData['barcode'] = $ean;
                        }
                        if ($setting->update_dimensions) {
                            if ($poids)    $updateData['Poids']    = $poids;
                            if ($hauteur)  $updateData['Hauteur']  = $hauteur;
                            if ($longueur) $updateData['Longueur'] = $longueur;
                            if ($largeur)  $updateData['Largeur']  = $largeur;
                        }
                        // Lier brand si pas encore liée
                        if ($brand && !$item->brand_id) {
                            $updateData['brand_id'] = $brand->id;
                        }
                        // Lier fournisseur si configuré
                        if ($setting->supplier_code && !$item->codefournisseur) {
                            $updateData['codefournisseur'] = $setting->supplier_code;
                        }

                        if (!empty($updateData) && !$this->dryRun) {
                            $item->update($updateData);
                        }
                        $marqueReport['updated']++;

                    } elseif ($setting->create_new_items) {
                        // ── CRÉATION ────────────────────────────
                        if (!$this->dryRun) {
                            Item::create([
                                'code'            => $ref,
                                'codefournisseur' => $setting->supplier_code ?: null,
                                'brand_id'        => $brand ? $brand->id : null,
                                'name'            => $name,
                                'cost_price'      => $price,
                                // sale_price TOUJOURS renseigné à la création (NOT NULL en base)
                                'sale_price'      => $salePrice,
                                'barcode'         => $ean,
                                'Poids'           => $poids,
                                'Hauteur'         => $hauteur,
                                'Longueur'        => $longueur,
                                'Largeur'         => $largeur,
                                'Ref_TecDoc'      => $refTecDoc,
                                'Code_pays'       => $codePays,
                                'Code_douane'     => $codeDouane,
                                'category_id'     => $categoryId ?: null,
                                'is_active'       => true,
                                'unit_id'         => 1,
                                'tva_group_id'    => 1,
                                'store_id'        => 1,
                            ]);
                        }
                        $marqueReport['imported']++;
                    } else {
                        $marqueReport['skipped']++;
                    }
                }

                $this->info("  ✅ {$marqueReport['imported']} créés · {$marqueReport['updated']} mis à jour · {$marqueReport['skipped']} ignorés");
                Log::info("GOLDA [{$codeMarque}]: créés={$marqueReport['imported']}, màj={$marqueReport['updated']}");

            } catch (\Exception $e) {
                $err = "Erreur import {$codeMarque} : " . $e->getMessage();
                Log::error($err);
                $this->error("  ❌ {$err}");
                $marqueReport['errors'][] = $err;
                $report['errors'][]       = $err;
            }

            // ── Désactiver articles supprimés ────────────────────
            if ($setting->deactivate_removed && !empty($fichierInfo['suppr_fichier'])) {
                try {
                    $localSuppr = storage_path("app/golda/csv/{$fichierInfo['suppr_fichier']}");
                    Storage::disk('local')->put(
                        "golda/csv/{$fichierInfo['suppr_fichier']}",
                        $ftp->get("csv/{$fichierInfo['suppr_fichier']}")
                    );
                    $this->cleanCsvFile($localSuppr);

                    $csvSuppr = Reader::createFromPath($localSuppr, 'r');
                    $csvSuppr->setDelimiter(';');
                    $csvSuppr->setHeaderOffset(0);

                    foreach (Statement::create()->process($csvSuppr) as $s) {
                        $refSup = trim($s['Ref_fournisseur'] ?? '');
                        if (!$refSup) continue;
                        if (!$this->dryRun) {
                            $q = Item::where('code', $refSup);
                            if ($brand) {
                                $q->where('brand_id', $brand->id);
                            } elseif ($setting->supplier_code) {
                                $q->where('codefournisseur', $setting->supplier_code);
                            }
                            $q->update(['is_active' => false]);
                        }
                        $marqueReport['deactivated']++;
                    }
                    $this->info("  🗑️  {$marqueReport['deactivated']} articles désactivés");
                } catch (\Exception $e) {
                    $this->warn("  ⚠️  Fichier supprimés absent pour {$codeMarque}");
                }
            }

            // ── Màj stats en base ────────────────────────────────
            if (!$this->dryRun) {
                $setting->update([
                    'last_import_count'      => $marqueReport['imported'] + $marqueReport['updated'],
                    'last_deactivated_count' => $marqueReport['deactivated'],
                    'last_imported_at'       => now(),
                ]);
            }

            $report['marques'][]        = $marqueReport;
            $report['totalImported']    += $marqueReport['imported'] + $marqueReport['updated'];
            $report['totalDeactivated'] += $marqueReport['deactivated'];
        }

        // ── Email rapport ────────────────────────────────────────
        if (!$this->dryRun) {
            try {
                $totalActive = Item::where('is_active', true)->count();
                $message = "Agent d'importation GOLDA — rapport du " . now()->format('d/m/Y à H:i');
                Mail::to(['ahmedarfaoui1600@gmail.com', 'ahmed.arfaoui@premagros.com'])
                    ->send(new GoldaImportReport($report, $totalActive, $message));
                $this->info("📧 Rapport email envoyé");
            } catch (\Exception $e) {
                Log::error("Email GOLDA : " . $e->getMessage());
            }
        }

        $this->info("🎉 Import terminé — {$report['totalImported']} articles traités · {$report['totalDeactivated']} désactivés");
        return 0;
    }

    // ══════════════════════════════════════════════════════════
    // HELPERS
    // ══════════════════════════════════════════════════════════
    private function cleanCsvFile(string $path): void
    {
        $content = file_get_contents($path);
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        $content = mb_convert_encoding(
            $content, 'UTF-8',
            mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true) ?: 'UTF-8'
        );
        file_put_contents($path, $content);
    }

    private function detectDelimiter(string $path): string
    {
        $line = fgets(fopen($path, 'r'));
        if (strpos($line, "\t") !== false) return "\t";
        if (strpos($line, ';')  !== false) return ';';
        return ',';
    }

    private function cleanString(string $str): string
    {
        $str = iconv('UTF-8', 'UTF-8//IGNORE', $str);
        $str = str_replace(["\x92", "\x93", "\x94"], "'", $str);
        $str = str_replace(["\x96", "\x97"], "-", $str);
        return trim($str);
    }

    private function parseFloat($val): ?float
    {
        if ($val === null || $val === '') return null;
        $f = floatval(str_replace(',', '.', $val));
        return $f > 0 ? $f : null;
    }
}