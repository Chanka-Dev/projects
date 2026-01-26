<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index(Request $request)
    {
        $query = Servicio::query();
        if ($request->has('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%')
                  ->orWhere('descripcion', 'like', '%' . $request->search . '%');
        }
        $servicios = $query->paginate(10);
        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('servicios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:servicios,nombre',
            'descripcion' => 'nullable|string',
            'precio_base' => 'required|numeric|min:0',
            'monto_comision' => 'required|numeric|min:0',
            'activo' => 'boolean',
        ]);

        Servicio::create($validated + ['activo' => $request->input('activo', true)]);
        return redirect()->route('servicios.index')->with('success', 'Servicio creado con éxito');
    }

    public function show(Servicio $servicio)
    {
        return view('servicios.show', compact('servicio'));
    }

    public function edit(Servicio $servicio)
    {
        return view('servicios.edit', compact('servicio'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:servicios,nombre,' . $servicio->id,
            'descripcion' => 'nullable|string',
            'precio_base' => 'required|numeric|min:0',
            'monto_comision' => 'required|numeric|min:0',
            'activo' => 'boolean',
        ]);

        $servicio->update($validated);
        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado con éxito');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado con éxito');
    }
}