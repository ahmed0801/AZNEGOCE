<?php

namespace App\Http\Controllers;

use App\Mail\AccessRequestNotification;
use App\Models\AccessRequest;
use App\Models\Arrivage;
use App\Models\Notification;
use App\Services\BusinessCentralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{

    public function create(Request $request)
    {
        $request->validate([
            'customerName' => 'required|string|max:255',
            'customerPostingGroup' => 'required|string',
            'paymentTermsCode' => 'required|string',
            'countryRegionCode' => 'required|string',
            'genBusPostingGroup' => 'required|string',
            'grpComptaTVAClt' => 'required|string',
            'matFiscale' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
            'phoneNo' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:100',
        ]);
    
        $data = $request->only([
            'customerName',
            'customerPostingGroup',
            'paymentTermsCode',
            'countryRegionCode',
            'genBusPostingGroup',
            'grpComptaTVAClt',
            'matFiscale',
            'city',
            'phoneNo',
            'adresse',
        ]);
        // dd($data);
    
        $result = app(\App\Services\BusinessCentralService::class)->createCustomer($data);
        session()->forget('clients');

        if ($result['success']) {
            return redirect("/commande")->with('success', 'Client Crée Avec Succés');
        } else {
            return redirect("/commande")->with('error', 'erreur dans la création du client');
        }
    }



    

    public function storeArrivage(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vendor1' => 'required|string',
            'vendor2' => 'nullable|string',
            'vendor3' => 'nullable|string',
        ]);

        // Upload de l'image
        $imagePath = $request->file('image')->store('arrivages', 'public');

         // Vérifiez qu'au moins un fournisseur est sélectionné
    if (empty($request->vendor1) && empty($request->vendor2) && empty($request->vendor3)) {
        return back()->withErrors(['fournisseur' => 'Veuillez sélectionner au moins un fournisseur.']);
    }

        // Enregistrement en base
        Arrivage::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $imagePath,
            'fournisseur1' => $request->input('vendor1'),
            'fournisseur2' => $request->input('vendor2'),
            'fournisseur3' => $request->input('vendor3'),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Arrivage créé avec succès.');
    }


    public function destroy($id)
{
    try {
        // Récupérer l'arrivage
        $arrivage = Arrivage::findOrFail($id);

        // Supprimer l'image du stockage
        if ($arrivage->image && Storage::exists($arrivage->image)) {
            Storage::delete($arrivage->image);
        }

        // Supprimer l'arrivage
        $arrivage->delete();

        // Redirection avec un message de succès
        return redirect()->route('admin.dashboard')->with('success', 'Arrivage supprimé avec succès.');
    } catch (\Exception $e) {
        return redirect()->route('admin.dashboard')->with('error', 'Erreur lors de la suppression de l\'arrivage.');
    }
}


public function storeNotification(Request $request)
{
    // Validation des données
    $request->validate([
        'notif' => 'required|string|max:255',
    ]);

    // Création de la notification
    Notification::create([
        'notif' => $request->notif,
    ]);

    return redirect()->route('admin.dashboard')->with('success', 'Notification créée avec succès.');
}


public function deleteNotification($id)
{
    $notification = Notification::findOrFail($id);
    $notification->delete();

    return redirect()->route('admin.dashboard')->with('success', 'Notification supprimée avec succès.');
}





public function storeAccessRequest(Request $request)
{
    $request->validate([
        'clientName' => 'required|string|max:255',
        'requesterName' => 'required|string|max:255',
        'whatsappNumber' => 'required|string|regex:/^\d{8}$/',
        'isClient' => 'nullable|string|in:oui,non', // Validation du champ isClient
    ]);

    // Sauvegarder la demande
    AccessRequest::create([
        'client_name' => $request->clientName,
        'requester_name' => $request->requesterName,
        'whatsapp_number' => $request->whatsappNumber,
        'is_client' => $request->isClient ?? 'non', // Définir la valeur par défaut sur "non"
    ]);

    // Préparer et envoyer l'email
    $emailData = [
        'clientName' => $request->clientName,
        'requesterName' => $request->requesterName,
        'whatsappNumber' => $request->whatsappNumber,
        'isClient' => $request->isClient ?? 'non',
    ];

        // Liste des destinataires
        $recipients = ['premagros@gmail.com', 'support.it@gmail.com', 'ahmed.arfaoui@premagros.com'];

        \Mail::to($recipients)->send(new AccessRequestNotification($emailData)); 



    return back()->with('success', 'Votre demande a été soumise avec succès. Nous vous contacterons sous peu.');
}





 // Afficher les demandes d'accès
 public function demandeAcces()
 {
    $requests = AccessRequest::latest('id')->whereIn('status', ['pending', 'approved', 'rejected'])->get();
    return view('admin.demandeAcces', compact('requests'));
 }

 // Approuver une demande
 public function approuverDemande($id)
 {
     $request = AccessRequest::findOrFail($id);
     $request->status = 'approved';
     $request->save();

     return redirect()->route('admin.demandeAcces')->with('success', 'Demande approuvée avec succès.');
 }

 // Rejeter une demande
 public function rejeterDemande($id)
 {
     $request = AccessRequest::findOrFail($id);
     $request->status = 'rejected';
     $request->save();

     return redirect()->route('admin.demandeAcces')->with('success', 'Demande rejetée avec succès.');
 }

 public function mettreAJourDemande(Request $request, $id)
{
    // Validation des champs modifiables
    $request->validate([
        'client_name' => 'nullable|string',
        'requester_name' => 'nullable|string',
        'whatsapp_number' => 'nullable|string',
        'numclient' => 'nullable|string',
    ]);

    // Récupération et mise à jour de la demande
    $accessRequest = AccessRequest::findOrFail($id);
    $accessRequest->client_name = $request->client_name;
    $accessRequest->requester_name = $request->requester_name;
    $accessRequest->whatsapp_number = $request->whatsapp_number;
    $accessRequest->numclient = $request->numclient;
    $accessRequest->save();

    return redirect()->route('admin.demandeAcces')->with('success', 'Demande mise à jour avec succès.');
}




