<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Trabajadora;
use App\Models\Servicio;
use App\Models\HistorialServicio;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function index(Request $request)
    {
        $query = Cita::with(['cliente', 'trabajadora']);
        if ($request->has('search')) {
            $query->whereHas('cliente', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->search . '%')
                  ->orWhere('telefono', 'like', '%' . $request->search . '%');
            })->orWhere('fecha', 'like', '%' . $request->search . '%');
        }
        $citas = $query->paginate(10);
        return view('citas.index', compact('citas'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $trabajadoras = Trabajadora::where('activo', true)->get();
        $servicios = Servicio::where('activo', true)->get();
        return view('citas.create', compact('clientes', 'trabajadoras', 'servicios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'trabajadora_id' => 'required|exists:trabajadoras,id',
            'fecha' => 'required|date',
            'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
            'observaciones' => 'nullable|string|max:500',
            'servicios' => 'required|array',
            'servicios.*' => 'exists:servicios,id',
        ]);

        $cita = Cita::create($validated);
        
        $serviciosData = [];
        foreach ($request->servicios as $servicioId) {
            $servicio = Servicio::find($servicioId);
            $serviciosData[$servicioId] = ['precio_aplicado' => $servicio->precio_base];
        }
        
        $cita->servicios()->attach($serviciosData);

        return redirect()->route('citas.index')->with('success', 'Cita creada con éxito');
    }    public function show(Cita $cita)
    {
        $cita->load(['cliente', 'trabajadora', 'servicios']);
        return view('citas.show', compact('cita'));
    }

    public function edit(Cita $cita)
    {
        $clientes = Cliente::all();
        $trabajadoras = Trabajadora::where('activo', true)->get();
        $servicios = Servicio::where('activo', true)->get();
        return view('citas.edit', compact('cita', 'clientes', 'trabajadoras', 'servicios'));
    }

    public function update(Request $request, Cita $cita)
{
    $validated = $request->validate([
        'cliente_id' => 'required|exists:clientes,id',
        'trabajadora_id' => 'required|exists:trabajadoras,id',
        'fecha' => 'required|date',
        'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
        'observaciones' => 'nullable|string|max:500',
        'servicios' => 'required|array',
        'servicios.*' => 'exists:servicios,id',
    ]);

    $estadoAnterior = $cita->estado;
    $cita->update($validated);
    
    $serviciosData = [];
    foreach ($request->servicios as $servicioId) {
        $servicio = Servicio::find($servicioId);
        $serviciosData[$servicioId] = ['precio_aplicado' => $servicio->precio_base];
    }
    
    $cita->servicios()->sync($serviciosData);

    // Solo crear historial si la cita está completada Y tiene pago completado
    if ($validated['estado'] == 'completada') {
        $pago = $cita->pago;
        
        // Verificar que el pago existe y está completado
        if ($pago && $pago->estado === 'completado') {
            // Verificar si ya existe historial para esta cita (evitar duplicados)
            $existeHistorial = HistorialServicio::where('cliente_id', $cita->cliente_id)
                ->where('trabajadora_id', $cita->trabajadora_id)
                ->where('fecha_servicio', $cita->fecha)
                ->exists();
            
            if (!$existeHistorial) {
                $metodoPago = $pago->metodo_pago;
                
                foreach ($cita->servicios as $servicio) {
                    $precioAplicado = $servicio->pivot->precio_aplicado;
                    $porcentajeComision = $servicio->porcentaje_comision ?? 0;
                    $montoComision = ($precioAplicado * $porcentajeComision) / 100;
                    
                    HistorialServicio::create([
                        'cliente_id' => $cita->cliente_id,
                        'trabajadora_id' => $cita->trabajadora_id,
                        'servicio_id' => $servicio->id,
                        'fecha_servicio' => $cita->fecha,
                        'precio_cobrado' => $precioAplicado,
                        'porcentaje_comision' => $porcentajeComision,
                        'monto_comision' => $montoComision,
                        'metodo_pago' => $metodoPago,
                    ]);
                }
            }
        }
    }

    return redirect()->route('citas.index')->with('success', 'Cita actualizada con éxito');
}

    public function destroy(Cita $cita)
    {
        $cita->delete();
        return redirect()->route('citas.index')->with('success', 'Cita eliminada con éxito');
    }
}