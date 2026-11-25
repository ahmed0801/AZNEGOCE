<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Identification d'un véhicule par plaque d'immatriculation, comme la page « nouvelle commande » de l'ERP,
 * mais l'appel à l'API est fait par le SERVEUR : la clé (.env : PLATE_API_TOKEN) n'est jamais envoyée au navigateur.
 * Résultat mis en cache 30 jours par plaque (économise les crédits de l'API).
 */
class PlateLookup
{
    public static function normalize($plate)
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) $plate));
    }

    /** Plaque FR/SIV (AB123CD), FNI ancienne (1234ABC / 123ABC45…) ou étrangère : on accepte 4 à 12 caractères alphanumériques. */
    public static function valid($plate)
    {
        $n = self::normalize($plate);
        return strlen($n) >= 4 && strlen($n) <= 12;
    }

    /** Affichage AB-123-CD pour une plaque SIV, sinon tel quel. */
    public static function pretty($plate)
    {
        $n = self::normalize($plate);
        return preg_match('/^([A-Z]{2})(\d{3})([A-Z]{2})$/', $n, $m) ? $m[1] . '-' . $m[2] . '-' . $m[3] : $n;
    }

    /** Clé lue dans config/services.php (fonctionne aussi avec « php artisan config:cache »), à défaut directement dans .env. */
    private static function token()
    {
        $t = config('services.plate.token');
        return (string) ($t ?: env('PLATE_API_TOKEN', ''));
    }

    public static function enabled()
    {
        return self::token() !== '';
    }

    /** @return array|null  ['plate','brand','model','engine','label'] ; null si l'API ne connaît pas la plaque ou est indisponible. */
    public static function lookup($plate)
    {
        $n = self::normalize($plate);
        if (!self::valid($n) || !self::enabled()) return null;
        $key = 'plate_lookup_' . $n;
        $hit = Cache::get($key);
        if (is_array($hit)) return $hit['found'] ? $hit['data'] : null;
        try {
            $resp = Http::timeout(6)->acceptJson()->get('https://api.apiplaqueimmatriculation.com/plaque', ['immatriculation' => $n, 'token' => self::token(), 'pays' => 'FR']);
            $d = $resp->successful() ? $resp->json('data') : null;
        } catch (\Throwable $e) {
            Log::channel('single')->warning('plate lookup failed: ' . $e->getMessage());
            return null;                                           // panne temporaire : pas de cache
        }
        if (!is_array($d) || !empty($d['erreur']) || empty($d['marque'])) { Cache::put($key, ['found' => false], now()->addHours(6)); return null; }
        $brand = trim((string) $d['marque']);
        $model = trim((string) ($d['modele'] ?? ''));
        $engine = trim((string) (!empty($d['sra_commercial']) ? $d['sra_commercial'] : (isset($d['code_moteur']) ? $d['code_moteur'] : '')));
        $vin = isset($d['vin']) ? self::normalize($d['vin']) : '';
        $data = ['plate' => self::pretty($n), 'brand' => mb_substr($brand, 0, 60), 'model' => mb_substr($model, 0, 80), 'engine' => mb_substr($engine, 0, 120), 'vin' => strlen($vin) === 17 ? $vin : null];
        $data['label'] = trim(implode(' ', array_filter([$data['brand'], $data['model'], $data['engine']])));
        Cache::put($key, ['found' => true, 'data' => $data], now()->addDays(30));
        return $data;
    }

    /**
     * Véhicule de CE client pour cette plaque : celui qui existe déjà, sinon création (comme « from-plate » de l'ERP).
     * Ne lève jamais d'exception ; null si impossible (le devis garde alors le texte).
     */
    public static function vehicleFor(Customer $customer, $plate, array $data = null)
    {
        $n = self::normalize($plate);
        try {
            $existing = $customer->vehicles()->whereRaw("REPLACE(REPLACE(UPPER(license_plate), '-', ''), ' ', '') = ?", [$n])->first();
            if ($existing) return $existing;
            if (!$data) return null;
            return $customer->vehicles()->create([
                'license_plate' => $data['plate'], 'brand_name' => $data['brand'], 'model_name' => $data['model'], 'engine_description' => $data['engine'],
            ]);
        } catch (\Throwable $e) {
            Log::channel('single')->warning('plate vehicle create failed: ' . $e->getMessage());
            return null;
        }
    }
}
