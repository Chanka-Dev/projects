<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Lote_inventario;
use App\Services\InventarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventarioController extends Controller
{
    protected $inventarioService;

    public function __construct(InventarioService $inventarioService)
    {
        $this->inventarioService = $inventarioService;
    }

    /**
     * Stock actual de un producto
     */
    public function stock($productoId)
    {
        $producto = Producto::findOrFail($productoId);
        $stockActual = $this->inventarioService->obtenerStockActual($producto);

        return response()->json($stockActual);
    }

    /**
     * Stock general de todos los productos
     */
    public function stockGeneral()
    {
        $productos = Producto::with(['lotes' => function ($query) {
            $query->disponibles();
        }])->get();

        $inventario = $productos->map(function ($producto) {
            return [
                'producto_id' => $producto->id,
                'codigo' => $producto->codigo,
                'nombre' => $producto->nombre,
                'categoria' => $producto->categoria,
                'stock_disponible' => $producto->stockDisponible(),
                'stock_minimo' => $producto->stock_minimo,
                'alerta_bajo_stock' => $producto->stockDisponible() < $producto->stock_minimo,
                'costo_promedio_peps' => $producto->costoPromedioPEPS(),
                'valor_inventario' => $producto->stockDisponible() * $producto->costoPromedioPEPS(),
                'lotes_disponibles' => $producto->lotes->count(),
            ];
        });

        return response()->json([
            'inventario' => $inventario,
            'valor_total' => $inventario->sum('valor_inventario'),
        ]);
    }

    /**
     * Productos con bajo stock (alerta de reorden)
     */
    public function bajoStock()
    {
        $productos = $this->inventarioService->productosBajoStock();

        return response()->json([
            'productos_bajo_stock' => $productos,
            'total_productos' => $productos->count(),
        ]);
    }

    /**
     * Alertas de productos próximos a vencer
     */
    public function vencimientos(Request $request)
    {
        $dias = $request->input('dias', 7);
        $vencimientos = $this->inventarioService->alertarVencimientos($dias);

        return response()->json([
            'productos_por_vencer' => $vencimientos,
            'total_productos' => $vencimientos->count(),
            'dias_alerta' => $dias,
        ]);
    }

    /**
     * Calcular mermas de productos vencidos
     */
    public function mermas()
    {
        $mermas = $this->inventarioService->calcularMermas();

        return response()->json([
            'productos_vencidos' => $mermas,
            'total_mermas' => $mermas->count(),
            'costo_total_merma' => $mermas->sum('costo_merma'),
        ]);
    }

    /**
     * Registrar merma de un lote
     */
    public function registrarMerma($loteId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'observacion' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $lote = Lote_inventario::findOrFail($loteId);
            $movimiento = $this->inventarioService->registrarMerma($lote, $request->observacion);

            return response()->json([
                'message' => 'Merma registrada exitosamente',
                'movimiento' => $movimiento->load('producto', 'lote'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al registrar merma',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ajuste manual de inventario
     */
    public function ajustar($loteId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nueva_cantidad' => 'required|numeric|min:0',
            'motivo' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $lote = Lote_inventario::findOrFail($loteId);
            $movimiento = $this->inventarioService->ajustarInventario(
                $lote,
                $request->nueva_cantidad,
                $request->motivo
            );

            return response()->json([
                'message' => 'Inventario ajustado exitosamente',
                'movimiento' => $movimiento->load('producto', 'lote'),
                'lote_actualizado' => $lote->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al ajustar inventario',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reporte de movimientos de inventario
     */
    public function reporteMovimientos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_desde' => 'required|date',
            'fecha_hasta' => 'required|date|after_or_equal:fecha_desde',
            'producto_id' => 'nullable|exists:productos,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $movimientos = $this->inventarioService->reporteMovimientos(
            $request->fecha_desde,
            $request->fecha_hasta,
            $request->producto_id
        );

        return response()->json([
            'fecha_desde' => $request->fecha_desde,
            'fecha_hasta' => $request->fecha_hasta,
            'movimientos' => $movimientos,
            'total_movimientos' => $movimientos->count(),
        ]);
    }

    /**
     * Vista general de kardex
     */
    public function kardex(Request $request)
    {
        $productos = Producto::orderBy('nombre')->get();
        $productoId = $request->input('producto_id');
        
        $fechaDesde = $request->input('fecha_desde', now()->subMonth()->format('Y-m-d'));
        $fechaHasta = $request->input('fecha_hasta', now()->format('Y-m-d'));

        $producto = null;
        
        // Si hay producto_id, buscar el producto específico
        if ($productoId) {
            $producto = Producto::findOrFail($productoId);
        }
        
        // Siempre cargar movimientos (con o sin filtro de producto)
        $movimientos = $this->inventarioService->reporteMovimientos(
            $fechaDesde,
            $fechaHasta,
            $productoId ?: null
        );

        return view('inventario.kardex', compact(
            'productos',
            'producto',
            'movimientos',
            'fechaDesde',
            'fechaHasta'
        ));
    }

    // ==================== MÉTODOS PARA VISTAS WEB ====================

    /**
     * Vista principal de inventario
     */
    public function index()
    {
        $productos = Producto::orderBy('nombre')
            ->paginate(20);

        $categorias = Producto::distinct()->pluck('categoria');

        return view('inventario.index', compact('productos', 'categorias'));
    }

    /**
     * Vista de alertas (bajo stock y vencimientos)
     */
    public function alertas()
    {
        $productosStockBajo = $this->inventarioService->productosBajoStock();
        $lotesProximosVencer = $this->inventarioService->alertarVencimientos(7);
        $mermas = $this->inventarioService->calcularMermas();

        return view('inventario.alertas', compact(
            'productosStockBajo',
            'lotesProximosVencer',
            'mermas'
        ));
    }

    /**
     * Vista de Kardex de producto específico
     */
    public function kardexProducto($productoId, Request $request)
    {
        $producto = Producto::findOrFail($productoId);
        
        $fechaInicio = $request->input('fecha_inicio', now()->subMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', now()->format('Y-m-d'));

        $movimientos = $this->inventarioService->reporteMovimientos(
            $fechaInicio,
            $fechaFin,
            $productoId
        );

        return view('inventario.kardex', compact('producto', 'movimientos'));
    }
}
