<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Ajoute automatiquement le script d'alertes « Commandes portail » à TOUTES les pages HTML de l'ERP
 * (dashboard, ventes, clients…) pour les utilisateurs connectés, sans modifier chaque page.
 */
class InjectPortalOrdersNotifier
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        try {
            $pd = strtolower((string) config('portal.domain'));
            if ($pd !== '' && strtolower($request->getHost()) === $pd) return $response;
            if (!Auth::check() || $request->ajax() || $request->expectsJson() || !$request->isMethod('GET')) return $response;
            if (!method_exists($response, 'getContent') || $response instanceof \Symfony\Component\HttpFoundation\StreamedResponse || $response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse) return $response;
            if ($response->getStatusCode() !== 200) return $response;
            $ct = (string) $response->headers->get('Content-Type');
            if (stripos($ct, 'text/html') === false) return $response;
            $path = $request->path();
            if (preg_match('#(^|/)(print|export|pdf|portail)(/|$)|^api/#i', $path)) return $response;   // impressions, exports, API

            $html = $response->getContent();
            if (!is_string($html) || stripos($html, '</body>') === false || strpos($html, 'portal-orders-notify.js') !== false) return $response;

            $tag = '<script charset="utf-8" src="' . e(asset('assets/js/portal-orders-notify.js')) . '?v=2" defer data-feed="' . e(route('b2b.orders.count')) . '" data-page="' . e(route('b2b.orders')) . '"></script>';
            $pos = strripos($html, '</body>');
            $response->setContent(substr($html, 0, $pos) . $tag . substr($html, $pos));
        } catch (\Throwable $e) { /* jamais bloquant */ }

        return $response;
    }
}
