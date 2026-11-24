<?php

namespace App\Http\Middleware;

use Closure;

/** En-têtes de sécurité sur toutes les pages du portail (publiques et connectées). */
class PortalHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        if (!method_exists($response, 'header')) return $response;

        $response->header('X-Frame-Options', 'DENY');                       // pas d'affichage dans un cadre (clickjacking)
        $response->header('X-Content-Type-Options', 'nosniff');
        $response->header('Referrer-Policy', 'same-origin');
        $response->header('Permissions-Policy', 'camera=(self), microphone=(), geolocation=(), payment=()');
        $response->header('X-Robots-Tag', 'noindex, nofollow');
        $response->header('Cache-Control', 'private, no-store, max-age=0');
        $response->header('Content-Security-Policy',
            "default-src 'self'; img-src 'self' data: https:; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; script-src 'self' 'unsafe-inline'; "
            . "connect-src 'self'; font-src 'self' data: https://fonts.gstatic.com; media-src 'self' blob:; frame-ancestors 'none'; form-action 'self'; base-uri 'self'; object-src 'none'");
        if ($request->isSecure()) {
            $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }
        return $response;
    }
}
