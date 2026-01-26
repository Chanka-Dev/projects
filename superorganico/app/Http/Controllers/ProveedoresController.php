<?php

namespace App\Http\Controllers;

use App\Models\Proveedores;
use Illuminate\Http\Request;

class ProveedoresController extends Controller
{
    public function index()
    {
        $proveedores = Proveedores::orderBy('nombre')->paginate(15);
        return view('proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('proveedores.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'nit' => 'nullable|string|max:50',
            'ci' => 'nullable|string|max:50',
            'telefono' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string',
            'ciudad' => 'nullable|string|max:100',
            'pais' => 'nullable|string|max:100',
            'activo' => 'boolean',
        ]);

        $proveedor = Proveedores::create($validated);

        return redirect()
            ->route('proveedores.index')
            ->with('success', 'Proveedor creado exitosamente');
    }

    public function show(Proveedores $proveedore)
    {
        $proveedore->load('compras');
        return view('proveedores.show', ['proveedor' => $proveedore]);
    }

    public function edit(Proveedores $proveedore)
    {
        return view('proveedores.edit', ['proveedor' => $proveedore]);
    }

    public function update(Request $request, Proveedores $proveedore)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'nit' => 'nullable|string|max:50',
            'ci' => 'nullable|string|max:50',
            'telefono' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string',
            'ciudad' => 'nullable|string|max:100',
            'pais' => 'nullable|string|max:100',
            'activo' => 'boolean',
        ]);

        $proveedore->update($validated);

        return redirect()
            ->route('proveedores.index')
            ->with('success', 'Proveedor actualizado exitosamente');
    }

    public function destroy(Proveedores $proveedore)
    {
        $proveedore->delete();

        return redirect()
            ->route('proveedores.index')
            ->with('success', 'Proveedor eliminado exitosamente');
    }

    /**
     * Guardar proveedor desde modal (AJAX)
     */
    public function storeAjax(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'nit' => 'nullable|string|max:50',
            'ci' => 'nullable|string|max:50',
            'telefono' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $validated['activo'] = true;

        $proveedor = Proveedores::create($validated);

        return response()->json([
            'success' => true,
            'proveedor' => $proveedor,
            'message' => 'Proveedor creado exitosamente'
        ]);
    }
}
