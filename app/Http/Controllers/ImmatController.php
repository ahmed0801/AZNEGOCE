<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Composite\TecDoc\Facades\TecDoc; // si tu veux utiliser le package, mais ici j'utilise direct HTTP

class ImmatController extends Controller
{

    public function index()
    {
        return view('vehicles.newcat'); // la blade fournie plus bas
    }

    /**
     * Reçoit la plaque, récupère le VIN via l'API plaque,
     * puis interroge TecDoc pour obtenir les véhicules et articles.
     */
    public function fetchByPlate(Request $request)
{
    $request->validate([
        'plate' => 'required|string'
    ]);

    $plate = $request->input('plate');

    // --- 1) Appel API plaque pour récupérer VIN et infos de base ---
    $plateApiUrl = 'https://api.apiplaqueimmatriculation.com/get-vehicule-info';
    $plateToken  = 'TokenDemo2025A';
    $country     = 'FR';

    // Appel GET direct vers l'API plaque
    $plateResp = Http::get($plateApiUrl, [
        'immatriculation' => $plate,
        'token' => $plateToken,
        'pays' => $country
    ]);

    if (!$plateResp->successful()) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la requête vers l\'API plaque',
            'body' => $plateResp->body()
        ], 500);
    }

    $plateData = $plateResp->json('data', null);
    if (!$plateData || empty($plateData['vin'])) {
        return response()->json([
            'success' => false,
            'message' => 'VIN introuvable pour cette plaque',
            'api_response' => $plateResp->json()
        ], 404);
    }

    $vin = $plateData['vin'];

    // --- 2) Appel TecDoc pour récupérer le véhicule et les articles ---
    // Exemple : tu peux remplacer ces constantes par tes vraies infos TecDoc



$tecdocUrl = 'https://webservice.tecalliance.services/pegasus-3-0/services/TecdocToCatDLB.jsonEndpoint';  
    $apiKey = env('TECDOC_API_KEY', '2BeBXg6LDMZPdqWdaoq9CP19qGL6bTDHB9qBJEu6K264jC2Yv8wg');
    $provider = env('TECDOC_PROVIDER_ID', 23454);
    $country = env('TECDOC_COUNTRY', 'TN');
    $lang = env('TECDOC_LANG', 'fr');

    $payloadVehicle = [
    "getVehiclesByVIN" => [
        "country"  => $country,
        "lang"     => $lang,
        "vin"      => $vin,
        "provider" => $provider
    ]
];

$tecdocResp = Http::withHeaders([
    'X-Api-Key' => $apiKey,
    'Content-Type' => 'application/json',
    'Accept' => 'application/json'
])->post($tecdocUrl, $payloadVehicle);

// Debug si ça casse
if (!$tecdocResp->successful()) {
    dd([
        'status' => $tecdocResp->status(),
        'body' => $tecdocResp->body(),
        'payload' => $payloadVehicle
    ]);
}

    $tecdocJson = $tecdocResp->json();
    $linkageTargets = $tecdocJson['linkageTargets'] ?? $tecdocJson['data'] ?? null;

    if (empty($linkageTargets)) {
        return response()->json([
            'success' => false,
            'message' => 'Aucun véhicule trouvé dans TecDoc pour ce VIN',
            'tecdoc' => $tecdocJson
        ], 404);
    }

    // Récupérer le linkageTargetId
    $linkageTargetId = null;
    foreach ($linkageTargets as $t) {
        if (isset($t['linkageTargetId'])) {
            $linkageTargetId = $t['linkageTargetId'];
            break;
        }
        if (isset($t['id'])) {
            $linkageTargetId = $t['id'];
            break;
        }
    }

    if (!$linkageTargetId) {
        return response()->json([
            'success' => false,
            'message' => 'Impossible d\'extraire linkageTargetId depuis TecDoc',
            'tecdoc' => $tecdocJson
        ], 500);
    }

    // B) On demande les articles du véhicule
    $payloadArticles = [
        "getArticles" => [
            "articleCountry" => $country,
            "provider" => $provider,
            "lang" => $lang,
            "perPage" => 50,
            "page" => 1,
            "linkageTargetType" => "P",
            "linkageTargetId" => $linkageTargetId,
            "includeAll" => true
        ]
    ];

    $articlesResp = Http::withHeaders([
        'X-Api-Key' => $apiKey,
        'Content-Type' => 'application/json'
    ])->post($tecdocUrl, $payloadArticles);

    if (!$articlesResp->successful()) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur TecDoc (getArticles)',
            'body' => $articlesResp->body()
        ], 500);
    }

    $articlesJson = $articlesResp->json();
    $articles = $articlesJson['articles'] ?? $articlesJson['data']['articles'] ?? [];

    return response()->json([
        'success' => true,
        'plate_info' => $plateData,
        'vin' => $vin,
        'tecdoc_vehicle' => $linkageTargets,
        'linkageTargetId' => $linkageTargetId,
        'articles_count' => is_array($articles) ? count($articles) : 0,
        'articles' => $articles
    ]);
}

}
