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
        return redirect()->back()->with('success', 'Commande facturée avec succès');
    } else {
        return redirect()->back()->with('error', 'Erreur lors de la facturation : ' . $result['message']);
    }
}



public function exportPdf($NumBL, $orderNo, $CustomerNo, $DateBL)
    {


         // Récupérer la liste des clients depuis la session ou l'API
    $clients = session('clients');
    if (!$clients) {
        $clients = $this->businessCentralService->CustomerList() ?? [];
        session(['clients' => $clients]);
    }

    // Rechercher le client correspondant au CustomerNo
    $clientInfo = collect($clients)->firstWhere('CustomerNo', $CustomerNo);

    // Extraire les informations si le client existe
    $CustomerName = $clientInfo['CustomerName'] ?? 'Client inconnu';
    $VATCode = $clientInfo['Adresse'] ?? 'Non renseigné';
    $MatFiscale = $clientInfo['MatFiscale'] ?? 'Non renseigné';



        // Appeler la méthode pour obtenir les détails de la commande
        $orderDetails = $this->businessCentralService->getOrderDetail($orderNo);
    // dd($orderDetails);
        if (!$orderDetails) {
            return redirect()->back()->with('error', 'Impossible d\'exporter la facture. Détails introuvables.');
        }
    
       
    
        // Générer le PDF avec les données
        $pdf = PDF::loadView('pdf.order', [
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



    

public function exportPdfsansref($NumBL, $orderNo, $CustomerNo, $DateBL)
{


     // Récupérer la liste des clients depuis la session ou l'API
$clients = session('clients');
if (!$clients) {
    $clients = $this->businessCentralService->CustomerList() ?? [];
    session(['clients' => $clients]);
}

// Rechercher le client correspondant au CustomerNo
$clientInfo = collect($clients)->firstWhere('CustomerNo', $CustomerNo);

// Extraire les informations si le client existe
$CustomerName = $clientInfo['CustomerName'] ?? 'Client inconnu';
$VATCode = $clientInfo['Adresse'] ?? 'Non renseigné';
$MatFiscale = $clientInfo['MatFiscale'] ?? 'Non renseigné';



    // Appeler la méthode pour obtenir les détails de la commande
    $orderDetails = $this->businessCentralService->getOrderDetail($orderNo);
// dd($orderDetails);
    if (!$orderDetails) {
        return redirect()->back()->with('error', 'Impossible d\'exporter la facture. Détails introuvables.');
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




    

}
