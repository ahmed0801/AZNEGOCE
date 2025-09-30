<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BotController extends Controller
{
    private $url;
    
    public function __construct()
    {
        $host = env('AI_HOST');
        $port = env('AI_PORT');
        $route = env('AI_ROUTE');
        $this->url = "{$host}:{$port}{$route}";
        $this->url = "http://51.68.230.52:8010/predict";
    }
    
    public function callBot(Request $request)
    {
        $question = $request->input('question');

        $response = Http::post($this->url, [
            'question' => $question,
            'session_id' => Auth::user()->id
        ]);
        return response()->json($response->json());
    }
}