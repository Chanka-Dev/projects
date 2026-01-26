<?php

namespace App\Http\Controllers\Trabajador;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index()
    {
        $productos = Producto::orderBy('nombre', 'asc')->get();
        return view('trabajador.inventario', compact('productos'));
    }
}
