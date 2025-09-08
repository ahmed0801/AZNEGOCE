<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Permission;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
 $this->registerPolicies();

    // Charger toutes les permissions de la base et créer un Gate pour chacune
    try {
        Permission::all()->each(function ($permission) {
            Gate::define($permission->key, function (User $user) use ($permission) {
                return $user->hasPermission($permission->key);
            });
        });
    } catch (\Exception $e) {
        // Évite les erreurs lors du cache config ou migration
    }
    }
}
