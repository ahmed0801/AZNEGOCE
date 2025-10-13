<?php

namespace App\Http\Controllers;

use Composite\TecDoc\Facades\TecDoc;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TecdocController extends Controller
{


    
  public function search(Request $request)
{
    $reference = $request->input('reference');

    if (!$reference) {
        return view('welcome');
    }

    $filter = [
        'numberType' => 10,
        'searchExact' => true,
    ];

    try {
        $articles = TecDoc::articles()->findByNumber($reference, (array) $filter);

        // Vérifier si la réponse est vide (cas où findByNumber ne lève pas d'exception mais retourne une liste vide)
        if (empty($articles)) {
            $articles = null;
        }
    } catch (\Exception $e) {
        // En cas d'erreur (Empty response ou autre), on considère qu'il n'y a pas de résultats
        $articles = null;
    }
    // dd($articles);


    $response = Http::withHeaders([
        'X-Api-Key' => '2BeBXg6LDMZPdqWdaoq9CP19qGL6bTDHB9qBJEu6K264jC2Yv8wg'
    ])->post('https://webservice.tecalliance.services/pegasus-3-0/services/TecdocToCatDLB.jsonEndpoint', [
        "getLinkageTargets" => [
            "provider" => env('TECDOC_PROVIDER_ID'),
            "linkageTargetCountry" => "TN", 
            "lang" => "fr",
            "linkageTargetType" => "P", 
            "perPage" => 0,
            "page" => 1,
            "includeMfrFacets" => true
        ]
    ]);
    // Décoder la réponse JSON
    $data = $response->json();

    // Vérifier si les marques existent et extraire les marques
    if (isset($data['mfrFacets']['counts'])) {
        $brands = $data['mfrFacets']['counts']; // Liste des marques
    } else {
        $brands = []; // Si pas de marques disponibles
    }




    return view('tecdoc', compact('articles', 'reference','brands'));
}






public function search3(Request $request)
{
    $reference = "KNAB2512ALT701882";

    if (!$reference) {
        return view('welcome');
    }

    $filter = [
        'numberType' => 10,
        'searchExact' => true,
    ];

    try {
        // $articles = TecDoc::articles()->findByNumber($reference, (array) $filter);
        $articles = TecDoc::vehicles()->findByVin($reference);

        // Vérifier si la réponse est vide (cas où findByNumber ne lève pas d'exception mais retourne une liste vide)
        if (empty($articles)) {
            $articles = null;
        }
    } catch (\Exception $e) {
        // En cas d'erreur (Empty response ou autre), on considère qu'il n'y a pas de résultats
        $articles = null;
    }
    dd($articles);

    return view('tecdoc', compact('articles', 'reference'));
}




public function search2(Request $request)
    {
        $vin = "KNAB2512ALT701882";

        if (!$vin) {
            return view('welcome');
        }

        // Paramètres de la requête
        $country = 'FR';  // Remplace par le code pays que tu utilises
        $lang = 'fr';  // Langue de réponse
        $provider = 23454;  // Remplace par ton Provider ID TecDoc

        // URL du service SOAP (remplace par l’URL fournie par TecDoc)
        $wsdl = 'https://webservice.tecalliance.services/pegasus-3-0/services/TecdocToCatDLB.jsonEndpoint';

        try {
            $client = new Client();

            // Construction de la requête SOAP
            $xmlRequest = <<<XML
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tec="http://tecdoc.com/">
                <soapenv:Header/>
                <soapenv:Body>
                    <tec:getVehiclesByVIN>
                        <vin>{$vin}</vin>
                        <country>{$country}</country>
                        <lang>{$lang}</lang>
                        <provider>{$provider}</provider>
                    </tec:getVehiclesByVIN>
                </soapenv:Body>
            </soapenv:Envelope>
            XML;

            // Envoi de la requête
            $response = $client->post($wsdl, [
                'headers' => [
                    'Content-Type' => 'text/xml; charset=utf-8',
                    'SOAPAction' => 'getVehiclesByVIN',
                ],
                'body' => $xmlRequest,
            ]);

            // Récupération et parsing de la réponse XML
            $xml = simplexml_load_string($response->getBody()->getContents());
            $json = json_encode($xml);
            $arrayResponse = json_decode($json, true);

            // Extraire les véhicules
            $vehicles = $arrayResponse['Body']['getVehiclesByVINResponse']['data'] ?? [];
dd($vehicles);
            return view('vehicle', compact('vehicles', 'vin'));

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la récupération des données : ' . $e->getMessage());
        }
    }
    



    public function getVehicleByVin($vin)
    {
        // Récupérer les variables d'environnement
        $apiKey = env('TECDOC_API_KEY', '2BeBXg6LDMZPdqWdaoq9CP19qGL6bTDHB9qBJEu6K264jC2Yv8wg');
        $providerId = env('TECDOC_PROVIDER_ID', 23454);
        $country = env('TECDOC_COUNTRY', 'TN');
        $lang = env('TECDOC_LANG', 'fr');
    
        // URL de l'API
        $url = "https://webservice.tecalliance.services/pegasus-3-0/services/TecdocToCatDLB.jsonEndpoint";
    
        // Corps de la requête
        $payload = [
            "getVehiclesByVIN" => [
                "country" => $country,
                "lang" => $lang,
                "vin" => $vin,
                "provider" => (int) $providerId
            ]
        ];
    
        // Effectuer la requête API avec le bon header
        $response = Http::withHeaders([
            'X-Api-Key' => $apiKey,
            'Content-Type' => 'application/json'
        ])->post($url, $payload);
    
        // Vérifier la réponse et retourner les données
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                'error' => 'Erreur lors de la récupération du véhicule',
                'status' => $response->status(),
                'message' => $response->body()
            ], $response->status());
        }
    }




