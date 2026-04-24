<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ingrediente;

class IngredienteController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ingredient-list|ingredient-create|ingredient-edit|ingredient-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:ingredient-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:ingredient-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:ingredient-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = $request->input('q');
        $ingredientes = Ingrediente::when($query, fn($q) => $q->where('nombre', 'like', "%$query%"))
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();
        return view('ingredientes.index', compact('ingredientes', 'query'))
            ->with('i', ($request->input('page', 1) - 1) * 15);
    }

    public function create()
    {
        return view('ingredientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'        => 'required|unique:ingredientes,nombre|max:150',
            'unidad_default'=> 'nullable|max:50',
            'categoria'     => 'nullable|max:80',
            'descripcion'   => 'nullable|max:500',
            'es_alergeno'   => 'nullable|boolean',
        ]);
        Ingrediente::create(array_merge(
            $request->only('nombre', 'unidad_default', 'categoria', 'descripcion'),
            ['es_alergeno' => $request->boolean('es_alergeno')]
        ));
        return redirect()->route('ingredientes.index')->with('success', 'Ingrediente creado correctamente.');
    }

    public function edit(Ingrediente $ingrediente)
    {   
        return view('ingredientes.edit', compact('ingrediente'));
    }

    public function update(Request $request, Ingrediente $ingrediente)
    {
        $request->validate([
            'nombre'        => 'required|unique:ingredientes,nombre,'.$ingrediente->id.'|max:150',
            'unidad_default'=> 'nullable|max:50',
            'categoria'     => 'nullable|max:80',
            'descripcion'   => 'nullable|max:500',
            'es_alergeno'   => 'nullable|boolean',
        ]);
        $ingrediente->update(array_merge(
            $request->only('nombre', 'unidad_default', 'categoria', 'descripcion'),
            ['es_alergeno' => $request->boolean('es_alergeno')]
        ));
        return redirect()->route('ingredientes.index')->with('success', 'Ingrediente actualizado.');
    }

    public function destroy(Ingrediente $ingrediente)
    {
        $ingrediente->delete();
        return redirect()->route('ingredientes.index')->with('success', 'Ingrediente eliminado.');
    }
}
