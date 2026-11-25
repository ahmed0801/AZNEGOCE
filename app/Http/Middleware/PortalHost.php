<?php

namespace App\Http\Middleware;

use Closure;

/**
 * MIDDLEWARE GLOBAL (à ajouter en premier dans $middleware de app/Http/Kernel.php).
 *
 * Sur le sous-domaine du portail :
 *  - SEULES les pages du portail existent (liste blanche) : toute autre adresse — connexion ERP, /api, /admin,
 *    /customers, /sales… — répond 404, comme si l'ERP n'était pas installé sur ce domaine ;
 *  - la session utilise un cookie dédié (jamais celui de l'ERP) et sans domaine partagé.
 *
 * Sans PORTAL_DOMAIN (développement), ne fait rien.
 */
class PortalHost
{
    const ALLOWED = '#^/(connexion|deconnexion|mot-de-passe|mot-de-passe-oublie|catalogue|promotions|suggestions|panier|panier/(ajouter|modifier|vider)|liste-references|commande|commandes|commandes/[0-9]+|commandes/[0-9]+/(annuler|recommander)|compte|compte/factures|compte/factures/[0-9]+|compte/factures/[0-9]+/pdf|compte/bons-livraison|compte/bons-livraison/[0-9]+|compte/bons-livraison/[0-9]+/pdf|devis|devis/nouveau|devis/[0-9]+|devis/[0-9]+/(accepter|refuser|annuler|pdf|renegocier)|devis/erp/[0-9]+/pdf|devis/plaque|devis/[0-9]+/fichiers|devis/[0-9]+/fichiers/[0-9]+|favoris/[0-9]+|article/[0-9]+|article/[0-9]+/equivalents)?$#D';

    public function handle($request, Closure $next)
    {
        $domain = strtolower((string) config('portal.domain'));
        if ($domain !== '' && strtolower($request->getHost()) === $domain) {
            // cookie de session propre au portail, limité à ce seul sous-domaine
            config([
                'session.cookie' => config('portal.session_cookie', 'az_portal_session'),
                'session.domain' => null,
            ]);

            $path = '/' . trim($request->path(), '/');
            if ($path === '//') $path = '/';
            if (!preg_match(self::ALLOWED, $path)) {
                abort(404);
            }
        }
        return $next($request);
    }
}
