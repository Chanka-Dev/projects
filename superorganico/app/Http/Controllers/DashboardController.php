<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Lote_inventario;
use App\Services\ContabilidadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $contabilidadService;

    public function __construct(ContabilidadService $contabilidadService)
    {
        $this->contabilidadService = $contabilidadService;
    }

    public function index()
    {
        // Ventas de hoy
        $totalVentasHoy = Venta::whereDate('fecha_hora', today())
            ->sum('total');

        // Total productos
        $productosActivos = Producto::count();

        // Productos próximos a vencer (próximos 7 días)
        $productosVencimiento = \DB::table('lote_inventarios')
            ->where('cantidad_actual', '>', 0)
            ->whereNotNull('fecha_caducidad')
            ->whereBetween('fecha_caducidad', [now(), now()->addDays(7)])
            ->distinct()
            ->count('producto_id');

        // Saldo IVA (mes actual)
        $saldoIVA = $this->contabilidadService->calcularSaldoIVA(
            now()->startOfMonth()->format('Y-m-d'),
            now()->endOfMonth()->format('Y-m-d')
        );

        // Últimas 5 ventas
        $ultimasVentas = Venta::with('cliente')
            ->orderBy('fecha_hora', 'desc')
            ->limit(5)
            ->get();

        // Productos más vendidos (últimos 30 días)
        $productosMasVendidos = DB::table('detalle_ventas')
            ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
            ->where('ventas.fecha_hora', '>=', now()->subDays(30))
            ->select(
                'productos.nombre',
                DB::raw('SUM(detalle_ventas.cantidad) as total_vendido'),
                DB::raw('SUM(detalle_ventas.subtotal) as total_ingresos')
            )
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('total_vendido')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalVentasHoy',
            'productosActivos',
            'productosVencimiento',
            'saldoIVA',
            'ultimasVentas',
            'productosMasVendidos'
        ));
    }
}
