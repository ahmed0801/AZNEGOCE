<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Supplier;
use App\Models\Item;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\GoldaImportReport;
use App\Models\ItemCategory;

class ImportGoldaTarifs extends Command
{
    protected $signature = 'golda:import';
    protected $description = 'Importe les tarifs GOLDA depuis le FTP et met à jour les fournisseurs et articles';

    public function handle()
    {
        $this->info("🚀 Début de l’import GOLDA...");
        
        // Initialize report data
        $report = [
            'suppliers' => [],
            'totalItems' => 0,
            'errors' => []
        ];

        // --- Connexion FTP ---
        $ftp = Storage::createFtpDriver([
            'host' => 'golda.fr',
            'username' => 'tdlfg8223',
            'password' => 'B7w42bz!36',
            'root' => '/tarifs/',
            'passive' => true,
            'ssl' => false,
        ]);

        Storage::makeDirectory('golda');

        // --- Télécharger infos_tarifs.csv ---
        $localInfoFile = storage_path('app/golda/infos_tarifs.csv');
        Storage::disk('local')->put('golda/infos_tarifs.csv', $ftp->get('infos_tarifs.csv'));

        // --- Nettoyage du BOM et conversion UTF-8 ---
        $content = file_get_contents($localInfoFile);
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
       $content = mb_convert_encoding($content, 'UTF-8', mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true) ?: 'UTF-8');

        file_put_contents($localInfoFile, $content);

        // --- Détection du délimiteur ---
        $firstLine = fgets(fopen($localInfoFile, 'r'));
        if (strpos($firstLine, "\t") !== false) {
            $delimiter = "\t";
        } elseif (strpos($firstLine, ';') !== false) {
            $delimiter = ';';
        } else {
            $delimiter = ',';
        }

        // --- Lecture du CSV ---
        $csv = Reader::createFromPath($localInfoFile, 'r');
        $csv->setDelimiter($delimiter);
        $csv->setHeaderOffset(0);
        $records = Statement::create()->process($csv);

        $this->info('📋 En-têtes détectées : ' . implode(' | ', $csv->getHeader()));

