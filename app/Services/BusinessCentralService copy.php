<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;


class BusinessCentralService
{
    private $wsdl;
    private $url;
    private $username;
    private $password;

    public function __construct()
    {
        $this->wsdl = storage_path('app/B2BWS.wsdl');
        $this->url = 'http://192.168.1.16:7047/BC260/WS/TPG/Codeunit/B2BWS';
        $this->username = 'ahmed';
        $this->password = 'AZER1234azer$';
    }

    public function getItems($itemFilter)
    {
        // Corps de la requête SOAP
        $request = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:GetItems>
         <b2b:itemFilter>{$itemFilter}</b2b:itemFilter>
         <b2b:vARJson></b2b:vARJson>
      </b2b:GetItems>
   </soapenv:Body>
</soapenv:Envelope>
XML;

        // Initialisation de cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM); // Authentification NTLM
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request); // Utilisation du corps de la requête SOAP
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/xml; charset=utf-8', 'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:GetItems']); // Headers SOAP

        // Exécution de la requête
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
            return null; // En cas d'erreur, renvoyer null
        }

        curl_close($ch);

        // Traitement de la réponse SOAP
        if ($response) {
            $xml = simplexml_load_string($response);
            $namespaces = $xml->getNamespaces(true);
            $body = $xml->children($namespaces['Soap'])->Body;

            // Chercher les éléments sans dépendre du namespace
            $result = $body->children()->GetItems_Result;

            if (isset($result->vARJson)) {
                $json = json_decode((string)$result->vARJson, true);
                return $json;
            }
        }

        return null; // En cas de réponse vide ou mal formée, renvoyer null
    }








    public function getItemsByGroup($articlesJson) 
{
    // Corps de la requête SOAP
    $request = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:GetItemsByGroup>
         <b2b:vARJsonInput>{$articlesJson}</b2b:vARJsonInput>
         <b2b:vARJson></b2b:vARJson>
      </b2b:GetItemsByGroup>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    // Initialisation de cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: text/xml; charset=utf-8', 
        'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:GetItemsByGroup'
    ]);

    // Exécution de la requête
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        return null;
    }

    curl_close($ch);

    // Traitement de la réponse SOAP
    if ($response) {
        $xml = simplexml_load_string($response);
        $namespaces = $xml->getNamespaces(true);
        $body = $xml->children($namespaces['Soap'])->Body;

        // Extraction du résultat
        $result = $body->children()->GetItemsByGroup_Result;

        if (isset($result->vARJson)) {
            return json_decode((string) $result->vARJson, true);
        }
    }

    return null; // En cas de réponse vide ou mal formée
}







public function searchByRefOriginGroup($articlesJson) 
{
    $request = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:RechercheParRefOriginGroup2>
         <b2b:vARJsonInput>{$articlesJson}</b2b:vARJsonInput>
         <b2b:varJSON></b2b:varJSON>
      </b2b:RechercheParRefOriginGroup2>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: text/xml; charset=utf-8', 
        'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:RechercheParRefOriginGroup2'
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        return null;
    }
    curl_close($ch);

    if ($response) {
        $xml = simplexml_load_string($response);
        $namespaces = $xml->getNamespaces(true);
        
        // Vérification et sélection du bon namespace SOAP
        $soapNamespace = $namespaces['soapenv'] ?? $namespaces['Soap'] ?? $namespaces['s'] ?? null;
        if (!$soapNamespace) {
            return null; // Namespace SOAP introuvable
        }
        $body = $xml->children($soapNamespace)->Body;
        $result = $body->children()->RechercheParRefOriginGroup2_Result;

        if (isset($result->varJSON)) {
            return json_decode((string) $result->varJSON, true);
        }
    }

    return null;
}








    


public function getItemsByDescription($description)
{
    $request = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:GetItemsByDesc>
         <b2b:description>{$description}</b2b:description>
         <b2b:vARJson></b2b:vARJson>
      </b2b:GetItemsByDesc>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/xml; charset=utf-8', 'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:GetItemsByDesc']);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return null; 
    }

    curl_close($ch);

    $xml = simplexml_load_string($response);
    $namespaces = $xml->getNamespaces(true);
    $body = $xml->children($namespaces['Soap'])->Body;

    $result = $body->children()->GetItemsByDesc_Result;

    if (isset($result->vARJson)) {
        $json = json_decode((string)$result->vARJson, true);
        return $json;
    }

    return null;
}





