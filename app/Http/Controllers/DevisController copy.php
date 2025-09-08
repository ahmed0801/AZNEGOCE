<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entetepanier;
use App\Models\Panier;
use PDF; 



class DevisController extends Controller
{
    public function index()
    {
        $entetedevis = Entetepanier::orderBy('created_at', 'desc')->take(100)->get();
        return view('listdevis', compact('entetedevis'));
    }

    public function show($id)
    {
        // On récupère l'entête du devis
        $entetedevis = Entetepanier::findOrFail($id);
        // dd($entetedevis);
        // On récupère les lignes du panier liées à ce devis
        $details = Panier::where('entetepanier_id', $id)->get();

        return view('devis_details', compact('entetedevis', 'details'));
    }

    public function exportPdf($id)
{
    $entetedevis = Entetepanier::findOrFail($id);
    $details = Panier::where('entetepanier_id', $id)->get();

    $pdf = PDF::loadView('pdf.devis', compact('entetedevis', 'details'));
    return $pdf->stream('devis_'.$id.'.pdf');
}
public function exportPdfsansref($id)
{
    $entetedevis = Entetepanier::findOrFail($id);
    $details = Panier::where('entetepanier_id', $id)->get();

    $pdf = PDF::loadView('pdf.devissansref', compact('entetedevis', 'details'));
    return $pdf->stream('devis_'.$id.'.pdf');
}

public function exportPdfsansremise($id)
{
    $entetedevis = Entetepanier::findOrFail($id);
    $details = Panier::where('entetepanier_id', $id)->get();

    $pdf = PDF::loadView('pdf.devissansremise', compact('entetedevis', 'details'));
    return $pdf->stream('devis_'.$id.'.pdf');
}

public function exportPdfsans2($id)
{
    $entetedevis = Entetepanier::findOrFail($id);
    $details = Panier::where('entetepanier_id', $id)->get();

    $pdf = PDF::loadView('pdf.devissans2', compact('entetedevis', 'details'));
    return $pdf->stream('devis_'.$id.'.pdf');
}



public function loadPanier($id)
{
    // Supprimer l'ancien panier en session
    session()->forget('panier');
    session()->forget('selectedClient');


    // Récupérer les lignes panier du devis $id
    $items = Panier::where('entetepanier_id', $id)->get();
// dd($items);
    // Formater les données comme dans ta session exemple
    $panier = [];

    foreach ($items as $item) {
        $panier[$item->item_reference] = [
            'article' => [
                'ItemNo' => $item->item_reference,
                'Desc' => $item->item_name,
            ],
            'PrixVenteUnitaire' => $item->price,
            'quantite' => $item->quantity,
            'remise' => $item->remise ?? 0,
        ];
        
    }

    // Charger en session
    session(['panier' => $panier]);

    // Rediriger vers la page panier (ou autre page souhaitée)
    return redirect("/commande")->with('success', 'Panier chargé depuis le devis.');
}




}