        // --- Boucle principale : fournisseurs ---
        foreach ($records as $row) {

            // eliminer des fournisseurs
            // $fournisseurName = trim($row['Nom_Fournisseur'] ?? '');
 $fournisseurName = trim($row['Nom_Fournisseur'] ?? '');

    // 🚫 Liste des fournisseurs à ignorer
    $fournisseursIgnorer = [
        'C-CARPARTS',
        'DRiV',
        'ELECTRICFIL SERVICE',
        'FRANCELEC',
        'IRONTEK',
        'LRT AUTOMOTIVE GmbH',
        'EXADIS',
        
    ];

    // Si le fournisseur est dans la liste noire, on saute totalement son traitement
    if (in_array(strtoupper($fournisseurName), array_map('strtoupper', $fournisseursIgnorer))) {
        $this->info("⏩ Fournisseur ignoré : {$fournisseurName}");
        continue;
    }



            $prefixe = trim($row['Prefixe_Tarif'] ?? '');
            $fichierTarif = trim($row['Nom_Fichier_CSV'] ?? '');
            $fichierSupprimes = trim($row['Nom_Fichier_Articles_Supprimes'] ?? '');

            if (empty($fournisseurName)) continue;

// 🔍 Vérifier fournisseur par CODE, jamais par nom
$supplier = Supplier::where('code', $prefixe)->first();

if (!$supplier) {
    $supplier = Supplier::create([
        'code' => $prefixe,
        'name' => $fournisseurName
    ]);
}


            $this->info("➡️ Traitement fournisseur : {$fournisseurName} ({$prefixe})");
            $supplierReport = [
                'name' => $fournisseurName,
                'imported' => 0,
                'deactivated' => 0,
                'errors' => []
            ];

            // --- Import des articles ---
            if ($fichierTarif) {
                try {
                    $remoteFile = "csv/{$fichierTarif}";
                    $localFile = storage_path("app/golda/csv/{$fichierTarif}");

                    Storage::disk('local')->put("golda/csv/{$fichierTarif}", $ftp->get($remoteFile));
                    $this->info("📂 Fichier articles téléchargé : {$fichierTarif}");

                    $csvItems = Reader::createFromPath($localFile, 'r');
                    $csvItems->setDelimiter(';');
                    $csvItems->setHeaderOffset(0);

                    $recordsItems = Statement::create()->process($csvItems);

                    foreach ($recordsItems as $i) {
                        $ref = trim($i['Ref_fournisseur'] ?? '');
                        $name = trim($i['Description'] ?? '');
                        $price = floatval(str_replace(',', '.', $i['Prix_euro'] ?? 0));
                        $ean = trim($i['Code_EAN'] ?? '');

                        $poids = isset($i['Poids']) ? floatval(str_replace(',', '.', $i['Poids'])) : null;
$hauteur = isset($i['Hauteur']) ? floatval(str_replace(',', '.', $i['Hauteur'])) : null;
$longueur = isset($i['Longueur']) ? floatval(str_replace(',', '.', $i['Longueur'])) : null;
$largeur = isset($i['Largeur']) ? floatval(str_replace(',', '.', $i['Largeur'])) : null;
$refTecDoc = trim($i['Ref_TecDoc'] ?? '');
$codePays = trim($i['Code_pays'] ?? '');
$codeDouane = trim($i['Code_douane'] ?? '');
$category_id = trim($i['Code_famille_NU'] ?? '');



                        if (!$ref || !$name) continue;

                        // Nettoyage UTF-8 + caractères spéciaux
                        $name = iconv('UTF-8', 'UTF-8//IGNORE', $name);
                        $name = str_replace(["\x92", "\x93", "\x94"], "'", $name);
                        $name = str_replace(["\x96", "\x97"], "-", $name);

                        // Création ou mise à jour de l'article
                        $item = Item::where('code', $ref)->first();



                        $category = $category_id ? ItemCategory::find($category_id) : null;
$margin = $category ? $category->default_sale_margin : 30.00;
$sale_price = round($price * (1 + $margin / 100), 2);


                        if ($item) {
                            $item->update([
    // 'codefournisseur' => $supplier->code,
    // 'name' => $name,
    'cost_price' => $price,

    // ca c'est pour lamarge par famille
    // 'sale_price' => $sale_price, 


    // 'barcode' => $ean,
    // 'Poids' => $poids,
    // 'Hauteur' => $hauteur,
    // 'Longueur' => $longueur,
    // 'Largeur' => $largeur,
    // 'Ref_TecDoc' => $refTecDoc,
    // 'Code_pays' => $codePays,
    // 'Code_douane' => $codeDouane,
    // 'category_id' => $category_id,
    // 'is_active' => true,
    // 'unit_id' => 1,
    // 'tva_group_id' => 1,
    // 'store_id' => 1
                            ]);
                        } else {
                            Item::create([
    'code' => $ref,
    'codefournisseur' => $supplier->code,
    'name' => $name,
    'cost_price' => $price,
    'barcode' => $ean,
    'Poids' => $poids,
    'Hauteur' => $hauteur,
    'Longueur' => $longueur,
    'Largeur' => $largeur,
    'Ref_TecDoc' => $refTecDoc,
    'Code_pays' => $codePays,
    'Code_douane' => $codeDouane,
    'category_id' => $category_id,
    'is_active' => true,
    'unit_id' => 1,
    'tva_group_id' => 1,
    'store_id' => 1
                                
                            ]);
                        }

                        $supplierReport['imported']++;
                        $report['totalItems']++;
                    }

                    $this->info("✅ {$supplierReport['imported']} articles importés pour {$supplier->name}");
                    Log::info("GOLDA: {$supplierReport['imported']} articles importés pour {$supplier->name}");

                } catch (\Exception $e) {
                    $errorMsg = "Erreur import articles pour {$supplier->name}: {$e->getMessage()}";
                    Log::error($errorMsg);
                    $this->error("❌ {$errorMsg}");
                    $supplierReport['errors'][] = $errorMsg;
                    $report['errors'][] = $errorMsg;
                }
            }

            // --- Désactiver les articles supprimés ---
            if ($fichierSupprimes) {
                try {
                    $remoteSuppr = "csv/{$fichierSupprimes}";
                    $localSuppr = storage_path("app/golda/csv/{$fichierSupprimes}");
                    Storage::disk('local')->put("golda/csv/{$fichierSupprimes}", $ftp->get($remoteSuppr));

                    $csvSuppr = Reader::createFromPath($localSuppr, 'r');
                    $csvSuppr->setDelimiter("\t");
                    $csvSuppr->setHeaderOffset(0);
                    $supprList = Statement::create()->process($csvSuppr);

                    foreach ($supprList as $s) {
                        $refSup = trim($s['Ref_fournisseur'] ?? '');
                        if (!$refSup) continue;

                        Item::where('code', $refSup)
                            ->where('codefournisseur', $supplier->code)
                            ->update(['is_active' => false]);
                        $supplierReport['deactivated']++;
                    }

                    $this->info("🗑️ {$supplierReport['deactivated']} articles désactivés pour {$supplier->name}");
                    Log::info("GOLDA: {$supplierReport['deactivated']} articles désactivés pour {$supplier->name}");

                } catch (\Exception $e) {
                    $errorMsg = "Fichier supprimés introuvable pour {$supplier->name}: {$e->getMessage()}";
                    Log::warning($errorMsg);
                    $supplierReport['errors'][] = $errorMsg;
                    $report['errors'][] = $errorMsg;
                }
            }

            $report['suppliers'][] = $supplierReport;
        }

        // --- Calculate total active items in stock ---
        $totalActiveItems = Item::where('is_active', true)->count();

// --- Send email report ---
        try {
            $messageText = "Hello, je suis un agent développé et programmé par votre développeur ERP pour que je tourne chaque soirée et j'intègre automatiquement toutes nouveaux articles dans GOLDA et les mises à jour des prix pour chaque fournisseur. Ahmed m'a programmé aussi pour vous envoyer ce rapport complet et détaillant du dernier résultat de l'importation.";
            Mail::to(['ahmedarfaoui1600@gmail.com', 'ahmed.arfaoui@premagros.com'])->send(new GoldaImportReport($report, $totalActiveItems, $messageText));
            $this->info("📧 Rapport envoyé par email à ahmedarfaoui@gmail.com et abidi.mourad@orange.fr");
        } catch (\Exception $e) {
            $errorMsg = "Erreur lors de l'envoi de l'email: {$e->getMessage()}";
            Log::error($errorMsg);
            $this->error("❌ {$errorMsg}");
            throw new \Exception($errorMsg); // Rethrow to make the error visible in console
        }

        $this->info('🎉 Import GOLDA terminé avec succès.');
    }
}