public function rechercheParRefOrigin2($refOrigin)
{
    // Corps de la requête SOAP
    $request = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:RechercheParRefOrigin2>
         <b2b:refOrigin>{$refOrigin}</b2b:refOrigin>
         <b2b:varJSON></b2b:varJSON>
      </b2b:RechercheParRefOrigin2>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    // Initialisation de cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/xml; charset=utf-8', 'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:RechercheParRefOrigin2']);

    // Exécution de la requête
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return null; // Retourne null en cas d'erreur
    }

    curl_close($ch);

    // Parse de la réponse SOAP
    $xml = simplexml_load_string($response);
    $namespaces = $xml->getNamespaces(true);
    $body = $xml->children($namespaces['Soap'])->Body;

    $result = $body->children()->RechercheParRefOrigin2_Result;

    if (isset($result->varJSON)) {
        $json = json_decode((string)$result->varJSON, true);
        return $json;
    }

    return null; // Retourner null si la réponse est vide ou mal formée
}








public function getOrdersBySeller()
{
    $sellerCode = Auth::user()->codevendeur;

    // Corps de la requête SOAP
    $request = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:GetOrdersBySeller>
         <b2b:vARJson></b2b:vARJson>
         <b2b:codeVendeur>{$sellerCode}</b2b:codeVendeur>
      </b2b:GetOrdersBySeller>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    // Initialisation de cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: text/xml; charset=utf-8',
        'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:GetOrdersBySeller'
    ]);

    // Exécution de la requête
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return null; // En cas d'erreur, retourner null
    }

    curl_close($ch);

    // Parse de la réponse SOAP
    $xml = simplexml_load_string($response);
    $namespaces = $xml->getNamespaces(true);
    $body = $xml->children($namespaces['Soap'])->Body;

    // Extraire le résultat
    $result = $body->children()->GetOrdersBySeller_Result;

    if (isset($result->vARJson)) {
        $json = json_decode((string)$result->vARJson, true);
                // Récupérer les 50 dernières commandes
                return array_slice($json, 0, 100);

    }

    return null; // Retourner null si la réponse est mal formée
}





public function GetEnteteBL()
{
    $soapRequest = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:GetEnteteBL>
         <b2b:vARJson></b2b:vARJson>
         <b2b:codeClient>***</b2b:codeClient>
      </b2b:GetEnteteBL>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $soapRequest);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: text/xml; charset=utf-8',
        'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:GetEnteteBL'
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return ['success' => false, 'message' => 'Erreur de connexion : ' . curl_error($ch)];
    }

    curl_close($ch);

    try {
        $xml = simplexml_load_string($response);
        $namespaces = $xml->getNamespaces(true);

        $body = $xml->children($namespaces['Soap'])->Body;
        $result = $body->children('urn:microsoft-dynamics-schemas/codeunit/B2BWS')->GetEnteteBL_Result;
        $jsonString = (string)$result->vARJson;

        $data = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['success' => false, 'message' => 'Erreur JSON : ' . json_last_error_msg()];
        }

        return ['success' => true, 'data' => $data];
    } catch (\Exception $e) {
        return ['success' => false, 'message' => 'Erreur traitement SOAP : ' . $e->getMessage()];
    }
}



