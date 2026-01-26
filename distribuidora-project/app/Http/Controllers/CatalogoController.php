<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index()
    {
        $categorias = \App\Models\Categoria::all();
        $productos = Producto::with('categoria')
            ->filter(request(['search', 'categoria']))
            ->where('stock', '>', 0)
            ->get();
            
        return view('dashboard.public', compact('productos', 'categorias'));
    }

    public function agregar(Request $request)
    {
        $producto = Producto::find($request->producto_id);
        $carrito = session()->get('carrito', []);

        $carrito[$producto->id] = [
            'nombre' => $producto->nombre,
            'precio' => $producto->precio,
            'cantidad' => ($carrito[$producto->id]['cantidad'] ?? 0) + 1,
        ];

        session()->put('carrito', $carrito);
        return redirect()->route('catalogo.index')->with('success', 'Producto añadido al carrito.');
    }

    public function carrito()
    {
        $carrito = session()->get('carrito', []);
        return view('catalogo.carrito', compact('carrito'));
    }

    public function eliminar(Request $request)
    {
        $carrito = session()->get('carrito', []);
        unset($carrito[$request->producto_id]);
        session()->put('carrito', $carrito);
        return redirect()->route('carrito.ver')->with('success', 'Producto eliminado del carrito.');
    }
}
