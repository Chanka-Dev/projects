<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Trabajadora;
use App\Models\Pago;
use App\Models\PagoTrabajadora;
use App\Models\HistorialServicio;
use App\Models\Recordatorio;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Citas de hoy
        $citasHoy = Cita::whereDate('fecha', today())
            ->with(['cliente', 'trabajadora', 'servicios'])
            ->orderBy('id')
            ->get();
        
        // Próximas citas (próximos 7 días)
        $citasProximas = Cita::whereBetween('fecha', [today()->addDay(), today()->addDays(7)])
            ->with('cliente', 'trabajadora')
            ->orderBy('fecha')
            ->take(5)
            ->get();
        
        // Estadísticas generales
        $totalClientes = Cliente::count();
        $totalTrabajadoras = Trabajadora::where('activo', true)->count();
        
        // Citas del mes actual
        $citasMes = Cita::whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->count();
        
        $citasCompletadasMes = Cita::whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->where('estado', 'completada')
            ->count();
        
        // Ingresos del mes
        $ingresosMes = Pago::whereMonth('fecha_pago', now()->month)
            ->whereYear('fecha_pago', now()->year)
            ->where('estado', 'completado')
            ->sum('monto_total');
        
        // Ingresos de hoy
        $ingresosHoy = Pago::whereDate('fecha_pago', today())
            ->where('estado', 'completado')
            ->sum('monto_total');
        
        // Comisiones pendientes de pago
        $comisionesPendientes = HistorialServicio::whereNull('pago_trabajadora_id')
            ->sum('monto_comision');
        
        // Pagos pendientes de clientes
        $pagosPendientes = Pago::where('estado', 'pendiente')->count();
        $montoPendiente = Pago::where('estado', 'pendiente')->sum('monto_total');
        
        // Citas pendientes sin pago
        $citasSinPago = Cita::where('estado', 'completada')
            ->whereDoesntHave('pago')
            ->count();
        
        // Servicios más solicitados (este mes)
        $serviciosPopulares = DB::table('citas_servicios')
            ->join('servicios', 'citas_servicios.servicio_id', '=', 'servicios.id')
            ->join('citas', 'citas_servicios.cita_id', '=', 'citas.id')
            ->whereMonth('citas.fecha', now()->month)
            ->whereYear('citas.fecha', now()->year)
            ->select('servicios.nombre', DB::raw('COUNT(*) as total'))
            ->groupBy('servicios.id', 'servicios.nombre')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        // Trabajadoras con más citas este mes
        $trabajadorasTop = Cita::whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->select('trabajadora_id', DB::raw('COUNT(*) as total_citas'))
            ->with('trabajadora')
            ->groupBy('trabajadora_id')
            ->orderByDesc('total_citas')
            ->limit(5)
            ->get();
        
        // Recordatorios pendientes
        $recordatoriosPendientes = Recordatorio::where('estado', 'pendiente')
            ->whereDate('fecha_envio', '<=', today()->addDays(3))
            ->count();

        return view('dashboard', compact(
            'citasHoy',
            'citasProximas',
            'totalClientes',
            'totalTrabajadoras',
            'citasMes',
            'citasCompletadasMes',
            'ingresosMes',
            'ingresosHoy',
            'comisionesPendientes',
            'pagosPendientes',
            'montoPendiente',
            'citasSinPago',
            'serviciosPopulares',
            'trabajadorasTop',
            'recordatoriosPendientes'
        ));
    }
}