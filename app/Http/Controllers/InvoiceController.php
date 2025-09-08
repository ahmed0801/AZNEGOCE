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
    


    // faire avoir article avec quantité
    public function createCreditMemo(Request $request)
{
    $request->validate([
        'NumFacture' => 'required',
        'CodeArticle' => 'required',
        'Quantite' => 'required|numeric|min:1',
    ]);

    // (Optionnel) Vérification côté serveur de la quantité max
    $details = $this->businessCentralService->getinvoiceDetail($request->NumFacture);
    $line = collect($details)->firstWhere('CodeArticle', $request->CodeArticle);

    if (!$line) {
        return redirect()->back()->with('error', "Article non trouvé dans la facture.");
    }

    if ((int)$request->Quantite > (int)$line['Quantite']) {
        return redirect()->back()->with('error', "❌ Quantité demandée dépasse la quantité facturée ({$line['Quantite']}).");
    }

    $result = $this->businessCentralService->CreateCreditMemoForItemWithQty(
        $request->NumFacture,
        $request->CodeArticle,
        $request->Quantite
    );

    if ($result['success']) {
        return redirect("/avoirs")->with('success', $result['message']);
    } else {
        return redirect()->back()->with('error', $result['message']);
    }
}



// avoir total
public function createTotalCreditMemo(Request $request)
{
    $request->validate([
        'NumFacture' => 'required|string',
    ]);

    $NumFacture = $request->input('NumFacture');

    $success = $this->businessCentralService->createTotalCreditMemo($NumFacture);

    if ($success) {
        return redirect("/avoirs")->with('success', "✅ Avoir total généré pour la facture {$NumFacture}.");
    } else {
        return redirect()->back()->with('error', "❌ Erreur lors de la génération de l’avoir pour la facture {$NumFacture}.");
    }
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



foreach ($invoiceDetails as &$item) {
    $item['PrixUnitaire'] = $this->parseNumber($item['PrixUnitaire'] ?? 0);
    $item['Quantite'] = $this->parseNumber($item['Quantite'] ?? 0);
    $item['MontantHT'] = $this->parseNumber($item['MontantHT'] ?? 0);
    $item['MontantTTC'] = $this->parseNumber($item['MontantTTC'] ?? 0);
}



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


    public function exportPdfsansref($NumFacture,$DateFacture,$CodeClient)
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



foreach ($invoiceDetails as &$item) {
    $item['PrixUnitaire'] = $this->parseNumber($item['PrixUnitaire'] ?? 0);
    $item['Quantite'] = $this->parseNumber($item['Quantite'] ?? 0);
    $item['MontantHT'] = $this->parseNumber($item['MontantHT'] ?? 0);
    $item['MontantTTC'] = $this->parseNumber($item['MontantTTC'] ?? 0);
}



    // dd($invoiceDetails);
        // Générer le PDF avec les données
        $pdf = PDF::loadView('pdf.invoicesansref', [
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







    public function exportPdfduplic($NumFacture,$DateFacture,$CodeClient)
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



foreach ($invoiceDetails as &$item) {
    $item['PrixUnitaire'] = $this->parseNumber($item['PrixUnitaire'] ?? 0);
    $item['Quantite'] = $this->parseNumber($item['Quantite'] ?? 0);
    $item['MontantHT'] = $this->parseNumber($item['MontantHT'] ?? 0);
    $item['MontantTTC'] = $this->parseNumber($item['MontantTTC'] ?? 0);
}



    // dd($invoiceDetails);
        // Générer le PDF avec les données
        $pdf = PDF::loadView('pdf.invoiceduplic', [
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
    
        // Supprimer les espaces et forcer la valeur en string
        $value = trim((string) $value);
    
        // Supprimer les virgules qui servent de séparateurs de milliers
        // Exemple : "1,600" devient "1600"
        $value = str_replace(',', '', $value);
    
        // Remplacer les virgules décimales par des points (au cas où)
        $value = str_replace(',', '.', $value);
    
        // Retourner un float si c’est numérique, sinon 0.0
        return is_numeric($value) ? (float)$value : 0.0;
    }
    




}
