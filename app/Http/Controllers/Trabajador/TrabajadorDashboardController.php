<?php

namespace App\Http\Controllers\Trabajador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;

class TrabajadorDashboardController extends Controller
{
    public function index()
    {
        $pedidosPendientes = Pedido::where('estado', 'pendiente')->count();
        $pedidosConfirmados = Pedido::where('estado', 'confirmado')->count();
        $pedidosCompletados = Pedido::where('estado', 'completado')->count();

        return view('dashboard.trabajador', compact(
            'pedidosPendientes',
            'pedidosConfirmados',
            'pedidosCompletados'
        ));
    }
}
