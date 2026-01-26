<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Gates para roles
        Gate::define('isAdministrador', function ($user) {
            return $user->role === 'administrador';
        });

        Gate::define('isTrabajador', function ($user) {
            return $user->role === 'trabajador';
        });

        Gate::define('isCliente', function ($user) {
            return $user->role === 'cliente';
        });
    }
}
