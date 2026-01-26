<?php

namespace App\Http\Controllers;

use App\Models\CitaServicio;
use App\Models\Cita;
use App\Models\Servicio;
use Illuminate\Http\Request;

class CitaServicioController extends Controller
{
    public function index(Request $request)
    {
        $query = CitaServicio::with(['cita.cliente', 'servicio']);
        if ($request->has('search')) {
            $query->whereHas('cita.cliente', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->search . '%')
                  ->orWhere('telefono', 'like', '%' . $request->search . '%');
            })->orWhereHas('servicio', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->search . '%');
            });
        }
        $citasServicios = $query->paginate(10);
        return view('citas-servicios.index', compact('citasServicios'));
    }

    public function create()
    {
        $citas = Cita::with('cliente')->get();
        $servicios = Servicio::where('activo', true)->get();
        return view('citas-servicios.create', compact('citas', 'servicios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cita_id' => 'required|exists:citas,id',
            'servicio_id' => 'required|exists:servicios,id',
            'precio_aplicado' => 'required|numeric|min:0',
        ]);

        CitaServicio::create($validated);
        return redirect()->route('citas-servicios.index')->with('success', 'Cita-Servicio registrada con éxito');
    }

    public function show(CitaServicio $citaServicio)
    {
        $citaServicio->load(['cita.cliente', 'servicio']);
        return view('citas-servicios.show', compact('citaServicio'));
    }

    public function edit(CitaServicio $citaServicio)
    {
        $citas = Cita::with('cliente')->get();
        $servicios = Servicio::where('activo', true)->get();
        return view('citas-servicios.edit', compact('citaServicio', 'citas', 'servicios'));
    }

    public function update(Request $request, CitaServicio $citaServicio)
    {
        $validated = $request->validate([
            'cita_id' => 'required|exists:citas,id',
            'servicio_id' => 'required|exists:servicios,id',
            'precio_aplicado' => 'required|numeric|min:0',
        ]);

        $citaServicio->update($validated);
        return redirect()->route('citas-servicios.index')->with('success', 'Cita-Servicio actualizada con éxito');
    }

    public function destroy(CitaServicio $citaServicio)
    {
        $citaServicio->delete();
        return redirect()->route('citas-servicios.index')->with('success', 'Cita-Servicio eliminada con éxito');
    }
}