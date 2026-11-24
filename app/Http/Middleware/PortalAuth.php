<?php

namespace App\Http\Middleware;

use App\Models\B2bSetting;
use App\Models\B2bUser;
use App\Models\CustomerB2bSetting;
use Closure;

/**
 * Protège toutes les pages du portail B2B (session indépendante de l'ERP : clé « portal_user_id »).
 * Vérifie à CHAQUE requête que l'accès est toujours valide (suspendu / désactivé depuis l'ERP = coupé immédiatement).
 */
class PortalAuth
{
    public function handle($request, Closure $next)
    {
        $s = B2bSetting::current();
        if (!$s->portal_enabled) {
            return response()->view('portal.off', ['s' => $s, 'message' => $s->maintenance_message], 503);
        }

        $id = session('portal_user_id');
        $user = $id ? B2bUser::with(['customer.discountGroup', 'customer.tvaGroup'])->find($id) : null;
        $customer = $user ? $user->customer : null;
        if (!$user || !$customer) {
            session()->forget('portal_user_id');
            return redirect()->route('portal.login');
        }

        $cs = CustomerB2bSetting::forCustomer($customer->id);
        $reason = null;
        if (!$user->isActive()) $reason = 'Votre accès est suspendu. Contactez-nous pour le réactiver.';
        elseif (!$cs->portal_enabled) $reason = "L'accès au portail est désactivé pour votre compte. Contactez-nous.";
        elseif ($customer->type === 'particulier' && !$s->allow_particulier) $reason = "Le portail est réservé aux professionnels. Contactez-nous.";
        if ($reason) {
            session()->forget('portal_user_id');
            return redirect()->route('portal.login')->withErrors(['email' => $reason]);
        }

        if ($user->must_change_password && !$request->routeIs('portal.password', 'portal.password.update', 'portal.logout')) {
            return redirect()->route('portal.password');
        }

        $request->attributes->set('portal_user', $user);
        $request->attributes->set('portal_customer_settings', $cs);
        $request->attributes->set('portal_settings', $s);

        $response = $next($request);
        // les pages du portail contiennent des prix personnalisés : jamais de cache partagé
        if (method_exists($response, 'header')) {
            $response->header('Cache-Control', 'private, no-store');
        }
        return $response;
    }
}