public function arrivage(Request $request)
{
    $itemFilter = '';
    $descriptionFilter = '';
    $originReferenceFilter = '';
    $items = [];

    // Récupérer les fournisseurs depuis la session ou la requête
    $vendors = session('vendors');
    if (!$vendors) {
        // Si les fournisseurs ne sont pas dans la session, appeler le service
        $businessCentralService = new BusinessCentralService();
        $vendors = $businessCentralService->getAllVendors();
        // Stocker les fournisseurs dans la session pour les prochaines requêtes
        session(['vendors' => $vendors]);
    }

    // Récupérer les clients depuis la session
    $clients = session('clients');
    if (!$clients) {
        // Si les clients ne sont pas dans la session, appeler le service
        $clients = $businessCentralService->CustomerList() ?? [];
        // Stocker les clients dans la session pour les prochaines requêtes
        session(['clients' => $clients]);
    }

    // Récupérer les fournisseurs depuis la requête (si présents)
    $vendor1 = $request->input('vendor1');
    $vendor2 = $request->input('vendor2');
    $vendor3 = $request->input('vendor3');

    // Si un fournisseur n'est pas sélectionné, utiliser la valeur par défaut
    $vendor1 = $vendor1 ?? $lastArrivage->fournisseur1 ?? 'FR00000000';
    $vendor2 = $vendor2 ?? $lastArrivage->fournisseur2 ?? 'FR00000000';
    $vendor3 = $vendor3 ?? $lastArrivage->fournisseur3 ?? 'FR00000000';

    // Appel au service pour récupérer les articles
        // Appel au service pour récupérer les articles
        $businessCentralService = new BusinessCentralService();
    $allItems = $businessCentralService->getItemsByVendor($vendor1, $vendor2, $vendor3);

    // Filtrer les articles avec un stock > 0
    $items = collect($allItems)
        ->sortBy('Desc') // Tri par ordre alphabétique
        ->values()->toArray();

    $selectedClient = session('selectedClient')['CustomerNo'] ?? '';

    $scrollTo = 'searchresultat';
    return view('commande', compact('vendors', 'items', 'itemFilter', 'descriptionFilter', 'originReferenceFilter', 'clients', 'selectedClient', 'scrollTo'));
}







public function showArrivage($id)
{
    $itemFilter = '';
    $descriptionFilter = '';
    $originReferenceFilter = '';
    $items = [];

    // Trouver l’arrivage sélectionné
    $arrivage = Arrivage::find($id);

    if (!$arrivage) {
        return redirect()->route('arrivage')->withErrors(['error' => 'Arrivage introuvable.']);
    }

    // Vérifier si les fournisseurs sont remplis et récupérer leurs articles
    $vendor1 = $arrivage->fournisseur1 ?? 'FR00000000';
    $vendor2 = $arrivage->fournisseur2 ?? 'FR00000000';
    $vendor3 = $arrivage->fournisseur3 ?? 'FR00000000';

    $businessCentralService = new BusinessCentralService();
    $allItems = $businessCentralService->getItemsByVendor($vendor1, $vendor2, $vendor3);

    // Filtrer les articles avec un stock > 0
    $items = collect($allItems)->filter(function ($item) {
        return isset($item['Stock']) && $item['Stock'] > 0;
    })->values()->toArray();
    $lastArrivage = $arrivage;
    $arrivages = Arrivage::latest()->take(3)->get(); // 3 derniers arrivages


    return view('lastarrivage', compact('lastArrivage','arrivage','arrivages', 'items', 'itemFilter', 'descriptionFilter', 'originReferenceFilter'));
}









}
