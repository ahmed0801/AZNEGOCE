<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function selectionner(Request $request)
    {
        session(['selectedClient' => $request->client]);
        return response()->json(['success' => true, 'selectedClient' => $request->client]);
    }

}
