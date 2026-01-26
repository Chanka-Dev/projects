<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar para funcionar en subdirectorio
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('http');
        }
        
        // Si la aplicación está en un subdirectorio
        $appUrl = env('APP_URL', '');
        if (str_contains($appUrl, '/distribuidora')) {
            URL::forceRootUrl($appUrl);
        }
    }
}