public function convertShipmentToInvoice($orderNo)
{
    // Valider le paramètre
    if (empty($orderNo)) {
        return ['success' => false, 'message' => 'Numéro de commande vide'];
    }

    $soapRequest = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:ConvertSalesShipmentToInvoice>
         <b2b:orderNo>{$orderNo}</b2b:orderNo>
         <b2b:res></b2b:res>
      </b2b:ConvertSalesShipmentToInvoice>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $soapRequest);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: text/xml; charset=utf-8',
        'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:ConvertSalesShipmentToInvoice'
    ]);

    // Activer le débogage cURL
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    $verbose = fopen('php://temp', 'w+');
    curl_setopt($ch, CURLOPT_STDERR, $verbose);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['success' => false, 'message' => 'Erreur de connexion : ' . $error];
    }

    curl_close($ch);

    // Débogage : enregistrer la réponse brute et les détails cURL
    \Log::debug('Réponse SOAP brute : ' . $response);
    rewind($verbose);
    \Log::debug('Détails cURL : ' . stream_get_contents($verbose));

    try {
        $xml = simplexml_load_string($response);
        if ($xml === false) {
            return ['success' => false, 'message' => 'Erreur parsing XML : Réponse mal formée'];
        }

        $namespaces = $xml->getNamespaces(true);
        \Log::debug('Namespaces trouvés : ' . json_encode($namespaces));

        // Utiliser le namespace SOAP explicite
        $soapNamespace = $namespaces['soapenv'] ?? 'http://schemas.xmlsoap.org/soap/envelope/';
        $body = $xml->children($soapNamespace)->Body;
        if (!$body) {
            return ['success' => false, 'message' => 'Balise Body non trouvée dans la réponse SOAP'];
        }

        // Vérifier si une erreur SOAP est présente
        if ($body->Fault) {
            $fault = (string)$body->Fault->faultstring;
            return ['success' => false, 'message' => 'Erreur SOAP du serveur : ' . $fault];
        }

        $result = $body->children('urn:microsoft-dynamics-schemas/codeunit/B2BWS')->ConvertSalesShipmentToInvoice_Result;
        if (!$result) {
            return ['success' => false, 'message' => 'Balise ConvertSalesShipmentToInvoice_Result non trouvée'];
        }

        $res = (string)$result->res;
        if (empty($res)) {
            return ['success' => false, 'message' => 'Champ res vide ou non trouvé'];
        }

        return [
            'success' => true,
            'result' => filter_var($res, FILTER_VALIDATE_BOOLEAN)
        ];
    } catch (\Exception $e) {
        \Log::error('Erreur traitement SOAP : ' . $e->getMessage());
        return ['success' => false, 'message' => 'Erreur traitement SOAP : ' . $e->getMessage()];
    }
}









public function getOrderDetail($orderNo)
{
    // Corps de la requête SOAP
    $request = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:GetOrderDetail>
         <b2b:vARJson></b2b:vARJson>
         <b2b:orderNo>{$orderNo}</b2b:orderNo>
      </b2b:GetOrderDetail>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    // Initialisation de cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM); // Authentification NTLM
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request); // Utilisation du corps de la requête SOAP
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: text/xml; charset=utf-8',
        'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:GetOrderDetail'
    ]); // Headers SOAP

    // Exécution de la requête
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        return null; // En cas d'erreur, renvoyer null
    }

    curl_close($ch);

    // Traitement de la réponse SOAP
    if ($response) {
        $xml = simplexml_load_string($response);
        $namespaces = $xml->getNamespaces(true); // Récupérer les namespaces
        
        // Debug : afficher les namespaces pour mieux comprendre la structure
        // var_dump($namespaces); // Assurez-vous que le namespace correct est affiché
        
        // Accéder au body en utilisant le bon namespace
        $namespace = key($namespaces); // Récupérer le premier namespace (vérifiez si c'est 'soapenv')
        $body = $xml->children($namespaces[$namespace])->Body;

        // Chercher les éléments sans dépendre du namespace
        $result = $body->children()->GetOrderDetail_Result; // Assurez-vous que le nom du résultat est correct

        // Vérifier que le résultat existe et contient le vARJson
        if (isset($result->vARJson)) {
            $json = json_decode((string)$result->vARJson, true); // Décoder le JSON en tableau associatif
            return $json; // Retourner les détails de la commande sous forme de tableau
        }
    }

    return null; // En cas de réponse vide ou mal formée, renvoyer null
}











public function getAllVendors()
{
    // Corps de la requête SOAP
    $request = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:GetAllVendors>
         <b2b:vARJson></b2b:vARJson>
      </b2b:GetAllVendors>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    // Initialisation de cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/xml; charset=utf-8', 'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:GetAllVendors']);

    // Exécution de la requête
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return null; // En cas d'erreur, retourner null
    }

    curl_close($ch);

    // Parse de la réponse SOAP
    $xml = simplexml_load_string($response);
    $namespaces = $xml->getNamespaces(true);
    $body = $xml->children($namespaces['Soap'])->Body;

    // Extraire le résultat
    $result = $body->children()->GetAllVendors_Result;

    if (isset($result->vARJson)) {
        $json = json_decode((string)$result->vARJson, true);
        return $json; // Retourne les fournisseurs sous forme de tableau associatif
    }

    return null; // Retourner null si la réponse est mal formée
}




