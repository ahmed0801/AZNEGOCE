<?php

namespace App\Http\Controllers;

use App\Models\AccessRequest;
use App\Models\Arrivage;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{


    public function storeArrivage(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload de l'image
        $imagePath = $request->file('image')->store('arrivages', 'public');

        // Enregistrement en base
        Arrivage::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'image' => $imagePath,
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
        'password' => 'required|string|min:6',
    ]);

    // Sauvegarder la demande
    AccessRequest::create([
        'client_name' => $request->clientName,
        'requester_name' => $request->requesterName,
        'whatsapp_number' => $request->whatsappNumber,
        'password' => $request->password, // Enregistrer en crypté
    ]);

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
    // Validation
    $request->validate([
        'numclient' => 'nullable|string',
    ]);

  
    // Mise à jour de la demande
    $accessRequest = AccessRequest::findOrFail($id);
    $accessRequest->numclient = $request->numclient;
    $accessRequest->save();

    return redirect()->route('admin.demandeAcces')->with('success', 'Demande mise à jour avec succès.');
}




}
