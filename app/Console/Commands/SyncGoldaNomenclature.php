<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoldaNomenclature;

class SyncGoldaNomenclature extends Command
{
    protected $signature = 'golda:nomenclature';
    protected $description = 'Charge familles et sous-familles de la nomenclature unique GOLDA';

    public function handle()
    {
        list($f, $s, $r) = GoldaNomenclature::syncAll();
        $this->info("Familles créées : {$f} · sous-familles créées : {$s} · renommées : {$r}");
        return 0;
    }
}
