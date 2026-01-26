<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        // Obtener usuarios con rol 'cliente' y sus pedidos
        $clientes = User::where('role', 'cliente')
            ->with('pedidos')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('clientes.index', compact('clientes'));
    }
}