<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\B2bSetting;
use App\Models\B2bUser;
use App\Models\CustomerB2bSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class PortalAuthController extends Controller
{
    public function showLogin()
    {
        $s = B2bSetting::current();
        if (!$s->portal_enabled) {
            return response()->view('portal.off', ['s' => $s, 'message' => $s->maintenance_message], 503);
        }
        if (session('portal_user_id')) return redirect()->route($s->homeRoute());
        return view('portal.login', ['s' => $s]);
    }

    public function login(Request $request)
    {
        $s = B2bSetting::current();
        if (!$s->portal_enabled) {
            return response()->view('portal.off', ['s' => $s, 'message' => $s->maintenance_message], 503);
        }
        $data = $request->validate(['email' => 'required|string|max:150', 'password' => 'required|string'], [
            'email.required' => "Saisissez votre e-mail ou nom d'utilisateur.",
            'password.required' => 'Saisissez votre mot de passe.',
        ]);

        // le champ « email » accepte l'adresse e-mail OU le nom d'utilisateur
        $email = strtolower(trim($data['email']));

        // verrouillage : 5 échecs sur la même adresse => 15 minutes de pause (en plus de la limite par IP)
        $key = 'portal-login|' . $email;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $min = max(1, (int) ceil(RateLimiter::availableIn($key) / 60));
            return back()->withInput($request->only('email'))->withErrors(['email' => "Trop de tentatives. Réessayez dans $min minute" . ($min > 1 ? 's' : '') . ' ou contactez-nous.']);
        }

        $user = B2bUser::with('customer')->where(function ($q) use ($email) {
            $q->where('email', $email)->orWhere('username', $email);
        })->first();
        // même durée de calcul et même message que l'e-mail existe ou non (pas de fuite d'information)
        $hash = $user ? $user->password : '$2y$10$Kx5M7a.dmEc/AdS1nA/jrOuaEOXH00yeKnbCXO1HicrWxpFlEqzJm';
        $good = Hash::check($data['password'], $hash);
        if (!$user || !$good) {
            RateLimiter::hit($key, 900);
            Log::channel('single')->warning('Portail B2B : échec de connexion', ['email' => $email, 'ip' => $request->ip()]);
            return back()->withInput($request->only('email'))->withErrors(['email' => 'Identifiant ou mot de passe incorrect.']);
        }

        $customer = $user->customer;
        $cs = $customer ? CustomerB2bSetting::forCustomer($customer->id) : null;
        $err = null;
        if (!$customer) $err = 'Compte client introuvable. Contactez-nous.';
        elseif (!$user->isActive()) $err = 'Votre accès est suspendu. Contactez-nous pour le réactiver.';
        elseif (!$cs->portal_enabled) $err = "L'accès au portail est désactivé pour votre compte. Contactez-nous.";
        elseif ($customer->type === 'particulier' && !$s->allow_particulier) $err = 'Le portail est réservé aux professionnels. Contactez-nous.';
        if ($err) return back()->withInput($request->only('email'))->withErrors(['email' => $err]);

        RateLimiter::clear($key);
        $request->session()->regenerate();            // protège contre la fixation de session
        session(['portal_user_id' => $user->id]);
        $user->forceFill(['last_login_at' => now(), 'reset_requested_at' => null])->save();
        Log::channel('single')->info('Portail B2B : connexion', ['user' => $user->id, 'customer' => $user->customer_id, 'ip' => $request->ip()]);

        // jamais de redirection vers une adresse mémorisée (qui pourrait venir de l'ERP)
        return redirect()->route(B2bSetting::current()->homeRoute());
    }

    public function logout(Request $request)
    {
        $request->session()->forget('portal_user_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('portal.login')->with('status', 'Vous êtes déconnecté.');
    }

    public function showPassword(Request $request)
    {
        return view('portal.password', [
            's'      => $request->attributes->get('portal_settings'),
            'pUser'  => $request->attributes->get('portal_user'),
            'forced' => (bool) $request->attributes->get('portal_user')->must_change_password,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->attributes->get('portal_user');
        $data = $request->validate([
            'current_password' => 'required|string',
            'password'         => ['required', 'string', 'min:8', 'confirmed', 'regex:/[A-Za-z]/', 'regex:/[0-9]/'],
        ], [
            'current_password.required' => 'Saisissez votre mot de passe actuel.',
            'password.required'  => 'Choisissez un nouveau mot de passe.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les deux mots de passe ne sont pas identiques.',
            'password.regex'     => 'Le mot de passe doit contenir au moins une lettre et un chiffre.',
        ]);

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }
        if (Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['password' => 'Choisissez un mot de passe différent de l\'actuel.']);
        }

        $user->forceFill(['password' => Hash::make($data['password']), 'must_change_password' => false])->save();
        $request->session()->regenerate();
        session(['portal_user_id' => $user->id]);

        return redirect()->route(B2bSetting::current()->homeRoute())->with('status', 'Mot de passe modifié.');
    }

    public function showForgot()
    {
        return view('portal.forgot', ['s' => B2bSetting::current(), 'sent' => false]);
    }

    /** Pas d'e-mail automatique : la demande apparaît dans la fiche client, un administrateur réinitialise. */
    public function forgot(Request $request)
    {
        $data = $request->validate(['email' => 'required|string|max:150'], ['email.required' => "Saisissez votre e-mail ou nom d'utilisateur."]);
        $id = strtolower(trim($data['email']));
        $user = B2bUser::where('email', $id)->orWhere('username', $id)->first();
        if ($user) {
            $user->forceFill(['reset_requested_at' => now()])->save();
        }
        // réponse identique que le compte existe ou non
        return view('portal.forgot', ['s' => B2bSetting::current(), 'sent' => true]);
    }
}
