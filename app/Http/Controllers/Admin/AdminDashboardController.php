<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalProductos = Producto::count();
        $totalClientes = Cliente::count();
        $totalPedidos = Pedido::count();
        $totalUsuarios = User::count();

        return view('dashboard.admin', compact(
            'totalProductos',
            'totalClientes',
            'totalPedidos',
            'totalUsuarios'
        ));
    }
}