public function getItemsByVendor($vendor1, $vendor2 = null, $vendor3 = null)
{
    // Corps de la requête SOAP
    $request = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:GetItemsByVendor>
         <b2b:vendorNo1>{$vendor1}</b2b:vendorNo1>
         <b2b:vendorNo2>{$vendor2}</b2b:vendorNo2>
         <b2b:vendorNo3>{$vendor3}</b2b:vendorNo3>
         <b2b:vARJson></b2b:vARJson>
      </b2b:GetItemsByVendor>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    // Initialisation de cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/xml; charset=utf-8', 'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:GetItemsByVendor']);

    // Exécution de la requête
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return null; // En cas d'erreur, retourner null
    }

    curl_close($ch);

    // Parse de la réponse SOAP
    $xml = simplexml_load_string($response);
    $namespaces = $xml->getNamespaces(true);
    $body = $xml->children($namespaces['Soap'])->Body;

    // Extraire le résultat
    $result = $body->children()->GetItemsByVendor_Result;

    if (isset($result->vARJson)) {
        $json = json_decode((string)$result->vARJson, true);
        return $json; // Retourne les articles sous forme de tableau associatif
    }

    return null; // Retourner null si la réponse est mal formée
}








public function ImportSalesByVendorNo(array $panierData, $customerNo, $vendorNo, $commentaire)
{
    // Formater les données en JSON
    $formattedData = json_encode(array_map(function ($item) use ($customerNo) {
        return [
            'CodeArticle' => $item['item_reference'],
            'Qte' => (string)$item['quantity'],
            'CodeClient' => $customerNo,
        ];
    }, $panierData));

    // Corps de la requête SOAP
    $request = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:ImportSalesByVendorNo>
         <b2b:vARJson>{$formattedData}</b2b:vARJson>
         <b2b:cdeAfp>false</b2b:cdeAfp>
         <b2b:cdePrema>false</b2b:cdePrema>
         <b2b:vendorNo>{$vendorNo}</b2b:vendorNo>
         <b2b:commentaire>{$commentaire}</b2b:commentaire>
      </b2b:ImportSalesByVendorNo>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/xml; charset=utf-8', 'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:ImportSalesByVendorNo']);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return null; // Retourne null en cas d'erreur
    }

    curl_close($ch);

    // Parse de la réponse SOAP
    $xml = simplexml_load_string($response);
    $namespaces = $xml->getNamespaces(true);
    $namespace = key($namespaces); // Récupérer le premier namespace (vérifiez si c'est 'soapenv')
    $body = $xml->children($namespaces[$namespace])->Body;

    $result = $body->children()->ImportSalesByVendorNo_Result;






    
    return [
        'cdeAfp' => filter_var((string)$result->cdeAfp, FILTER_VALIDATE_BOOLEAN),
        'cdePrema' => filter_var((string)$result->cdePrema, FILTER_VALIDATE_BOOLEAN),
    ];
}






public function importSales(array $panierData, $customerNo)
{
    // Formater les données en JSON
    $formattedData = json_encode(array_map(function ($item) use ($customerNo) {
        return [
            'CodeArticle' => $item['item_reference'],
            'PrixVenteUnitaire' => $item['PrixVenteUnitaire'],
            'Qte' => (string)$item['quantity'],
            'RemiseSurCommande' => $item['remise'],
            'CodeClient' => $customerNo,
        ];
    }, $panierData));
    // dd($formattedData);
    // Corps de la requête SOAP
    $request = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:ImportSales>
         <b2b:vARJson>{$formattedData}</b2b:vARJson>
         <b2b:cdeTPG>false</b2b:cdeTPG>
      </b2b:ImportSales>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/xml; charset=utf-8', 'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:ImportSales']);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return null; // Retourne null en cas d'erreur
    }

    curl_close($ch);

    // Parse de la réponse SOAP
    $xml = simplexml_load_string($response);
    $namespaces = $xml->getNamespaces(true);
    $body = $xml->children($namespaces['Soap'])->Body;

    $result = $body->children()->ImportSales_Result;

    return [
        'cdeTPG' => filter_var((string)$result->cdeTPG, FILTER_VALIDATE_BOOLEAN),
    ];
}


