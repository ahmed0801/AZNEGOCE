<?php

namespace App\Http\Controllers;

use App\Models\B2bSetting;
use App\Models\B2bUser;
use App\Models\Customer;
use App\Models\CustomerB2bSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/** Onglet « Accès B2B » de la fiche client (réponses JSON, appelé en AJAX). */
class CustomerB2bController extends Controller
{
    private function guard()
    {
        if (Auth::user() && Auth::user()->role === 'vendeur') abort(403);
    }

    /** Mot de passe provisoire lisible (sans 0/O/1/l/I). */
    private function tempPassword()
    {
        $chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $out = '';
        for ($i = 0; $i < 10; $i++) $out .= $chars[random_int(0, strlen($chars) - 1)];
        return $out;
    }

    /** Nom d'utilisateur proposé automatiquement : « prenom.nom » sans accents, unique. */
    private function makeUsername($name)
    {
        $base = strtolower(\Illuminate\Support\Str::ascii($name));
        $base = trim(preg_replace('/[^a-z0-9]+/', '.', $base), '.');
        if (strlen($base) < 3) $base = 'client';
        $u = $base; $i = 1;
        while (B2bUser::where('username', $u)->exists()) { $i++; $u = $base . $i; }
        return substr($u, 0, 60);
    }

    private function userRow(B2bUser $u)
    {
        return [
            'id'         => $u->id,
            'name'       => $u->name,
            'email'      => $u->email,
            'username'   => $u->username,
            'phone'      => $u->phone,
            'status'     => $u->status,
            'must_change' => (bool) $u->must_change_password,
            'reset_requested' => $u->reset_requested_at ? $u->reset_requested_at->format('d/m/Y H:i') : null,
            'last_login' => $u->last_login_at ? $u->last_login_at->format('d/m/Y H:i') : null,
            'created'    => $u->created_at ? $u->created_at->format('d/m/Y') : null,
        ];
    }

    private function state(Customer $customer)
    {
        $g = B2bSetting::current();
        $cs = CustomerB2bSetting::forCustomer($customer->id);
        $users = B2bUser::where('customer_id', $customer->id)->orderBy('id')->get();
        $group = $customer->discountGroup;

        $warnings = [];
        if (!$g->portal_enabled) {
            $warnings[] = ['level' => 'info', 'text' => "Le portail B2B est désactivé globalement (Paramètres > Portail B2B) : aucun client ne peut se connecter pour l'instant."];
        }
        if ($customer->type === 'particulier' && !$g->allow_particulier) {
            $warnings[] = ['level' => 'warn', 'text' => "Ce client est de type « Particulier » et le portail est réservé aux jobbers/professionnels : il ne pourra pas se connecter. Changez son type ou autorisez les particuliers dans les paramètres du portail."];
        }
        if ($customer->blocked) {
            $warnings[] = ['level' => 'warn', 'text' => "Ce client est bloqué : ses commandes du portail seront refusées."];
        }
        if (!$group) {
            $warnings[] = ['level' => 'info', 'text' => "Aucun groupe de remise client : il verra les tarifs sans remise de groupe."];
        }

        return [
            'ok'       => true,
            'portal_url' => route('portal.login'),
            'customer' => ['id' => $customer->id, 'name' => $customer->name, 'email' => $customer->email, 'type' => $customer->type],
            'group'    => $group ? ['name' => $group->name, 'rate' => (float) $group->discount_rate] : null,
            'global'   => ['auto_validate' => (bool) $g->auto_validate, 'portal_enabled' => (bool) $g->portal_enabled],
            'settings' => [
                'portal_enabled'  => (bool) $cs->portal_enabled,
                'can_order'       => (bool) $cs->can_order,
                'validation_mode' => $cs->validation_mode,
                'show_prices'     => (bool) $cs->show_prices,
                'show_finance'    => (bool) $cs->show_finance,
                'fulfillment'     => $cs->fulfillment ?: 'pickup',
                'note'            => $cs->note,
            ],
            'users'    => $users->map(function ($u) { return $this->userRow($u); })->values(),
            'warnings' => $warnings,
        ];
    }

    public function show(Customer $customer)
    {
        $this->guard();
        return response()->json($this->state($customer));
    }

