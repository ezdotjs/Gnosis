<?php

namespace App\Providers;

use App\Models\Gnosis\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('users.list', function (User $user) {
            return $user->hasPermission('users.list');
        });

        Gate::define('users.create', function (User $user) {
            return $user->hasPermission('users.create');
        });

        Gate::define('users.update', function (User $user) {
            return $user->hasPermission('users.update');
        });

        Gate::define('users.view', function (User $user) {
            return $user->hasPermission('users.view');
        });

        Gate::define('users.delete', function (User $user) {
            return $user->hasPermission('users.delete');
        });
    }
}
