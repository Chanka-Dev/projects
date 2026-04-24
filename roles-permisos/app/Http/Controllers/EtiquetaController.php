<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etiqueta;

class EtiquetaController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:tag-list|tag-create|tag-edit|tag-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:tag-create', ['only' => ['create', 's tore']]);
        $this->middleware('permission:tag-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:tag-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $etiquetas = Etiqueta::orderBy('nombre')->paginate(10);
        return view('etiquetas.index', compact('etiquetas'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        return view('etiquetas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:etiquetas,nombre|max:100',
            'color'  => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);
        Etiqueta::create($request->only('nombre', 'color'));
        return redirect()->route('etiquetas.index')->with('success', 'Etiqueta creada correctamente.');
    }

    public function edit(Etiqueta $etiqueta)
    {
        return view('etiquetas.edit', compact('etiqueta'));
    }

    public function update(Request $request, Etiqueta $etiqueta)
    {
        $request->validate([
            'nombre' => 'required|unique:etiquetas,nombre,'.$etiqueta->id.'|max:100',
            'color'  => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);
        $etiqueta->update($request->only('nombre', 'color'));
        return redirect()->route('etiquetas.index')->with('success', 'Etiqueta actualizada.');
    }

    public function destroy(Etiqueta $etiqueta)
    {
        $etiqueta->delete();
        return redirect()->route('etiquetas.index')->with('success', 'Etiqueta eliminada.');
    }
}