    public function saveSettings(Request $request, Customer $customer)
    {
        $this->guard();
        $v = $request->validate([
            'validation_mode' => 'required|in:inherit,manual,auto',
            'fulfillment'     => 'required|in:delivery,pickup,both',
            'note'            => 'nullable|string|max:1000',
        ]);
        CustomerB2bSetting::forCustomer($customer->id);   // s'assure que la ligne existe
        // écriture directe en base (sans passer par le modèle) puis relecture : on ne dit « enregistré » que si c'est vrai
        $data = [
            'portal_enabled'  => $request->boolean('portal_enabled') ? 1 : 0,
            'can_order'       => $request->boolean('can_order') ? 1 : 0,
            'show_prices'     => $request->boolean('show_prices') ? 1 : 0,
            'show_finance'    => $request->boolean('show_finance') ? 1 : 0,
            'fulfillment'     => $v['fulfillment'],
            'validation_mode' => $v['validation_mode'],
            'note'            => isset($v['note']) ? $v['note'] : null,
            'updated_at'      => now(),
        ];
        try {
            DB::table('customer_b2b_settings')->where('customer_id', $customer->id)->update($data);
        } catch (\Throwable $e) {
            return response()->json(['message' => "Enregistrement impossible : " . $e->getMessage() . " (lancez « php artisan migrate »)."], 500);
        }
        $saved = DB::table('customer_b2b_settings')->where('customer_id', $customer->id)->orderBy('id')->value('fulfillment');
        if ($saved !== $v['fulfillment']) {
            return response()->json(['message' => "Le mode de réception n'a pas été enregistré (valeur en base : « $saved »). Vérifiez la colonne customer_b2b_settings.fulfillment."], 500);
        }
        return response()->json($this->state($customer) + ['message' => 'Réglages B2B enregistrés.']);
    }

    public function storeUser(Request $request, Customer $customer)
    {
        $this->guard();
        $v = $request->validate([
            'name'  => 'required|string|max:120',
            'email' => 'required|email|max:150|unique:b2b_users,email',
            'phone' => 'nullable|string|max:50',
            'username' => ['nullable', 'regex:/^[A-Za-z0-9._-]{3,60}$/', 'unique:b2b_users,username'],
        ], [
            'email.unique' => 'Cette adresse e-mail a déjà un accès au portail.',
            'email.email'  => 'Adresse e-mail invalide.',
            'name.required' => 'Le nom est obligatoire.',
            'username.regex' => "Nom d'utilisateur : 3 à 60 caractères (lettres, chiffres, point, tiret, souligné), sans espace ni @.",
            'username.unique' => "Ce nom d'utilisateur est déjà pris.",
        ]);

        $username = !empty($v['username']) ? strtolower($v['username']) : $this->makeUsername($v['name']);
        $plain = $this->tempPassword();
        $u = B2bUser::create([
            'customer_id'          => $customer->id,
            'name'                 => $v['name'],
            'email'                => strtolower($v['email']),
            'username'             => $username,
            'phone'                => isset($v['phone']) ? $v['phone'] : null,
            'password'             => Hash::make($plain),
            'must_change_password' => true,
            'status'               => 'active',
        ]);
        CustomerB2bSetting::forCustomer($customer->id);

        return response()->json($this->state($customer) + [
            'message'       => 'Accès créé.',
            'temp_password' => $plain,       // affiché une seule fois
            'temp_for'      => $u->email,
            'temp_username' => $u->username,
        ]);
    }

    private function ownUser(Customer $customer, $userId)
    {
        return B2bUser::where('customer_id', $customer->id)->where('id', $userId)->firstOrFail();
    }

    public function setStatus(Customer $customer, $userId, $status)
    {
        $this->guard();
        abort_unless(in_array($status, ['active', 'suspended'], true), 404);
        $this->ownUser($customer, $userId)->update(['status' => $status]);
        return response()->json($this->state($customer) + ['message' => $status === 'active' ? 'Accès réactivé.' : 'Accès suspendu.']);
    }

    public function resetPassword(Customer $customer, $userId)
    {
        $this->guard();
        $u = $this->ownUser($customer, $userId);
        $plain = $this->tempPassword();
        $u->update(['password' => Hash::make($plain), 'must_change_password' => true, 'remember_token' => null, 'reset_requested_at' => null]);
        return response()->json($this->state($customer) + [
            'message'       => 'Mot de passe réinitialisé.',
            'temp_password' => $plain,
            'temp_for'      => $u->email,
            'temp_username' => $u->username,
        ]);
    }

    public function destroyUser(Customer $customer, $userId)
    {
        $this->guard();
        $this->ownUser($customer, $userId)->delete();
        return response()->json($this->state($customer) + ['message' => 'Accès supprimé.']);
    }
}
