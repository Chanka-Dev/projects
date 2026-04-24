<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Receta;
use App\Models\RecetaParte;
use App\Models\RecetaIngrediente;
use App\Models\Etiqueta;
use App\Models\Ingrediente;

class RecetaController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:recipe-list|recipe-create|recipe-edit|recipe-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:recipe-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:recipe-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:recipe-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $etiquetaFiltro = $request->input('etiqueta');
        $ingredienteFiltro = $request->input('ingrediente');

        $recetas = Receta::with(['etiqueta', 'user'])
            ->when($etiquetaFiltro, fn($q) => $q->where('etiqueta_id', $etiquetaFiltro))
            ->when($ingredienteFiltro, function ($q) use ($ingredienteFiltro) {
                $q->whereHas('ingredientes', fn($qi) => $qi->where('ingrediente_id', $ingredienteFiltro));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $etiquetas = Etiqueta::orderBy('nombre')->get();
        $ingredientes = Ingrediente::orderBy('nombre')->get();

        return view('recetas.index', compact('recetas', 'etiquetas', 'ingredientes', 'etiquetaFiltro', 'ingredienteFiltro'));
    }

    public function create()
    {
        $etiquetas = Etiqueta::orderBy('nombre')->get();
        $ingredientes = Ingrediente::orderBy('nombre')->get();
        return view('recetas.create', compact('etiquetas', 'ingredientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'      => 'required|max:255',
            'etiqueta_id' => 'required|exists:etiquetas,id',
            'imagen'      => 'nullable|image|max:2048',
            'link'        => 'nullable|url|max:500',
        ]);

        $data = $request->only('titulo', 'etiqueta_id', 'fuente', 'link', 'instrucciones');
        $data['user_id'] = auth()->id();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $this->guardarImagen($request);
        }

        $receta = Receta::create($data);
        $this->syncPartes($receta, $request);

        return redirect()->route('recetas.show', $receta)->with('success', 'Receta creada correctamente.');
    }

    public function show(Receta $receta)
    {
        $receta->load(['etiqueta', 'user', 'partes.ingredientes.ingrediente', 'ingredientes.ingrediente']);
        return view('recetas.show', compact('receta'));
    }

    public function edit(Receta $receta)
    {
        $receta->load(['partes.ingredientes.ingrediente', 'ingredientes.ingrediente']);
        $etiquetas = Etiqueta::orderBy('nombre')->get();
        $ingredientes = Ingrediente::orderBy('nombre')->get();
        return view('recetas.edit', compact('receta', 'etiquetas', 'ingredientes'));
    }

    public function update(Request $request, Receta $receta)
    {
        $request->validate([
            'titulo'      => 'required|max:255',
            'etiqueta_id' => 'required|exists:etiquetas,id',
            'imagen'      => 'nullable|image|max:2048',
            'link'        => 'nullable|url|max:500',
        ]);

        $data = $request->only('titulo', 'etiqueta_id', 'fuente', 'link', 'instrucciones');

        if ($request->hasFile('imagen')) {
            if ($receta->imagen) {
                $this->eliminarImagen($receta->imagen);
            }
            $data['imagen'] = $this->guardarImagen($request);
        }

        $receta->update($data);
        $receta->partes()->delete();
        $receta->ingredientes()->whereNull('receta_parte_id')->delete();
        $this->syncPartes($receta, $request);

        return redirect()->route('recetas.show', $receta)->with('success', 'Receta actualizada.');
    }

    public function destroy(Receta $receta)
    {
        if ($receta->imagen) {
            $this->eliminarImagen($receta->imagen);
        }
        $receta->delete();
        return redirect()->route('recetas.index')->with('success', 'Receta eliminada.');
    }

    private function guardarImagen(Request $request): string
    {
        $file = $request->file('imagen');
        $nombre = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('imagenes/recetas'), $nombre);
        return 'imagenes/recetas/' . $nombre;
    }

    private function eliminarImagen(string $path): void
    {
        $fullPath = public_path($path);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    private function syncPartes(Receta $receta, Request $request): void
    {
        // Ingredientes generales (sin parte)
        foreach ($request->input('ing_general', []) as $item) {
            if (empty($item['ingrediente_id'])) continue;
            RecetaIngrediente::create([
                'receta_id'      => $receta->id,
                'receta_parte_id' => null,
                'ingrediente_id' => $item['ingrediente_id'],
                'cantidad'       => $item['cantidad'] ?? null,
                'notas'          => $item['notas'] ?? null,
            ]);
        }

        // Partes con sus ingredientes e instrucciones
        foreach ($request->input('partes', []) as $i => $parte) {
            if (empty($parte['titulo'])) continue;
            $recetaParte = RecetaParte::create([
                'receta_id'    => $receta->id,
                'titulo'       => $parte['titulo'],
                'orden'        => $i,
                'instrucciones' => $parte['instrucciones'] ?? null,
            ]);

            foreach ($parte['ingredientes'] ?? [] as $item) {
                if (empty($item['ingrediente_id'])) continue;
                RecetaIngrediente::create([
                    'receta_id'       => $receta->id,
                    'receta_parte_id' => $recetaParte->id,
                    'ingrediente_id'  => $item['ingrediente_id'],
                    'cantidad'        => $item['cantidad'] ?? null,
                    'notas'           => $item['notas'] ?? null,
                ]);
            }
        }
    }
}
