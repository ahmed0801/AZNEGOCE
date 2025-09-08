<?php

namespace App\Http\Controllers;

use App\Services\BusinessCentralService;
use Illuminate\Http\Request;
use PDF;

class OrderController extends Controller
{
    private $businessCentralService;

    public function __construct(BusinessCentralService $businessCentralService)
    {
        $this->businessCentralService = $businessCentralService;
    }

    public function index()
{
    $response = $this->businessCentralService->GetEnteteBL();

    if (!$response['success']) {
        return back()->withErrors(['orders' => $response['message']]);
    }

    $orders = $response['data'];

    // Récupération ou chargement de la session clients
    $clients = session('clients');
    if (!$clients) {
        $clients = $this->businessCentralService->CustomerList() ?? [];
        session(['clients' => $clients]);
    }

    // Transformer en collection pour une recherche plus simple
    $clientCollection = collect($clients);

    // Enrichir les commandes avec le nom du client
    foreach ($orders as &$order) {
        $clientInfo = $clientCollection->firstWhere('CustomerNo', $order['CustomerNo']);
        $order['CustomerName'] = $clientInfo['CustomerName'] ?? 'Inconnu';
    }

    // Trier les commandes par NumBL descendant
    usort($orders, function ($a, $b) {
        return strcmp($b['NumBL'], $a['NumBL']);
    });

    return view('order', compact('orders'));
}




    public function show($orderNo)
    {
        // Appeler la méthode pour obtenir les détails de la commande
        $orderDetails = $this->businessCentralService->getOrderDetail($orderNo);
    
        // Debug : Afficher les données de la commande pour vérifier la structure
        // dd($orderDetails);
    
        // Vérifier si les détails ont été récupérés
        if (!$orderDetails) {
            return redirect()->back()->with('error', 'Détails de la commande non trouvés.');
        }
    
        // Retourner la vue avec les données
        return view('order_details', compact('orderDetails','orderNo'));
    }
    

