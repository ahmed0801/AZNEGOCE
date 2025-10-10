<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Supplier;
use App\Models\Item;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\Log;

class ImportGoldaTarifs extends Command
{
    protected $signature = 'golda:import';
    protected $description = 'Importe les tarifs GOLDA depuis le FTP et met à jour les fournisseurs et articles';

    public function handle()
    {
        $this->info("🚀 Début de l’import GOLDA...");

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

        // --- Nettoyage du BOM éventuel ---
        $content = file_get_contents($localInfoFile);
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
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

            // --- Créer ou récupérer le fournisseur ---
            $supplier = Supplier::firstOrCreate(
                ['name' => $fournisseurName],
                ['code' => $prefixe]
            );

            $this->info("➡️ Traitement fournisseur : {$fournisseurName} ({$prefixe})");

            $count = 0;

            // --- Import des articles ---
           // --- Téléchargement du fichier CSV articles ---
if ($fichierTarif) {
    try {
        $remoteFile = "csv/{$fichierTarif}";
        $localFile = storage_path("app/golda/csv/{$fichierTarif}");

        Storage::disk('local')->put("golda/csv/{$fichierTarif}", $ftp->get($remoteFile));
        $this->info("📂 Fichier articles téléchargé : {$fichierTarif}");

        $csvItems = Reader::createFromPath($localFile, 'r');
        $csvItems->setDelimiter(';'); // <-- Séparateur correct
        $csvItems->setHeaderOffset(0);

        // Nettoyage des entêtes
        $headers = $csvItems->getHeader();
        $cleanHeaders = array_map(fn($h) => trim(preg_replace('/[[:^print:]]/', '', $h)), $headers);
        $this->info('🧾 Colonnes articles : ' . implode(' | ', $cleanHeaders));

        $recordsItems = Statement::create()->process($csvItems);
        $count = 0;

        foreach ($recordsItems as $i) {
            $ref = trim($i['Ref_fournisseur'] ?? '');
            $name = trim($i['Description'] ?? '');
            $price = floatval(str_replace(',', '.', $i['Prix_euro'] ?? 0));
            $ean = trim($i['Code_EAN'] ?? '');

            if (!$ref || !$name) continue;

            // Création ou mise à jour de l'article
            $item = Item::updateOrCreate(
                ['code' => $ref, 'codefournisseur' => $supplier->code],
                [
                    'name' => $name,
                    'cost_price' => $price,
                    'barcode' => $ean,
                    'is_active' => true
                ]
            );
            $count++;
        }

        $this->info("✅ {$count} articles importés pour {$supplier->name}");
        Log::info("GOLDA: {$count} articles importés pour {$supplier->name}");

    } catch (\Exception $e) {
        Log::error("Erreur import articles pour {$supplier->name}: {$e->getMessage()}");
        $this->error("❌ Erreur import articles pour {$supplier->name}: {$e->getMessage()}");
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

                    $countSuppr = 0;
                    foreach ($supprList as $s) {
                        $refSup = trim($s['Ref_fournisseur'] ?? '');
                        if (!$refSup) continue;

                        Item::where('code', $refSup)
                            ->where('codefournisseur', $supplier->code)
                            ->update(['is_active' => false]);
                        $countSuppr++;
                    }

                    $this->info("🗑️ {$countSuppr} articles désactivés pour {$supplier->name}");
                    Log::info("GOLDA: {$countSuppr} articles désactivés pour {$supplier->name}");

                } catch (\Exception $e) {
                    Log::warning("Fichier supprimés introuvable pour {$supplier->name}");
                }
            }
        }

        $this->info('🎉 Import GOLDA terminé avec succès.');
    }
}
