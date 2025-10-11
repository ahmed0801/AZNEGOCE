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

        // --- Fonction utilitaire pour convertir en UTF-8 ---
        $toUTF8 = function($string) {
            $encoding = mb_detect_encoding($string, ['UTF-8', 'ISO-8859-1', 'WINDOWS-1252'], true) ?: 'ISO-8859-1';
            return mb_convert_encoding($string, 'UTF-8', $encoding);
        };

        // --- Connexion FTP ---
        $ftp = Storage::createFtpDriver([
            'host' => 'golda.fr',
            'username' => 'tdlfg8223',
            'password' => '1aX&&5fxCYHz',
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
        $content = $toUTF8($content);
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
            $fournisseurName = trim($row['Nom_Fournisseur'] ?? '');
            $prefixe = trim($row['Prefixe_Tarif'] ?? '');
            $fichierTarif = trim($row['Nom_Fichier_CSV'] ?? '');
            $fichierSupprimes = trim($row['Nom_Fichier_Articles_Supprimes'] ?? '');

            if (empty($fournisseurName)) continue;

            $supplier = Supplier::firstOrCreate(
                ['name' => $fournisseurName],
                ['code' => $prefixe]
            );

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

                        if (!$ref || !$name) continue;

                        // Conversion UTF-8
                        $name = $toUTF8($name);

                        // Nettoyage caractères spéciaux
                        $name = str_replace(["\x92", "\x93", "\x94"], "'", $name);
                        $name = str_replace(["\x96", "\x97"], "-", $name);

                        // Création ou mise à jour de l'article
                        $item = Item::where('code', $ref)->first();
                        if ($item) {
                            $item->update([
                                'codefournisseur' => $supplier->code,
                                'name' => $name,
                                'cost_price' => $price,
                                'barcode' => $ean,
                                'is_active' => true
                            ]);
                        } else {
                            Item::create([
                                'code' => $ref,
                                'codefournisseur' => $supplier->code,
                                'name' => $name,
                                'cost_price' => $price,
                                'barcode' => $ean,
                                'is_active' => true
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
            $messageText = "Hello, je suis un robot développé et programmé par votre développeur Ahmed pour que je tourne chaque soirée et j'intègre automatiquement toutes nouveaux articles dans GOLDA et les mises à jour des prix pour chaque fournisseur. Ahmed m'a programmé aussi pour vous envoyer ce rapport complet et détaillant du dernier résultat de l'importation.";
            Mail::to(['ahmedarfaoui1600@gmail.com', 'abidi.mourad@orange.fr'])
                ->send(new GoldaImportReport($report, $totalActiveItems, $messageText));
            $this->info("📧 Rapport envoyé par email à ahmedarfaoui@gmail.com et abidi.mourad@orange.fr");
        } catch (\Exception $e) {
            $errorMsg = "Erreur lors de l'envoi de l'email: {$e->getMessage()}";
            Log::error($errorMsg);
            $this->error("❌ {$errorMsg}");
            throw new \Exception($errorMsg);
        }

        $this->info('🎉 Import GOLDA terminé avec succès.');
    }
}