    public function facturer($numCommande)
{

    // dd($numCommande);
    $result = $this->businessCentralService->convertShipmentToInvoice($numCommande);

    if ($result['success']) {
        return redirect("/invoices")->with('success', 'Commande facturée avec succès');
    } else {
        return redirect()->back()->with('error', 'Erreur lors de la facturation : ' . $result['message']);
    }
}




public function exportPdf($NumBL, $orderNo, $CustomerNo, $DateBL)
{
    // Récupérer la liste des clients depuis la session ou via l’API
    $clients = session('clients');
    if (!$clients) {
        $clients = $this->businessCentralService->CustomerList() ?? [];
        session(['clients' => $clients]);
    }

    // Rechercher le client correspondant
    $clientInfo = collect($clients)->firstWhere('CustomerNo', $CustomerNo);

    // Extraire les informations client
    $CustomerName = $clientInfo['CustomerName'] ?? 'Client inconnu';
    $VATCode = $clientInfo['Adresse'] ?? 'Non renseigné';
    $MatFiscale = $clientInfo['MatFiscale'] ?? 'Non renseigné';

    // Récupérer les détails de la commande
    $orderDetails = $this->businessCentralService->getOrderDetail($orderNo);
    if (!$orderDetails) {
        return redirect()->back()->with('error', 'Impossible d\'exporter la facture. Détails introuvables.');
    }

    // Nettoyer et formater les données numériques
    foreach ($orderDetails as &$item) {
        $item['PrixUnitaire'] = $this->parseNumber($item['PrixUnitaire']);
        $item['Quantite'] = $this->parseNumber($item['Quantite']);
        $item['Remise'] = $this->parseNumber($item['Remise']);
        $item['totalHT'] = $this->parseNumber($item['totalHT']);
        $item['totalTTC'] = $this->parseNumber($item['totalTTC']);
        $item['TVA'] = $this->parseNumber($item['TVA']);
    }

    // Générer le PDF
    $pdf = PDF::loadView('pdf.order', [
        'orderDetails' => $orderDetails,
        'NumBL' => $NumBL,
        'customerNo' => $CustomerNo,
        'CustomerName' => $CustomerName,
        'VATCode' => $VATCode,
        'MatFiscale' => $MatFiscale,
        'DateBL' => $DateBL,
    ]);

    // Stream PDF dans le navigateur
    return $pdf->stream("BL_{$NumBL}.pdf");
}




private function parseNumber($value)
{
    if (is_null($value) || $value === '') {
        return 0.0;
    }

    $value = trim((string) $value);

    // Si le nombre contient à la fois des points et des virgules
    if (strpos($value, ',') !== false && strpos($value, '.') !== false) {
        // On suppose que la virgule est le séparateur de milliers, donc on la supprime
        $value = str_replace(',', '', $value);
    } else {
        // Sinon, on suppose que la virgule est un séparateur décimal → on la remplace par un point
        $value = str_replace(',', '.', $value);
    }

    return is_numeric($value) ? (float)$value : 0.0;
}




    

public function exportPdfsansref($NumBL, $orderNo, $CustomerNo, $DateBL)
{

// Récupérer la liste des clients depuis la session ou via l’API
$clients = session('clients');
if (!$clients) {
    $clients = $this->businessCentralService->CustomerList() ?? [];
    session(['clients' => $clients]);
}

// Rechercher le client correspondant
$clientInfo = collect($clients)->firstWhere('CustomerNo', $CustomerNo);

// Extraire les informations client
$CustomerName = $clientInfo['CustomerName'] ?? 'Client inconnu';
$VATCode = $clientInfo['Adresse'] ?? 'Non renseigné';
$MatFiscale = $clientInfo['MatFiscale'] ?? 'Non renseigné';

// Récupérer les détails de la commande
$orderDetails = $this->businessCentralService->getOrderDetail($orderNo);
if (!$orderDetails) {
    return redirect()->back()->with('error', 'Impossible d\'exporter la facture. Détails introuvables.');
}

// Nettoyer et formater les données numériques
foreach ($orderDetails as &$item) {
    $item['PrixUnitaire'] = $this->parseNumber($item['PrixUnitaire']);
    $item['Quantite'] = $this->parseNumber($item['Quantite']);
    $item['Remise'] = $this->parseNumber($item['Remise']);
    $item['totalHT'] = $this->parseNumber($item['totalHT']);
    $item['totalTTC'] = $this->parseNumber($item['totalTTC']);
    $item['TVA'] = $this->parseNumber($item['TVA']);
}

   

    // Générer le PDF avec les données
    $pdf = PDF::loadView('pdf.ordersansref', [
        'orderDetails' => $orderDetails,
        'NumBL' => $NumBL,
        'customerNo' => $CustomerNo,
        'CustomerName' => $CustomerName,
        'VATCode' => $VATCode,
        'MatFiscale' => $MatFiscale,
        'DateBL' => $DateBL,

    ]);

    // Télécharger le PDF avec un nom spécifique
    return $pdf->stream("BL_{$NumBL}.pdf");
}





public function exportPdfsansremise($NumBL, $orderNo, $CustomerNo, $DateBL)
{

// Récupérer la liste des clients depuis la session ou via l’API
$clients = session('clients');
if (!$clients) {
    $clients = $this->businessCentralService->CustomerList() ?? [];
    session(['clients' => $clients]);
}

// Rechercher le client correspondant
$clientInfo = collect($clients)->firstWhere('CustomerNo', $CustomerNo);

// Extraire les informations client
$CustomerName = $clientInfo['CustomerName'] ?? 'Client inconnu';
$VATCode = $clientInfo['Adresse'] ?? 'Non renseigné';
$MatFiscale = $clientInfo['MatFiscale'] ?? 'Non renseigné';

// Récupérer les détails de la commande
$orderDetails = $this->businessCentralService->getOrderDetail($orderNo);
if (!$orderDetails) {
    return redirect()->back()->with('error', 'Impossible d\'exporter la facture. Détails introuvables.');
}

// Nettoyer et formater les données numériques
foreach ($orderDetails as &$item) {
    $item['PrixUnitaire'] = $this->parseNumber($item['PrixUnitaire']);
    $item['Quantite'] = $this->parseNumber($item['Quantite']);
    $item['Remise'] = $this->parseNumber($item['Remise']);
    $item['totalHT'] = $this->parseNumber($item['totalHT']);
    $item['totalTTC'] = $this->parseNumber($item['totalTTC']);
    $item['TVA'] = $this->parseNumber($item['TVA']);
}

   

    // Générer le PDF avec les données
    $pdf = PDF::loadView('pdf.ordersansremise', [
        'orderDetails' => $orderDetails,
        'NumBL' => $NumBL,
        'customerNo' => $CustomerNo,
        'CustomerName' => $CustomerName,
        'VATCode' => $VATCode,
        'MatFiscale' => $MatFiscale,
        'DateBL' => $DateBL,

    ]);

    // Télécharger le PDF avec un nom spécifique
    return $pdf->stream("BL_{$NumBL}.pdf");
}




public function exportPdfsans2($NumBL, $orderNo, $CustomerNo, $DateBL)
{

// Récupérer la liste des clients depuis la session ou via l’API
$clients = session('clients');
if (!$clients) {
    $clients = $this->businessCentralService->CustomerList() ?? [];
    session(['clients' => $clients]);
}

// Rechercher le client correspondant
$clientInfo = collect($clients)->firstWhere('CustomerNo', $CustomerNo);

// Extraire les informations client
$CustomerName = $clientInfo['CustomerName'] ?? 'Client inconnu';
$VATCode = $clientInfo['Adresse'] ?? 'Non renseigné';
$MatFiscale = $clientInfo['MatFiscale'] ?? 'Non renseigné';

// Récupérer les détails de la commande
$orderDetails = $this->businessCentralService->getOrderDetail($orderNo);
if (!$orderDetails) {
    return redirect()->back()->with('error', 'Impossible d\'exporter la facture. Détails introuvables.');
}

// Nettoyer et formater les données numériques
foreach ($orderDetails as &$item) {
    $item['PrixUnitaire'] = $this->parseNumber($item['PrixUnitaire']);
    $item['Quantite'] = $this->parseNumber($item['Quantite']);
    $item['Remise'] = $this->parseNumber($item['Remise']);
    $item['totalHT'] = $this->parseNumber($item['totalHT']);
    $item['totalTTC'] = $this->parseNumber($item['totalTTC']);
    $item['TVA'] = $this->parseNumber($item['TVA']);
}

   

    // Générer le PDF avec les données
    $pdf = PDF::loadView('pdf.ordersans2', [
        'orderDetails' => $orderDetails,
        'NumBL' => $NumBL,
        'customerNo' => $CustomerNo,
        'CustomerName' => $CustomerName,
        'VATCode' => $VATCode,
        'MatFiscale' => $MatFiscale,
        'DateBL' => $DateBL,

    ]);

    // Télécharger le PDF avec un nom spécifique
    return $pdf->stream("BL_{$NumBL}.pdf");
}


    

}
