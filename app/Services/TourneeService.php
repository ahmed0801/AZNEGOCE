<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service à installer sur chacun des 4 sites Laravel aznegoce
 * Compatible PHP 7.4
 */
class TourneeService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.tournee.url', ''), '/');
        $this->apiKey  = config('services.tournee.key', '');
    }

    private function headers()
    {
        return [
            'X-API-KEY'    => $this->apiKey,
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    // ── Ajouter une ligne de tournée ──────────────────────────
    public function addLine(array $data)
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->headers())
                ->post($this->baseUrl . '/api/tournee/lines', $data);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            Log::error('TourneeService::addLine failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return [
                'success' => false,
                'error'   => $response->json('message', 'Erreur serveur tournée'),
            ];

        } catch (\Exception $e) {
            Log::error('TourneeService::addLine exception: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Impossible de contacter le serveur tournée'];
        }
    }

    // ── Supprimer une ligne ───────────────────────────────────
    public function deleteLine($lineId)
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->headers())
                ->delete($this->baseUrl . '/api/tournee/lines/' . $lineId);
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('TourneeService::deleteLine: ' . $e->getMessage());
            return false;
        }
    }

    // ── Lignes d'une facture ──────────────────────────────────
    public function getLinesForInvoice($invoiceId)
    {
        return $this->getLinesForSource($invoiceId, 'facture_vente');
    }

    public function getLinesForSource($sourceId, $sourceType = 'facture_vente')
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->headers())
                ->get($this->baseUrl . '/api/tournee/lines', [
                    'source_id'   => $sourceId,
                    'source_type' => $sourceType,
                ]);
            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            Log::error('TourneeService::getLinesForSource: ' . $e->getMessage());
            return [];
        }
    }

    // ── Liste des chauffeurs ──────────────────────────────────
    public function getChauffeurs()
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->headers())
                ->get($this->baseUrl . '/api/tournee/chauffeurs');
            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            Log::error('TourneeService::getChauffeurs: ' . $e->getMessage());
            return [];
        }
    }

    // ── Paramètres tournée du jour ───────────────────────────
    // Retourne les créneaux disponibles, le créneau suggéré
    // et si le site est ouvert ce jour
    public function getParametres(string $date = null)
    {
        try {
            $params = [];
            if ($date) {
                $params['date'] = $date;
            }

            $response = Http::timeout(5)
                ->withHeaders($this->headers())
                ->get($this->baseUrl . '/api/tournee/parametres', $params);

            if ($response->successful()) {
                return $response->json();
            }

            // Si l'API ne répond pas, retourner des paramètres par défaut
            return $this->defaultParametres();

        } catch (\Exception $e) {
            Log::warning('TourneeService::getParametres: ' . $e->getMessage());
            return $this->defaultParametres();
        }
    }

    // Paramètres par défaut si le hub est inaccessible
    private function defaultParametres(): array
    {
        return [
            'is_open'         => true,
            'message'         => null,
            'creneaux'        => [
                ['label' => '9h-11h',  'debut' => '09:00', 'fin' => '11:00'],
                ['label' => '11h-12h', 'debut' => '11:00', 'fin' => '12:00'],
                ['label' => '13h-14h', 'debut' => '13:00', 'fin' => '14:00'],
                ['label' => '15h-16h', 'debut' => '15:00', 'fin' => '16:00'],
                ['label' => '17h-18h', 'debut' => '17:00', 'fin' => '18:00'],
            ],
            'creneaux_dispo'  => [],
            'creneau_suggere' => '9h-11h',
        ];
    }

    // ── Synchroniser les fournisseurs ─────────────────────────
    public function syncFournisseurs(array $fournisseurs)
    {
        try {
            $response = Http::timeout(15)
                ->withHeaders($this->headers())
                ->post($this->baseUrl . '/api/tournee/fournisseurs/sync', [
                    'fournisseurs' => $fournisseurs,
                ]);
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('TourneeService::syncFournisseurs: ' . $e->getMessage());
            return false;
        }
    }
}