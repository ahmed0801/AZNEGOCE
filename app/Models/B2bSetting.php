<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class B2bSetting extends Model
{
    protected $table = 'b2b_settings';
    protected $guarded = ['id'];

    protected $casts = [
        'portal_enabled'    => 'boolean',
        'auto_validate'     => 'boolean',
        'block_over_credit' => 'boolean',
        'allow_particulier' => 'boolean',
        'show_prices'       => 'boolean',
        'show_stock'        => 'boolean',
        'auto_print'        => 'boolean',
        'notify_new_order'  => 'boolean',
        'portal_show_erp_quotes' => 'boolean',
        'margin_guard'      => 'boolean',
        'min_margin_percent' => 'float',
        'min_order_amount'  => 'float',
    ];

    const BOOLEANS = ['portal_enabled', 'auto_validate', 'block_over_credit', 'allow_particulier',
                      'show_prices', 'show_stock', 'auto_print', 'notify_new_order', 'margin_guard', 'portal_show_erp_quotes'];

    /** La ligne unique de paramètres (créée avec les valeurs par défaut au premier appel). */
    public static function current()
    {
        $s = static::first();
        if (!$s) {
            $company = null;
            try { $company = \App\Models\CompanyInformation::first(); } catch (\Throwable $e) {}
            $s = static::create([
                'company_name' => $company ? $company->name : null,
                'phone'        => $company ? $company->phone : null,
                'email'        => $company ? $company->email : null,
                'address'      => $company ? $company->address : null,
            ]);
        }
        return $s;
    }

    /** Portail « devis seulement » : seules les demandes de devis (+ mot de passe / déconnexion) sont accessibles. */
    public function quotesOnly()
    {
        return ($this->portal_mode ?? 'full') === 'quotes';
    }

    /** Nom de la route d'accueil du portail selon le mode. */
    public function homeRoute()
    {
        return $this->quotesOnly() ? 'portal.quotes' : 'portal.catalog';
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo_path ? asset($this->logo_path) : null;
    }
}