public function createCustomer(array $data)
{
    $soapRequest = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:CreateNewCustomer>
         <b2b:customerName>{$data['customerName']}</b2b:customerName>
         <b2b:customerPostingGroup>{$data['customerPostingGroup']}</b2b:customerPostingGroup>
         <b2b:paymentTermsCode>{$data['paymentTermsCode']}</b2b:paymentTermsCode>
         <b2b:countryRegionCode>{$data['countryRegionCode']}</b2b:countryRegionCode>
         <b2b:genBusPostingGroup>{$data['genBusPostingGroup']}</b2b:genBusPostingGroup>
         <b2b:grpComptaTVAClt>{$data['grpComptaTVAClt']}</b2b:grpComptaTVAClt>
         <b2b:matFiscale>{$data['matFiscale']}</b2b:matFiscale>
         <b2b:city>{$data['city']}</b2b:city>
         <b2b:phoneNo>{$data['phoneNo']}</b2b:phoneNo>
         <b2b:adresse>{$data['adresse']}</b2b:adresse>
      </b2b:CreateNewCustomer>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $soapRequest);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: text/xml; charset=utf-8',
        'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:CreateNewCustomer'
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return ['success' => false, 'message' => 'Erreur de connexion au service web'];
    }

    curl_close($ch);

    // Traitement de la réponse XML
    try {
        $xml = simplexml_load_string($response);
        $namespaces = $xml->getNamespaces(true);
        $body = $xml->children($namespaces['Soap'])->Body;
        $result = $body->children($namespaces[''])->CreateNewCustomer_Result;

        $message = (string)$result->return_value;

        return ['success' => true, 'message' => $message];
    } catch (\Exception $e) {
        return ['success' => false, 'message' => 'Erreur lors du traitement de la réponse SOAP'];
    }
}



public function CustomerList()
{
    // Corps de la requête SOAP
    $request = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:CustomerList>
         <b2b:vARJson></b2b:vARJson>
      </b2b:CustomerList>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/xml; charset=utf-8', 'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:CustomerList']);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return null; // Retourne null en cas d'erreur
    }

    curl_close($ch);

    // Parse de la réponse SOAP
    $xml = simplexml_load_string($response);
    $namespaces = $xml->getNamespaces(true);
    $body = $xml->children($namespaces['Soap'])->Body;

    $result = $body->children()->CustomerList_Result->vARJson;

    return json_decode((string)$result, true);
}




public function getLastSalesShipmentInfo()
{
    // Corps de la requête SOAP
    $request = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:GetLastSalesShipmentInfo>
         <b2b:lastShipmentNo>?</b2b:lastShipmentNo>
         <b2b:salesOrderNo>?</b2b:salesOrderNo>
         <b2b:customerNo>?</b2b:customerNo>
      </b2b:GetLastSalesShipmentInfo>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    // Initialisation de cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM); // Authentification NTLM
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: text/xml; charset=utf-8',
        'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:GetLastSalesShipmentInfo'
    ]);

    // Exécution de la requête
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        return null;
    }

    curl_close($ch);

    // Traitement de la réponse SOAP
    if ($response) {
        $xml = simplexml_load_string($response);
        $namespaces = $xml->getNamespaces(true);
        $body = $xml->children($namespaces['Soap'])->Body;

        $result = $body->children()->GetLastSalesShipmentInfo_Result;

        if ($result) {
            return [
                'NumBL' => (string) $result->lastShipmentNo ?? null,
                'OrderNo' => (string) $result->salesOrderNo ?? null,
                'CustomerNo' => (string) $result->customerNo ?? null,
            ];
        }
    }

    return null;
}






