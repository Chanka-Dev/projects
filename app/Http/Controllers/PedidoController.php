<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\PedidoProducto;
use App\Models\Producto;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function formulario()
    {
        $carrito = session()->get('carrito', []);
        return view('pedidos.formulario', compact('carrito'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string',
        ]);

        $carrito = session()->get('carrito', []);
        if (empty($carrito)) {
            return redirect()->route('catalogo.index')->with('error', 'El carrito está vacío.');
        }

        $user = auth()->user();

        // Crear el pedido vinculado al usuario
        $pedido = Pedido::create([
            'user_id' => $user->id,
            'estado' => 'pendiente',
        ]);

        $mensaje = "Hola, soy {$user->name} {$user->apellido}, mi pedido:\n";
        $mensaje .= "Teléfono: {$request->telefono}\n";
        $mensaje .= "Dirección: {$request->direccion}\n\n";
        $detalle_pedido = "Dirección: {$request->direccion}\n\n";

        foreach ($carrito as $id => $item) {
            PedidoProducto::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $id,
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio'],
            ]);
            $linea = "• {$item['cantidad']} x {$item['nombre']} (Bs {$item['precio']})\n";
            $mensaje .= "- {$item['cantidad']} x {$item['nombre']} (Bs {$item['precio']})\n";
            $detalle_pedido .= $linea;
        }

        $pedido->mensaje_whatsapp = $mensaje;
        $pedido->detalle_pedido = $detalle_pedido;
        $pedido->save();

        session()->forget('carrito');

        $link = "https://wa.me/59179310105?text=" . urlencode($mensaje);

        return redirect()->away($link);
    }

    public function admin()
    {
        $pedidos = Pedido::with(['cliente', 'user'])->orderByDesc('id')->get();
        return view('pedidos.admin', compact('pedidos'));
    }

    public function cambiarEstado(Request $request, Pedido $pedido)
    {
        $previousState = $pedido->estado;
        $pedido->estado = $request->estado;
        $pedido->save();

        // Decrementar stock solo si pasa a completado desde un estado no completado
        if ($pedido->estado === 'completado' && $previousState !== 'completado') {
            foreach ($pedido->productos as $detalle) {
                $detalle->producto->decrement('stock', $detalle->cantidad);
            }
        }

        // Restaurar stock si estaba completado y se cancela
        if ($previousState === 'completado' && $pedido->estado === 'cancelado') {
            foreach ($pedido->productos as $detalle) {
                $detalle->producto->increment('stock', $detalle->cantidad);
            }
        }

        return redirect()->route('pedidos.admin')->with('success', 'Estado actualizado.');
    }
}