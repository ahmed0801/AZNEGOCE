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
        
     
            $messageText = "Hello, je suis un robot développé et programmé par votre développeur Ahmed pour que je tourne chaque soirée et j'intègre automatiquement toutes nouveaux articles dans GOLDA et les mises à jour des prix pour chaque fournisseur. Ahmed m'a programmé aussi pour vous envoyer ce rapport complet et détaillant du dernier résultat de l'importation.";
            Mail::to(['ahmedarfaoui1600@gmail.com', 'ahmed.arfaoui@premagros.com'])->send(new GoldaImportReport($report, $totalActiveItems, $messageText));
            $this->info("📧 Rapport envoyé par email à ahmedarfaoui@gmail.com et abidi.mourad@orange.fr");


        $this->info('🎉 Import GOLDA terminé avec succès.');
    }
}