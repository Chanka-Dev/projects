<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Redirigir al dashboard según el rol del usuario
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdministrador()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isTrabajador()) {
            return redirect()->route('trabajador.inventario');
        } else {
            return redirect()->route('catalogo.index');
        }
    }
}