public function getItemBLHistory(string $itemNo)
{
    $soapRequest = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:GetItemBLHistory>
         <b2b:itemNo>{$itemNo}</b2b:itemNo>
         <b2b:vARJson></b2b:vARJson>
      </b2b:GetItemBLHistory>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $soapRequest);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: text/xml; charset=utf-8',
        'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:GetItemBLHistory'
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return ['success' => false, 'message' => 'Erreur de connexion au service web'];
    }

    curl_close($ch);

    // Traitement de la réponse XML
    try {
        $xml = simplexml_load_string($response);
        $namespaces = $xml->getNamespaces(true);
        $body = $xml->children($namespaces['Soap'])->Body;
        $result = $body->children($namespaces[''])->GetItemBLHistory_Result;
        $jsonData = (string) $result->vARJson;

        $data = json_decode($jsonData, true);

        return ['success' => true, 'data' => $data];
    } catch (\Exception $e) {
        return ['success' => false, 'message' => 'Erreur lors du traitement de la réponse SOAP'];
    }
}






public function getinvoices($customerCode)
{
    // Corps de la requête SOAP
    $request = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:GetFactures>
      <b2b:vARJson></b2b:vARJson>
      <b2b:codeClient>{$customerCode}</b2b:codeClient>
      </b2b:GetFactures>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    // Initialisation de cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/xml; charset=utf-8', 'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:GetFactures']);

    // Exécution de la requête
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return null; // En cas d'erreur, retourner null
    }

    curl_close($ch);

    // Parse de la réponse SOAP
    $xml = simplexml_load_string($response);
    $namespaces = $xml->getNamespaces(true);
    $body = $xml->children($namespaces['Soap'])->Body;

    // Extraire le résultat
    $result = $body->children()->GetFactures_Result;

    if (isset($result->vARJson)) {
        $json = json_decode((string)$result->vARJson, true);
        return $json; // Retourne les factures sous forme de tableau associatif
    }

    return null; // Retourner null si la réponse est mal formée
}






public function getinvoiceDetail($NumFacture)
{
    // Corps de la requête SOAP
    $request = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:GetLignesFactures>
         <b2b:vARJson></b2b:vARJson>
         <b2b:codeFacture>{$NumFacture}</b2b:codeFacture>
      </b2b:GetLignesFactures>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    // Initialisation de cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM); // Authentification NTLM
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request); // Utilisation du corps de la requête SOAP
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: text/xml; charset=utf-8',
        'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:GetLignesFactures'
    ]); // Headers SOAP

    // Exécution de la requête
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        return null; // En cas d'erreur, renvoyer null
    }

    curl_close($ch);

    // Traitement de la réponse SOAP
    if ($response) {
        $xml = simplexml_load_string($response);
        $namespaces = $xml->getNamespaces(true); // Récupérer les namespaces
        
        // Debug : afficher les namespaces pour mieux comprendre la structure
        // var_dump($namespaces); // Assurez-vous que le namespace correct est affiché
        
        // Accéder au body en utilisant le bon namespace
        $namespace = key($namespaces); // Récupérer le premier namespace (vérifiez si c'est 'soapenv')
        $body = $xml->children($namespaces[$namespace])->Body;

        // Chercher les éléments sans dépendre du namespace
        $result = $body->children()->GetLignesFactures_Result; // Assurez-vous que le nom du résultat est correct

        // Vérifier que le résultat existe et contient le vARJson
        if (isset($result->vARJson)) {
            $json = json_decode((string)$result->vARJson, true); // Décoder le JSON en tableau associatif
            return $json; // Retourner les détails de la commande sous forme de tableau
        }
    }

    return null; // En cas de réponse vide ou mal formée, renvoyer null
}





public function getAvoirs($customerCode)
{
    // Corps de la requête SOAP
    $request = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:GetAvoirs>
      <b2b:vARJson></b2b:vARJson>
      <b2b:codeClient>{$customerCode}</b2b:codeClient>
      </b2b:GetAvoirs>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    // Initialisation de cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/xml; charset=utf-8', 'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:GetAvoirs']);

    // Exécution de la requête
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return null; // En cas d'erreur, retourner null
    }

    curl_close($ch);

    // Parse de la réponse SOAP
    $xml = simplexml_load_string($response);
    $namespaces = $xml->getNamespaces(true);
    $body = $xml->children($namespaces['Soap'])->Body;

    // Extraire le résultat
    $result = $body->children()->GetAvoirs_Result;

    if (isset($result->vARJson)) {
        $json = json_decode((string)$result->vARJson, true);
        return $json; // Retourne les avoirs sous forme de tableau associatif
    }

    return null; // Retourner null si la réponse est mal formée
}



