<?php

namespace App\Http\Controllers;
use App\Services\BusinessCentralService;
use PDF; 
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    private $businessCentralService;

    public function __construct(BusinessCentralService $businessCentralService)
    {
        $this->businessCentralService = $businessCentralService;
    }

    public function index()
    {
        // Récupérer le code client depuis la session
        $customerCode ="***";

        // Appel au service Business Central pour récupérer les commandes
        $invoices = $this->businessCentralService->getinvoices($customerCode);
        // dd($invoices);



        // Retourner les données à la vue
        return view('invoice', compact('invoices'));
    }


    public function show($NumFacture)
    {
        // Appeler la méthode pour obtenir les détails de la commande
        $invoiceDetails = $this->businessCentralService->getinvoiceDetail($NumFacture);
    
        // Debug : Afficher les données de la commande pour vérifier la structure
        // dd($invoiceDetails);
    
        // Vérifier si les détails ont été récupérés
        if (!$invoiceDetails) {
            return redirect()->back()->with('error', 'Détails de la facture non trouvés.');
        }
    
        // Retourner la vue avec les données
        return view('invoice_details', compact('invoiceDetails','NumFacture'));
    }
    
    
    public function exportPdf($NumFacture,$DateFacture,$CodeClient)
    {
        // Récupérer les détails de la facture
        $invoiceDetails = $this->businessCentralService->getinvoiceDetail($NumFacture);
    
        if (!$invoiceDetails) {
            return redirect()->back()->with('error', 'Impossible d\'exporter la facture. Détails introuvables.');
        }


         // Récupérer la liste des clients depuis la session ou l'API
    $clients = session('clients');
    if (!$clients) {
        $clients = $this->businessCentralService->CustomerList() ?? [];
        session(['clients' => $clients]);
    }
    // Rechercher le client correspondant au CustomerNo
    $clientInfo = collect($clients)->firstWhere('CustomerNo', $CodeClient);

    // Extraire les informations si le client existe
    $CustomerName = $clientInfo['CustomerName'] ?? 'Client inconnu';
    $VATCode = $clientInfo['Adresse'] ?? 'Non renseigné';
    $MatFiscale = $clientInfo['MatFiscale'] ?? 'Non renseigné';

    
        // Calculer le montant total de la facture
        $totalAmount = array_sum(array_map(function($invoice) {
            return floatval(str_replace(',', '', $invoice['MontantTTC'] ?? 0));
        }, $invoiceDetails));

                // Calculer le montant total de la facture
                $totalAmountHT = array_sum(array_map(function($invoice) {
                    return floatval(str_replace(',', '', $invoice['MontantHT'] ?? 0));
                }, $invoiceDetails));
$MontantTVA = $totalAmount - $totalAmountHT;
    // dd($invoiceDetails);
        // Générer le PDF avec les données
        $pdf = PDF::loadView('pdf.invoice', [
            'invoiceDetails' => $invoiceDetails,
            'NumFacture' => $NumFacture,
            'customerName' => $CustomerName,
            'customerNo' => $CodeClient,
            'totalAmount' => $totalAmount,
            'totalAmountHT' => $totalAmountHT,
            'MontantTVA' => $MontantTVA,
            'DateFacture' => $DateFacture,
            'MatFiscale' => $MatFiscale,
            'VATCode' => $VATCode,

        ]);
    
        // Télécharger le PDF avec un nom spécifique
        return $pdf->stream("Facture_{$NumFacture}.pdf");
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
    




}
