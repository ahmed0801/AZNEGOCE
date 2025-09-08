<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $client = $request->input('client');
        $name = $request->input('name');
        $date = $request->input('date');
    
        $query = Log::query();
    
        if ($client) {
            $query->where('CustomerNo', 'LIKE', "%$client%");
        }
    
        if ($name) {
            $query->where('CustomerName', 'LIKE', "%$name%");
        }
    
        if ($date) {
            $query->whereDate('login_date', $date);
        }
    
        $logs = $query->latest('login_date')->limit(100)->get();
    
        return view('logs.log', compact('logs'));
    }
    
}