public function getArticle($articleNumber)
{
    // Récupérer les variables d'environnement
    $apiKey = '2BeBXg6LDMZPdqWdaoq9CP19qGL6bTDHB9qBJEu6K264jC2Yv8wg';
    $providerId = 23454;
    $country = env('TECDOC_COUNTRY', 'TN');
    $lang = env('TECDOC_LANG', 'fr');

    // URL de l'API
    $url = "https://webservice.tecalliance.services/pegasus-3-0/services/TecdocToCatDLB.jsonEndpoint";

    // Corps de la requête
    $payload = [
        "getArticles" => [
            "articleNumber" => $articleNumber,
            "provider" => (int) $providerId,
            "lang" => $lang,
            "articleCountry" => $country
        ]
    ];

    // Effectuer la requête API
    $response = Http::withHeaders([
        'X-Api-Key' => $apiKey
    ])->post($url, $payload);

    // Vérifier la réponse et retourner les données
    if ($response->successful()) {
        return response()->json($response->json());
    } else {
        return response()->json([
            'error' => 'Erreur lors de la récupération de l\'article',
            'status' => $response->status(),
            'body' => $response->body()
        ], 500);
    }
}




























public function getBrands()
{
    $response = Http::withHeaders([
        'X-Api-Key' => '2BeBXg6LDMZPdqWdaoq9CP19qGL6bTDHB9qBJEu6K264jC2Yv8wg'
    ])->post('https://webservice.tecalliance.services/pegasus-3-0/services/TecdocToCatDLB.jsonEndpoint', [
        "getLinkageTargets" => [
            "provider" => env('TECDOC_PROVIDER_ID'),
            "linkageTargetCountry" => "TN", 
            "lang" => "fr",
            "linkageTargetType" => "P", 
            "perPage" => 0,
            "page" => 1,
            "includeMfrFacets" => true
        ]
    ]);
    // Décoder la réponse JSON
    $data = $response->json();
    dd($data['mfrFacets']['counts'][4]);

    // Vérifier si les marques existent
    if (isset($data['mfrFacets']['counts'])) {
        return response()->json($data['mfrFacets']['counts']);
    }

    return response()->json([]);
}




public function getModels(Request $request)
{
         $brandId = $request->input('brand_id');

    // $brandId = '111';

    $response = Http::withHeaders([
        'X-Api-Key' => '2BeBXg6LDMZPdqWdaoq9CP19qGL6bTDHB9qBJEu6K264jC2Yv8wg'
    ])->post('https://webservice.tecalliance.services/pegasus-3-0/services/TecdocToCatDLB.jsonEndpoint', [
        "getLinkageTargets" => [
            "provider" => env('TECDOC_PROVIDER_ID'),
            "linkageTargetCountry" => "TN",
            "lang" => "fr",
            "linkageTargetType" => "P",
            "mfrIds" => $brandId, 
            "perPage" => 0,
            "page" => 1,
            "includeVehicleModelSeriesFacets" => true
        ]
    ]);

    $data = $response->json();
    
    // Si des modèles sont présents dans la réponse, les retourner
    if (isset($data['vehicleModelSeriesFacets']['counts'])) {
        return response()->json($data['vehicleModelSeriesFacets']['counts']);
    }

    // Retourner une liste vide si pas de modèles trouvés
    return response()->json([]);
}




