<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Cita;
use App\Models\HistorialServicio;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $query = Pago::with('cita.cliente', 'cita.trabajadora', 'cita.servicios');
        
        // Filtro por estado
        if ($request->has('estado') && $request->estado) {
            $query->where('estado', $request->estado);
        }
        
        // Filtro por búsqueda
        if ($request->has('search') && $request->search) {
            $query->whereHas('cita.cliente', function ($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->search . '%')
                  ->orWhere('telefono', 'like', '%' . $request->search . '%');
            });
        }
        
        $pagos = $query->orderBy('fecha_pago', 'desc')->paginate(10)->appends($request->all());
        
        // Obtener citas sin pago (pendientes de registro)
        $citasSinPago = Cita::with('cliente', 'trabajadora', 'servicios')
            ->where('estado', 'completada')
            ->whereDoesntHave('pago')
            ->orderBy('fecha', 'desc')
            ->get();
        
        return view('pagos.index', compact('pagos', 'citasSinPago'));
    }

    public function create()
    {
        // Solo mostrar citas completadas sin pago
        $citas = Cita::with('cliente', 'trabajadora', 'servicios')
            ->where('estado', 'completada')
            ->whereDoesntHave('pago')
            ->orderBy('fecha', 'desc')
            ->get();
        return view('pagos.create', compact('citas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cita_id' => 'required|exists:citas,id|unique:pagos,cita_id',
            'monto_total' => 'required|numeric|min:0',
            'metodo_pago' => 'required|string|in:efectivo,tarjeta,transferencia',
            'estado' => 'required|string|in:pendiente,completado,fallido',
            'fecha_pago' => 'required|date|before_or_equal:today',
        ]);

        $pago = Pago::create($validated);
        
        // Si el pago está completado y la cita está completada, crear historial
        if ($validated['estado'] === 'completado') {
            $cita = Cita::with('servicios')->find($validated['cita_id']);
            
            if ($cita && $cita->estado === 'completada') {
                // Verificar que no exista historial previo
                $existeHistorial = HistorialServicio::where('cliente_id', $cita->cliente_id)
                    ->where('trabajadora_id', $cita->trabajadora_id)
                    ->where('fecha_servicio', $cita->fecha)
                    ->exists();
                
                if (!$existeHistorial) {
                    foreach ($cita->servicios as $servicio) {
                        $precioAplicado = $servicio->pivot->precio_aplicado;
                        $montoComision = $servicio->monto_comision ?? 0;
                        
                        HistorialServicio::create([
                            'cita_id' => $cita->id,
                            'cliente_id' => $cita->cliente_id,
                            'trabajadora_id' => $cita->trabajadora_id,
                            'servicio_id' => $servicio->id,
                            'fecha_servicio' => $cita->fecha,
                            'precio_cobrado' => $precioAplicado,
                            'monto_comision' => $montoComision,
                            'metodo_pago' => $validated['metodo_pago'],
                        ]);
                    }
                }
            }
        }
        
        return redirect()->route('pagos.index')->with('success', 'Pago registrado con éxito');
    }

    public function show(Pago $pago)
    {
        $pago->load('cita.cliente');
        return view('pagos.show', compact('pago'));
    }

    public function edit(Pago $pago)
    {
        $citas = Cita::with('cliente')->whereDoesntHave('pago')->orWhere('id', $pago->cita_id)->get();
        return view('pagos.edit', compact('pago', 'citas'));
    }

    public function update(Request $request, Pago $pago)
    {
        $validated = $request->validate([
            'cita_id' => 'required|exists:citas,id|unique:pagos,cita_id,' . $pago->id,
            'monto_total' => 'required|numeric|min:0',
            'metodo_pago' => 'required|string|in:efectivo,tarjeta,transferencia',
            'estado' => 'required|string|in:pendiente,completado,fallido',
            'fecha_pago' => 'required|date|before_or_equal:today',
        ]);

        $estadoAnterior = $pago->estado;
        $pago->update($validated);
        
        // Si cambió de pendiente a completado, crear historial
        if ($estadoAnterior !== 'completado' && $validated['estado'] === 'completado') {
            $cita = Cita::with('servicios')->find($validated['cita_id']);
            
            if ($cita && $cita->estado === 'completada') {
                // Verificar que no exista historial previo
                $existeHistorial = HistorialServicio::where('cliente_id', $cita->cliente_id)
                    ->where('trabajadora_id', $cita->trabajadora_id)
                    ->where('fecha_servicio', $cita->fecha)
                    ->exists();
                
                if (!$existeHistorial) {
                    foreach ($cita->servicios as $servicio) {
                        $precioAplicado = $servicio->pivot->precio_aplicado;
                        $montoComision = $servicio->monto_comision ?? 0;
                        
                        HistorialServicio::create([
                            'cita_id' => $cita->id,
                            'cliente_id' => $cita->cliente_id,
                            'trabajadora_id' => $cita->trabajadora_id,
                            'servicio_id' => $servicio->id,
                            'fecha_servicio' => $cita->fecha,
                            'precio_cobrado' => $precioAplicado,
                            'monto_comision' => $montoComision,
                            'metodo_pago' => $validated['metodo_pago'],
                        ]);
                    }
                }
            }
        }
        
        return redirect()->route('pagos.index')->with('success', 'Pago actualizado con éxito');
    }

    public function destroy(Pago $pago)
    {
        $pago->delete();
        return redirect()->route('pagos.index')->with('success', 'Pago eliminado con éxito');
    }

    public function marcarCompletado(Pago $pago)
    {
        // Solo si el pago no está ya completado
        if ($pago->estado === 'completado') {
            return redirect()->route('pagos.index')->with('info', 'El pago ya está completado');
        }

        $estadoAnterior = $pago->estado;
        $pago->update([
            'estado' => 'completado',
            'fecha_pago' => now(),
        ]);

        // Crear historial de servicios si la cita está completada
        $cita = Cita::with('servicios')->find($pago->cita_id);
        
        if ($cita && $cita->estado === 'completada') {
            // Verificar que no exista historial previo
            $existeHistorial = HistorialServicio::where('cita_id', $cita->id)->exists();
            
            if (!$existeHistorial) {
                foreach ($cita->servicios as $servicio) {
                    $precioAplicado = $servicio->pivot->precio_aplicado;
                    $montoComision = $servicio->monto_comision ?? 0;
                    
                    HistorialServicio::create([
                        'cita_id' => $cita->id,
                        'cliente_id' => $cita->cliente_id,
                        'trabajadora_id' => $cita->trabajadora_id,
                        'servicio_id' => $servicio->id,
                        'fecha_servicio' => $cita->fecha,
                        'precio_cobrado' => $precioAplicado,
                        'monto_comision' => $montoComision,
                        'metodo_pago' => $pago->metodo_pago,
                    ]);
                }
            }
        }

        return redirect()->route('pagos.index')->with('success', 'Pago marcado como completado');
    }
}