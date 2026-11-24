<?php

use App\Http\Controllers\VoiceCommandController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrintAgentController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/voice-command', [VoiceCommandController::class, 'handle']);

// ════════════════════════════════════════════════════════════════
// ROUTES TOURNÉE
// ════════════════════════════════════════════════════════════════

// Test de connexion
Route::get('/tournee/chauffeurs', function (Request $request) {
    $apiKey = $request->header('X-API-KEY');
    if (!$apiKey || $apiKey !== config('services.tournee.key')) {
        return response()->json(['error' => 'Non autorisé'], 401);
    }
    return response()->json([
        ['id' => 0, 'name' => config('app.name') . ' — API OK', 'phone' => null]
    ]);
});

// Liste fournisseurs — utilise phone1 car la table suppliers n'a pas de champ phone
Route::get('/tournee/fournisseurs-list', function (Request $request) {
    $apiKey = $request->header('X-API-KEY');
    if (!$apiKey || $apiKey !== config('services.tournee.key')) {
        return response()->json(['error' => 'Non autorisé'], 401);
    }

    $fournisseurs = \App\Models\Supplier::select('id', 'name', 'address', 'city', 'phone1')
        ->orderBy('name')
        ->get()
        ->map(function ($s) {
            return [
                'id'      => $s->id,
                'name'    => $s->name,
                'address' => $s->address ?? null,
                'city'    => $s->city    ?? null,
                'phone'   => $s->phone1  ?? null,
            ];
        });

    return response()->json($fournisseurs);
});

// Mise à jour barcode
Route::post('/articles/update-barcode', function (Request $request) {
    $apiKey = $request->header('X-API-KEY');
    if (!$apiKey || $apiKey !== config('services.tournee.key')) {
        return response()->json(['error' => 'Non autorisé'], 401);
    }

    $articleCode = $request->input('article_code');
    $barcode     = $request->input('barcode');

    if (!$articleCode || !$barcode) {
        return response()->json(['error' => 'article_code et barcode requis'], 422);
    }

    $item = \App\Models\Item::where('code', $articleCode)->first();
    if (!$item) {
        return response()->json(['error' => 'Article non trouvé'], 404);
    }

    $item->update(['barcode' => $barcode]);

    return response()->json(['success' => true]);
});



// ── Agent d'impression : à placer dans routes/api.php (préfixe /api automatique, sans CSRF ni session) ──
// + en haut du fichier :  use App\Http\Controllers\PrintAgentController;
Route::get('/print-agent/next', [PrintAgentController::class, 'next']);
Route::post('/print-agent/{id}/ack', [PrintAgentController::class, 'ack'])->whereNumber('id');
Route::get('/print-agent/ping', [PrintAgentController::class, 'ping']);

