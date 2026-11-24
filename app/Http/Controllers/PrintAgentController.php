<?php

namespace App\Http\Controllers;

use App\Models\B2bSetting;
use App\Models\PrintJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * API appelée par l'agent d'impression installé au magasin.
 * Authentification : en-tête  Authorization: Bearer <jeton>  (jeton affiché dans Portail B2B > Impression).
 * L'agent n'ouvre aucun port : c'est lui qui interroge le serveur.
 */
class PrintAgentController extends Controller
{
    private function authorizeAgent(Request $request)
    {
        $s = B2bSetting::current();
        $token = $request->bearerToken() ?: $request->header('X-Agent-Token');
        if (!$s->agent_token || !$token || !hash_equals($s->agent_token, (string) $token)) {
            abort(401, 'Jeton invalide');
        }
        $s->forceFill(['agent_last_seen' => now()])->saveQuietly();
    }

    /** Prochain ticket à imprimer (ou 204 s'il n'y en a pas). */
    public function next(Request $request)
    {
        $this->authorizeAgent($request);

        // un ticket resté « en cours » plus de 2 minutes (agent arrêté) est remis en file
        PrintJob::where('status', 'printing')
            ->where('claimed_at', '<', now()->subMinutes(2))
            ->update(['status' => 'pending']);

        // trop d'échecs : on abandonne
        PrintJob::where('status', 'pending')->where('attempts', '>=', PrintJob::MAX_ATTEMPTS)
            ->update(['status' => 'failed', 'error' => 'Abandonné après plusieurs tentatives']);

        $job = DB::transaction(function () {
            $j = PrintJob::where('status', 'pending')->orderBy('id')->lockForUpdate()->first();
            if ($j) {
                $j->status = 'printing';
                $j->attempts = $j->attempts + 1;
                $j->claimed_at = now();
                $j->save();
            }
            return $j;
        });

        if (!$job) return response()->noContent();

        return response()->json([
            'id'      => $job->id,
            'ip'      => $job->printer_ip,
            'port'    => (int) $job->printer_port,
            'copies'  => (int) $job->copies,
            'payload' => $job->payload,   // base64
        ]);
    }

    /** Résultat de l'impression. */
    public function ack(Request $request, $id)
    {
        $this->authorizeAgent($request);
        $data = $request->validate(['ok' => 'required|boolean', 'error' => 'nullable|string|max:255']);

        $job = PrintJob::findOrFail($id);
        if ($data['ok']) {
            $job->update(['status' => 'done', 'printed_at' => now(), 'error' => null]);
        } else {
            // on remet en file tant qu'il reste des tentatives
            $job->update([
                'status' => $job->attempts >= PrintJob::MAX_ATTEMPTS ? 'failed' : 'pending',
                'error'  => isset($data['error']) ? $data['error'] : 'Erreur inconnue',
            ]);
        }
        return response()->json(['ok' => true]);
    }

    /** Simple signal de vie (l'agent l'appelle aussi à chaque interrogation). */
    public function ping(Request $request)
    {
        $this->authorizeAgent($request);
        return response()->json(['ok' => true, 'time' => now()->toIso8601String()]);
    }
}
