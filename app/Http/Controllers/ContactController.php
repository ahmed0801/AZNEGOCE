<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'numclient' => 'required|string',
            'nomclient' => 'required|string',
            'sujet' => 'required|string',
            'message' => 'required|string',
        ]);

        Contact::create($validated);

        return redirect()->back()->with('success', 'Votre message a été envoyé avec succès.');
    }
}