public function getCreditNoteDetail($NumAvoir)
{
    // Corps de la requête SOAP
    $request = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:GetLignesAvoirs>
         <b2b:vARJson></b2b:vARJson>
         <b2b:codeFacture>{$NumAvoir}</b2b:codeFacture>
      </b2b:GetLignesAvoirs>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    // Initialisation de cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: text/xml; charset=utf-8',
        'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:GetLignesAvoirs'
    ]);

    // Exécution de la requête
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
        return null;
    }
    curl_close($ch);

    // Traitement de la réponse SOAP
    if ($response) {
        $xml = simplexml_load_string($response);
        $namespaces = $xml->getNamespaces(true);
        $body = $xml->children($namespaces['Soap'])->Body;
        $result = $body->children($namespaces[''])->GetLignesAvoirs_Result;

        if (isset($result->vARJson)) {
            return json_decode((string)$result->vARJson, true);
        }
    }

    return null;
}






public function GetRcptAchatEnreg()
{
    $soapRequest = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:GetRcptAchatEnreg>
         <b2b:vARJson></b2b:vARJson>
         <b2b:codeVendor>***</b2b:codeVendor>
      </b2b:GetRcptAchatEnreg>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url); // Assure-toi que $this->url est bien défini
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password); // Authentification NTLM
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $soapRequest);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: text/xml; charset=utf-8',
        'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:GetRcptAchatEnreg'
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return ['success' => false, 'message' => 'Erreur de connexion : ' . curl_error($ch)];
    }

    curl_close($ch);

    try {
        $xml = simplexml_load_string($response);
        $namespaces = $xml->getNamespaces(true);

        $body = $xml->children($namespaces['Soap'])->Body;
        $result = $body->children('urn:microsoft-dynamics-schemas/codeunit/B2BWS')->GetRcptAchatEnreg_Result;
        $jsonString = (string)$result->vARJson;

        $data = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['success' => false, 'message' => 'Erreur JSON : ' . json_last_error_msg()];
        }

        return ['success' => true, 'data' => $data];
    } catch (\Exception $e) {
        return ['success' => false, 'message' => 'Erreur traitement SOAP : ' . $e->getMessage()];
    }
}


public function GetPostedPurchaseReceiptLines($documentNo)
{
    $soapRequest = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:b2b="urn:microsoft-dynamics-schemas/codeunit/B2BWS">
   <soapenv:Header/>
   <soapenv:Body>
      <b2b:GetPostedPurchaseReceiptLines>
         <b2b:vARJson></b2b:vARJson>
         <b2b:documentNo>{$documentNo}</b2b:documentNo>
      </b2b:GetPostedPurchaseReceiptLines>
   </soapenv:Body>
</soapenv:Envelope>
XML;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url); // Remplace $this->url par l'URL de ton service
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $soapRequest);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: text/xml; charset=utf-8',
        'SOAPAction: urn:microsoft-dynamics-schemas/codeunit/B2BWS:GetPostedPurchaseReceiptLines'
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return ['success' => false, 'message' => 'Erreur de connexion : ' . curl_error($ch)];
    }

    curl_close($ch);

    try {
        $xml = simplexml_load_string($response);
        $namespaces = $xml->getNamespaces(true);

        $body = $xml->children($namespaces['Soap'])->Body;
        $result = $body->children('urn:microsoft-dynamics-schemas/codeunit/B2BWS')->GetPostedPurchaseReceiptLines_Result;
        $jsonString = (string)$result->vARJson;

        $data = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['success' => false, 'message' => 'Erreur JSON : ' . json_last_error_msg()];
        }

        return ['success' => true, 'data' => $data];
    } catch (\Exception $e) {
        return ['success' => false, 'message' => 'Erreur traitement SOAP : ' . $e->getMessage()];
    }
}








}