public function getEngines(Request $request)
{
    $modelId = $request->input('model_id');
    
    $response = Http::withHeaders([
        'X-Api-Key' => '2BeBXg6LDMZPdqWdaoq9CP19qGL6bTDHB9qBJEu6K264jC2Yv8wg'
    ])->post('https://webservice.tecalliance.services/pegasus-3-0/services/TecdocToCatDLB.jsonEndpoint', [
        "getLinkageTargets" => [
            "provider" => env('TECDOC_PROVIDER_ID'),
            "linkageTargetCountry" => "TN",
            "lang" => "fr",
            "linkageTargetType" => "P",
            "vehicleModelSeriesIds" => $modelId,
            "perPage" => 100,
            "page" => 1
        ]
    ]);

    // Vérifier si la réponse contient des motorisations
    $engines = [];
    if ($response->successful() && isset($response->json()['linkageTargets'])) {
        // Extraire les motorisations de la réponse
        foreach ($response->json()['linkageTargets'] as $target) {
            $engines[] = [
                'id' => $target['linkageTargetId'],
                'description' => $target['description'],
                'linkageTargetId' => $target['linkageTargetId'] // Ajout du linkageTargetId
            ];
        }
    }

    // Retourner la réponse JSON avec les motorisations extraites
    return response()->json($engines);
}







public function getCategories(Request $request)
{
    $brandId = $request->input('brand_id');
    $modelId = $request->input('model_id');
    $engineId = $request->input('engine_id');

    // Appel à l'API pour récupérer les données
    $response = Http::withHeaders([
        'X-Api-Key' => '2BeBXg6LDMZPdqWdaoq9CP19qGL6bTDHB9qBJEu6K264jC2Yv8wg'
    ])->post('https://webservice.tecalliance.services/pegasus-3-0/services/TecdocToCatDLB.jsonEndpoint', [
        "getArticles" => [
            "articleCountry" => "TN",
            "provider" => env('TECDOC_PROVIDER_ID'),
            "lang" => "fr",
            "perPage" => 0,
            "page" => 1,
            "linkageTargetType" => "P",
            "assemblyGroupFacetOptions" => [
                "enabled" => true,
                "assemblyGroupType" => "P",
                "includeCompleteTree" => true
            ],
            "linkageTargetId" => $modelId,
            "engineId" => $engineId // Ajouter engineId si nécessaire pour l'API
        ]
    ]);

    // Vérification de la réponse et extraction des groupes d'assemblage
    $data = $response->json();
    if ($data['status'] === 200 && isset($data['assemblyGroupFacets']['counts'])) {
        $categories = $data['assemblyGroupFacets']['counts']; // On récupère les catégories de groupes d'assemblage
        return response()->json($categories);
    }

    return response()->json(['message' => 'Aucune catégorie trouvée.'], 404);
}




public function getParts(Request $request)
{
    $categoryId = $request->input('category_id');
    
    $response = Http::withHeaders([
        'X-Api-Key' => '2BeBXg6LDMZPdqWdaoq9CP19qGL6bTDHB9qBJEu6K264jC2Yv8wg'
    ])->post('https://webservice.tecalliance.services/pegasus-3-0/services/TecdocToCatDLB.jsonEndpoint', [
        "getArticles" => [
            "articleCountry" => "TN",
            "provider" => env('TECDOC_PROVIDER_ID'),
            "assemblyGroupNodeIds" => [$categoryId], // Catégorie sous forme de tableau
            "lang" => "fr",
            "perPage" => 100,
            "page" => 1,
            "includeAll" => true
        ]
    ]);

    $data = $response->json();

    // Vérification de la réponse
    if (isset($data['status']) && $data['status'] === 200) {
        $articles = $data['articles']; // Liste des articles retournés
        return view('parts', compact('articles'));
    }

    return response()->json(['message' => 'Aucun article trouvé.'], 404);
}





// preso search

public function persoget(Request $request)
{
    // Affichage de toutes les données envoyées par la requête
    // dd($request->all());

    // Récupération des valeurs des paramètres
    $assemblyGroupNodeId = $request->query('assemblyGroupNodeId');
    $linkingTargetId = $request->query('linkingTargetId');
    // $linkingTargetId = 26582;


    // Création du filtre
    $filter = [
        'numberType' => 10,
        "assemblyGroupNodeId" => $assemblyGroupNodeId,
        "linkingTargetId" => $linkingTargetId,
        "linkingTargetType" => "P",  // Default is P (passenger car)
    ];


    try {
        $articles = TecDoc::articles()->filter((array) $filter);

        // Vérifier si la réponse est vide (cas où findByNumber ne lève pas d'exception mais retourne une liste vide)
        if (empty($articles)) {
            $articles = null;
        }
    } catch (\Exception $e) {
        // En cas d'erreur (Empty response ou autre), on considère qu'il n'y a pas de résultats
        $articles = null;
    }





    // return $articles;
    return view('parts',compact('articles'));
}


    


}
