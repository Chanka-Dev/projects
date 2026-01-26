<?php

namespace App\Http\Controllers;

use App\Models\PagoTrabajadora;
use App\Models\Trabajadora;
use App\Models\HistorialServicio;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PagoTrabajadoraController extends Controller
{
    public function index(Request $request)
    {
        $query = PagoTrabajadora::with('trabajadora');
        
        if ($request->has('trabajadora_id') && $request->trabajadora_id) {
            $query->where('trabajadora_id', $request->trabajadora_id);
        }
        
        if ($request->has('estado') && $request->estado) {
            $query->where('estado', $request->estado);
        }
        
        // Filtrar por rango de fechas (buscar pagos que se solapen con el rango)
        if ($request->has('fecha_desde') && $request->fecha_desde) {
            $query->where('fecha_fin_periodo', '>=', $request->fecha_desde);
        }
        
        if ($request->has('fecha_hasta') && $request->fecha_hasta) {
            $query->where('fecha_inicio_periodo', '<=', $request->fecha_hasta);
        }
        
        $pagos = $query->orderBy('fecha_inicio_periodo', 'desc')->paginate(10)->appends($request->all());
        $trabajadoras = Trabajadora::where('activo', true)->get();
        
        // Comisiones pendientes agrupadas por trabajadora
        $comisionesPendientes = HistorialServicio::with(['trabajadora', 'cita.cliente', 'servicio'])
            ->whereNull('pago_trabajadora_id')
            ->get()
            ->groupBy('trabajadora_id')
            ->map(function ($servicios) {
                return [
                    'trabajadora' => $servicios->first()->trabajadora,
                    'total_servicios' => $servicios->count(),
                    'total_comisiones' => $servicios->sum('monto_comision'),
                    'fecha_inicio' => $servicios->min('fecha_servicio'),
                    'fecha_fin' => $servicios->max('fecha_servicio'),
                    'servicios' => $servicios,
                ];
            });
        
        // Estadísticas de comisiones pendientes
        $estadisticas = [
            'total_pendiente' => HistorialServicio::whereNull('pago_trabajadora_id')->sum('monto_comision'),
            'servicios_pendientes' => HistorialServicio::whereNull('pago_trabajadora_id')->count(),
        ];
        
        return view('pago-trabajadoras.index', compact('pagos', 'trabajadoras', 'estadisticas', 'comisionesPendientes'));
    }

    public function create()
    {
        $trabajadoras = Trabajadora::where('activo', true)->get();
        return view('pago-trabajadoras.create', compact('trabajadoras'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'trabajadora_id' => 'required|exists:trabajadoras,id',
            'fecha_inicio_periodo' => 'required|date',
            'fecha_fin_periodo' => 'required|date|after_or_equal:fecha_inicio_periodo',
        ]);

        // Obtener servicios del periodo que no han sido pagados
        $servicios = HistorialServicio::where('trabajadora_id', $validated['trabajadora_id'])
            ->whereBetween('fecha_servicio', [$validated['fecha_inicio_periodo'], $validated['fecha_fin_periodo']])
            ->whereNull('pago_trabajadora_id')
            ->get();

        if ($servicios->isEmpty()) {
            return redirect()->back()
                ->withErrors(['error' => 'No hay servicios pendientes de pago en este periodo.'])
                ->withInput();
        }

        $totalServicios = $servicios->sum('precio_cobrado');
        $totalComisiones = $servicios->sum('monto_comision');

        $pago = PagoTrabajadora::create([
            'trabajadora_id' => $validated['trabajadora_id'],
            'fecha_inicio_periodo' => $validated['fecha_inicio_periodo'],
            'fecha_fin_periodo' => $validated['fecha_fin_periodo'],
            'total_servicios' => $totalServicios,
            'total_comisiones' => $totalComisiones,
            'monto_pagado' => 0,
            'estado' => 'pendiente',
        ]);

        // Asociar servicios al pago
        $servicios->each(function ($servicio) use ($pago) {
            $servicio->update(['pago_trabajadora_id' => $pago->id]);
        });

        return redirect()->route('pago-trabajadoras.show', $pago)
            ->with('success', 'Pago generado con éxito. Total a pagar: $' . number_format($totalComisiones, 2));
    }

    public function show(PagoTrabajadora $pagoTrabajadora)
    {
        $pagoTrabajadora->load(['trabajadora', 'historialServicios.servicio', 'historialServicios.cliente']);
        return view('pago-trabajadoras.show', compact('pagoTrabajadora'));
    }

    public function edit(PagoTrabajadora $pagoTrabajadora)
    {
        if ($pagoTrabajadora->estado === 'pagado') {
            return redirect()->route('pago-trabajadoras.show', $pagoTrabajadora)
                ->with('warning', 'No se puede editar un pago ya realizado.');
        }
        
        return view('pago-trabajadoras.edit', compact('pagoTrabajadora'));
    }

    public function update(Request $request, PagoTrabajadora $pagoTrabajadora)
    {
        if ($pagoTrabajadora->estado === 'pagado') {
            return redirect()->route('pago-trabajadoras.show', $pagoTrabajadora)
                ->with('error', 'No se puede modificar un pago ya realizado.');
        }

        $validated = $request->validate([
            'monto_pagado' => 'required|numeric|min:0',
            'metodo_pago' => 'required|string|in:efectivo,transferencia,deposito,cheque',
            'fecha_pago' => 'required|date',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $pagoTrabajadora->update($validated + ['estado' => 'pagado']);

        return redirect()->route('pago-trabajadoras.show', $pagoTrabajadora)
            ->with('success', 'Pago registrado con éxito.');
    }

    public function destroy(PagoTrabajadora $pagoTrabajadora)
    {
        if ($pagoTrabajadora->estado === 'pagado') {
            return redirect()->route('pago-trabajadoras.index')
                ->with('error', 'No se puede eliminar un pago ya realizado.');
        }

        // Liberar servicios asociados
        $pagoTrabajadora->historialServicios()->update(['pago_trabajadora_id' => null]);
        
        $pagoTrabajadora->delete();
        
        return redirect()->route('pago-trabajadoras.index')
            ->with('success', 'Pago eliminado con éxito.');
    }

    public function generarSemanal(Request $request)
    {
        $trabajadoras = Trabajadora::where('activo', true)->get();
        
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'trabajadora_id' => 'required|exists:trabajadoras,id',
            ]);
            
            // Obtener la última semana (lunes a domingo)
            $fechaFin = Carbon::now()->endOfWeek();
            $fechaInicio = Carbon::now()->startOfWeek();
            
            return redirect()->route('pago-trabajadoras.create', [
                'trabajadora_id' => $validated['trabajadora_id'],
                'fecha_inicio' => $fechaInicio->format('Y-m-d'),
                'fecha_fin' => $fechaFin->format('Y-m-d'),
            ]);
        }
        
        return view('pago-trabajadoras.generar-semanal', compact('trabajadoras'));
    }

    public function generarPagoRapido($trabajadoraId)
    {
        $trabajadora = Trabajadora::findOrFail($trabajadoraId);
        
        // Obtener servicios pendientes
        $servicios = HistorialServicio::where('trabajadora_id', $trabajadoraId)
            ->whereNull('pago_trabajadora_id')
            ->get();

        if ($servicios->isEmpty()) {
            return redirect()->route('pago-trabajadoras.index')
                ->with('error', 'No hay servicios pendientes de pago para esta trabajadora.');
        }

        $totalServicios = $servicios->sum('precio_cobrado');
        $totalComisiones = $servicios->sum('monto_comision');
        $fechaInicio = $servicios->min('fecha_servicio');
        $fechaFin = $servicios->max('fecha_servicio');

        // Crear el pago
        $pago = PagoTrabajadora::create([
            'trabajadora_id' => $trabajadoraId,
            'fecha_inicio_periodo' => $fechaInicio,
            'fecha_fin_periodo' => $fechaFin,
            'total_servicios' => $totalServicios,
            'total_comisiones' => $totalComisiones,
            'monto_pagado' => 0,
            'fecha_pago' => null,
            'estado' => 'pendiente',
            'metodo_pago' => null,
            'observaciones' => 'Generado automáticamente - ' . $servicios->count() . ' servicio(s)',
        ]);

        // Asociar servicios al pago
        $servicios->each(function ($servicio) use ($pago) {
            $servicio->update(['pago_trabajadora_id' => $pago->id]);
        });

        return redirect()->route('pago-trabajadoras.show', $pago)
            ->with('success', 'Pago generado exitosamente para ' . $trabajadora->nombre . '. Total: $' . number_format($totalComisiones, 2));
    }
}
