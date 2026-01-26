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
        // Gate para administrador
        Gate::define('administrador', function ($user) {
            return $user->esAdministrador();
        });

        // Gate para empleado
        Gate::define('empleado', function ($user) {
            return $user->esEmpleado();
        });
    }
}
