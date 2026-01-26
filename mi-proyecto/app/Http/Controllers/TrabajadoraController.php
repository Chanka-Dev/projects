<?php

namespace App\Http\Controllers;

use App\Models\Trabajadora;
use Illuminate\Http\Request;

class TrabajadoraController extends Controller
{
    public function index(Request $request)
    {
        $query = Trabajadora::query();
        if ($request->has('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%')
                  ->orWhere('tipo_contrato', 'like', '%' . $request->search . '%');
        }
        $trabajadoras = $query->paginate(10);
        return view('trabajadoras.index', compact('trabajadoras'));
    }

    public function create()
    {
        return view('trabajadoras.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_contrato' => 'required|string|in:planta,independiente',
            'activo' => 'boolean',
        ]);

        Trabajadora::create($validated + ['activo' => $request->input('activo', true), 'porcentaje_comision' => 0]);
        return redirect()->route('trabajadoras.index')->with('success', 'Trabajadora registrada con éxito');
    }

    public function show(Trabajadora $trabajadora)
    {
        return view('trabajadoras.show', compact('trabajadora'));
    }

    public function edit(Trabajadora $trabajadora)
    {
        return view('trabajadoras.edit', compact('trabajadora'));
    }

    public function update(Request $request, Trabajadora $trabajadora)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_contrato' => 'required|string|in:planta,independiente',
            'activo' => 'boolean',
        ]);

        $trabajadora->update($validated);
        return redirect()->route('trabajadoras.index')->with('success', 'Trabajadora actualizada con éxito');
    }

    public function destroy(Trabajadora $trabajadora)
    {
        $trabajadora->delete();
        return redirect()->route('trabajadoras.index')->with('success', 'Trabajadora eliminada con éxito');
    }
}