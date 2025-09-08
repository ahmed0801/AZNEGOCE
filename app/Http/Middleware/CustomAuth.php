<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est authentifié
        if (!session('user')) {
            // Si l'utilisateur n'est pas authentifié, rediriger vers la page de connexion
            return redirect()->route('login');
        }

        // Si l'utilisateur est authentifié, poursuivre la requête
        return $next($request);
    }
}

