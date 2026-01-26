<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectStaffFromCatalog
{
    /**
     * Redirige trabajadores y administradores fuera del catálogo
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Si es trabajador o admin, redirigir a su dashboard
            if ($user->isTrabajador() || $user->isAdministrador()) {
                return redirect()->route('dashboard');
            }
        }
        
        return $next($request);
    }
}
