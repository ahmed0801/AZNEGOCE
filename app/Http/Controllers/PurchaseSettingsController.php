<?php

namespace App\Http\Controllers;

use App\Models\ParametresAchat;
use Illuminate\Http\Request;

class PurchaseSettingsController extends Controller
{
    public function index()
    {
        $parametres = ParametresAchat::first() ?? new ParametresAchat([
            'reception_obligatoire_validation' => 0,
            'reception_obligatoire_retour' => 1,
        ]);
        return view('paramachat', compact('parametres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reception_obligatoire_validation' => 'nullable|boolean',
            'reception_obligatoire_retour' => 'nullable|boolean',
        ]);

        $parametres = ParametresAchat::first() ?? new ParametresAchat();

        $parametres->update([
            'reception_obligatoire_validation' => $request->input('reception_obligatoire_validation', 0),
            'reception_obligatoire_retour' => $request->input('reception_obligatoire_retour', 0),
        ]);

        return redirect("/setting")->with('success', 'Paramètres des achats mis à jour avec succès.');
    }
}