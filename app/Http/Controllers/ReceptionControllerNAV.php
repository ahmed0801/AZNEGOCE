<?php

namespace App\Http\Controllers;

use App\Services\BusinessCentralService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceptionController extends Controller
{


    private $businessCentralService;

    public function __construct(BusinessCentralService $businessCentralService)
    {
        $this->businessCentralService = $businessCentralService;
    }

    public function index()
    {
        $codeVendor = '***'; // remplace par un vrai code fournisseur ou récupère dynamiquement
        $response = $this->businessCentralService->GetRcptAchatEnreg($codeVendor);
        
        if (!$response['success']) {
            return view('reception')->with('error', $response['message']);
        }
    
        $data = $response['data'];
        return view('reception', compact('data'));
    }
    


    public function show($NumReception)
    {
        // Appeler la méthode pour obtenir les détails de la commande
        $receptionrDetails = $this->businessCentralService->GetPostedPurchaseReceiptLines($NumReception);
    // dd($receptionrDetails);
        // Debug : Afficher les données de la commande pour vérifier la structure
    
        // Vérifier si les détails ont été récupérés
        if (!$receptionrDetails) {
            return redirect()->back()->with('error', 'Détails de la commande non trouvés.');
        }
        $data = $receptionrDetails['data'];
        // Retourner la vue avec les données
        return view('reception_details', compact('data'));
    }


    public function showMultiple($Nums)
{
    $numsArray = explode(',', $Nums);
    $allDetails = [];

    foreach ($numsArray as $num) {
        $receptionDetails = $this->businessCentralService->GetPostedPurchaseReceiptLines($num);
        if ($receptionDetails && isset($receptionDetails['data'])) {
            foreach ($receptionDetails['data'] as $ligne) {
                $ligne['NumReception'] = $num; // pour garder la trace
                $allDetails[] = $ligne;
            }
        }
    }

    if (empty($allDetails)) {
        return redirect()->back()->with('error', 'Aucune réception trouvée.');
    }

    return view('reception_details_multiple', ['data' => $allDetails]);
}




    public function genererPDF(Request $request)
    {
        $articles = json_decode($request->input('articles'), true);
        $tickets = [];

        foreach ($articles as $article) {
            for ($i = 0; $i < $article['quantite']; $i++) {
                $tickets[] = [
                    'ArticleNo'   => $article['ArticleNo'],
                    'Description' => $article['Description'],
                    'Emplacement' => $article['Emplacement'],
                ];
            }
        }

        $pdf = Pdf::loadView('tickets_pdf', compact('tickets'));
        return $pdf->stream('tickets-reception.pdf');
    }



    public function printMultiple(Request $request)
{
    $receptions = json_decode($request->input('receptions'), true);
    $allLines = [];

    foreach ($receptions as $numReception) {
        $response = $this->businessCentralService->GetPostedPurchaseReceiptLines($numReception);
        if ($response && $response['success']) {
            $tickets[] = [
                'NumReception' => $numReception,
                'Lignes' => $response['data']
            ];
        }
    }

    if (empty($tickets)) {
        return redirect()->back()->with('error', 'Aucune réception valide à imprimer.');
    }
// dd($tickets);
    $pdf = Pdf::loadView('tickets_multiple', compact('tickets'));
    return $pdf->stream('tickets-reception.pdf');
}




}
