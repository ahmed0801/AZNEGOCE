<?php

namespace App\Http\Controllers;

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
    }
    
    public function callBot(Request $request)
    {
        $question = $request->input('chat-form');
        $response = Http::post($this->url, [
            'question' => $question
        ]);
        return response()->json($response->json());
    }
}