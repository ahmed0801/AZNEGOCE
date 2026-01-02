<?php
namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SingleSession
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $currentSessionId = session()->getId();
            $userSessionId = Auth::user()->session_id;

            if ($userSessionId && $userSessionId !== $currentSessionId) {
                // Supprimer l'ancienne session
                DB::table('sessions')->where('id', $userSessionId)->delete();

                // Mettre à jour la session actuelle
                Auth::user()->session_id = $currentSessionId;
                Auth::user()->save();

                // ⚠️ Stocker le message AVANT le logout dans session temporaire
                Session::flash('session_error', 'Votre compte a été ouvert sur un autre appareil. L’ancienne session a été fermée.');

                // Logout
                Auth::logout();

                // Rediriger vers login
                return redirect()->route('login.form');
            }
        }

        return $next($request);
    }
}
