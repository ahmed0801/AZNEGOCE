<?php

namespace App\Http\Controllers;

use App\Mail\SupportMail;
use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    public function index()
    {
                $tickets = Ticket::latest()->paginate(10);

        return view('contact', compact('tickets'));
    }

public function send(Request $request)
    {

                $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:1000',
        ]);

        // Sauvegarde du ticket
        $ticket = Ticket::create($request->only('name', 'email', 'subject', 'message'));

        
        $data = [
            'name'    => $request->input('name'),
            'email'   => $request->input('email'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
        ];

        Mail::to('ahmedarfaoui1600@gmail.com')->send(new SupportMail($data));

        return redirect()->back()->with('success', 'Votre ticket a été envoyé avec succès !');
    }


    // Back-office : liste des tickets
    public function list()
    {
        $tickets = Ticket::latest()->paginate(10);
        return view('tickets.list', compact('tickets'));
    }

    // Changer statut
    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = $request->status;
        $ticket->save();

        return redirect()->back()->with('success', 'Statut mis à jour avec succès.');
    }
}
