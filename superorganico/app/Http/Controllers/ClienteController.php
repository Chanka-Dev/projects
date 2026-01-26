<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('nombre')->paginate(15);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:persona,empresa',
            'nit' => 'nullable|string|max:50',
            'ci' => 'nullable|string|max:50',
            'telefono' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string',
            'ciudad' => 'nullable|string|max:100',
            'pais' => 'nullable|string|max:100',
            'activo' => 'boolean',
        ]);

        $cliente = Cliente::create($validated);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente creado exitosamente');
    }

    public function show(Cliente $cliente)
    {
        $cliente->load('ventas');
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:persona,empresa',
            'nit' => 'nullable|string|max:50',
            'ci' => 'nullable|string|max:50',
            'telefono' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string',
            'ciudad' => 'nullable|string|max:100',
            'pais' => 'nullable|string|max:100',
            'activo' => 'boolean',
        ]);

        $cliente->update($validated);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente actualizado exitosamente');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente eliminado exitosamente');
    }

    /**
     * Guardar cliente desde modal (AJAX)
     */
    public function storeAjax(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:persona,empresa',
            'nit' => 'nullable|string|max:50',
            'ci' => 'nullable|string|max:50',
            'telefono' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $validated['activo'] = true;

        $cliente = Cliente::create($validated);

        return response()->json([
            'success' => true,
            'cliente' => $cliente,
            'message' => 'Cliente creado exitosamente'
        ]);
    }
}
