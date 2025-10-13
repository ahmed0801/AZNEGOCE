<?php
namespace App\Http\Controllers;

use App\Services\BusinessCentralService;
use Illuminate\Http\Request;
use PDF;

class AvoirController extends Controller
{
    private $businessCentralService;

    public function __construct(BusinessCentralService $businessCentralService)
    {
        $this->businessCentralService = $businessCentralService;
    }

    public function index()
    {
        $customerCode = "***";

        $avoirs = $this->businessCentralService->getAvoirs($customerCode);

        

        return view('avoir', compact('avoirs'));
    }

    public function show($NumAvoir)
    {
        $creditNoteDetails = $this->businessCentralService->getCreditNoteDetail($NumAvoir);
// dd($creditNoteDetails);
        if (!$creditNoteDetails) {
            return redirect()->back()->with('error', 'Détails de l\'avoir non trouvés.');
        }

        return view('avoir_details', compact('creditNoteDetails', 'NumAvoir'));
    }

    public function exportCreditNotePdf_old($NumAvoir)
    {
        $creditNoteDetails = $this->businessCentralService->getCreditNoteDetail($NumAvoir);

        if (!$creditNoteDetails) {
            return redirect()->back()->with('error', 'Impossible d\'exporter l\'avoir. Détails introuvables.');
        }

        $customerName = session('user')['CustomerName'];
        $customerNo = session('user')['CustomerNo'];
        $totalAmount = array_sum(array_map(function ($creditNote) {
            return floatval(str_replace(',', '', $creditNote['MontantTTC'] ?? 0));
        }, $creditNoteDetails));

        $pdf = PDF::loadView('pdf.avoir', [
            'creditNoteDetails' => $creditNoteDetails,
            'NumAvoir' => $NumAvoir,
            'customerName' => $customerName,
            'customerNo' => $customerNo,
            'totalAmount' => $totalAmount,
        ]);

        return $pdf->stream("Avoir_{$NumAvoir}_Duplicata.pdf");
    }



    public function exportCreditNotePdf($NumAvoir, $DateFacture, $CustomerNo)
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
        $creditNoteDetails = $this->businessCentralService->getCreditNoteDetail($NumAvoir);

        if (!$creditNoteDetails) {
            return redirect()->back()->with('error', 'Impossible d\'exporter l\'avoir. Détails introuvables.');
        }

        
        $totalAmount = array_sum(array_map(function ($creditNote) {
            return floatval(str_replace(',', '', $creditNote['MontantTTC'] ?? 0));
        }, $creditNoteDetails));

        $totalHT = array_sum(array_map(function ($creditNote) {
            return floatval(str_replace(',', '', $creditNote['MontantHT'] ?? 0));
        }, $creditNoteDetails));

        $totalTVA = $totalAmount - $totalHT;

        $pdf = PDF::loadView('pdf.avoir', [
            'creditNoteDetails' => $creditNoteDetails,
            'NumAvoir' => $NumAvoir,
            'CustomerName' => $CustomerName,
            'customerNo' => $CustomerNo,
            'totalAmount' => $totalAmount,
            'totalHT' => $totalHT,
            'totalAmount' => $totalAmount,
            'totalTVA' => $totalTVA,
        'MatFiscale' => $MatFiscale,
        'VATCode' => $VATCode,
        'DateBL' => $DateFacture,

        ]);

        return $pdf->stream("Avoir_{$NumAvoir}_Duplicata.pdf");
    }

}

