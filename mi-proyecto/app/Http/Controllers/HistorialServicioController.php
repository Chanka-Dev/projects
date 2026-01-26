<?php

namespace App\Http\Controllers;

use App\Models\HistorialServicio;
use App\Models\Cliente;
use Illuminate\Http\Request;

class HistorialServicioController extends Controller
{
    public function index(Request $request)
    {
        $query = HistorialServicio::with(['cliente', 'trabajadora', 'servicio'])
            ->orderBy('fecha_servicio', 'desc');

        // Filtro por cliente
        if ($request->has('cliente_id') && $request->cliente_id) {
            $query->where('cliente_id', $request->cliente_id);
        }

        // Filtro por trabajadora
        if ($request->has('trabajadora_id') && $request->trabajadora_id) {
            $query->where('trabajadora_id', $request->trabajadora_id);
        }

        // Filtro por servicio
        if ($request->has('servicio_id') && $request->servicio_id) {
            $query->where('servicio_id', $request->servicio_id);
        }

        // Filtro por rango de fechas
        if ($request->has('fecha_desde') && $request->fecha_desde) {
            $query->where('fecha_servicio', '>=', $request->fecha_desde);
        }
        if ($request->has('fecha_hasta') && $request->fecha_hasta) {
            $query->where('fecha_servicio', '<=', $request->fecha_hasta);
        }

        // Búsqueda general
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->whereHas('cliente', function ($subQ) use ($request) {
                    $subQ->where('nombre', 'like', '%' . $request->search . '%')
                         ->orWhere('telefono', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('servicio', function ($subQ) use ($request) {
                    $subQ->where('nombre', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('trabajadora', function ($subQ) use ($request) {
                    $subQ->where('nombre', 'like', '%' . $request->search . '%');
                });
            });
        }

        $historialServicios = $query->paginate(15);
        
        // Para los filtros
        $clientes = Cliente::orderBy('nombre')->get();
        
        // Estadísticas
        $totalServicios = $query->count();
        $totalIngresos = $query->sum('precio_cobrado');
        
        return view('historial-servicios.index', compact(
            'historialServicios',
            'clientes',
            'totalServicios',
            'totalIngresos'
        ));
    }

    public function show(HistorialServicio $historialServicio)
    {
        $historialServicio->load(['cliente', 'trabajadora', 'servicio']);
        return view('historial-servicios.show', compact('historialServicio'));
    }

    // Solo permitir eliminar en casos excepcionales
    public function destroy(HistorialServicio $historialServicio)
    {
        $historialServicio->delete();
        return redirect()->route('historial-servicios.index')
            ->with('success', 'Registro eliminado del historial');
    }
}