<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Detalle_venta;
use App\Services\ContabilidadService;
use App\Services\InventarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VentaController extends Controller
{
    protected $contabilidadService;
    protected $inventarioService;

    public function __construct(
        ContabilidadService $contabilidadService,
        InventarioService $inventarioService
    ) {
        $this->contabilidadService = $contabilidadService;
        $this->inventarioService = $inventarioService;
    }

    /**
     * Listar ventas con filtros
     */
    public function index(Request $request)
    {
        $query = Venta::with(['cliente', 'usuario', 'detalles.producto', 'asientoContable']);

        // Filtros
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_hora', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_hora', '<=', $request->fecha_hasta);
        }
        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }
        if ($request->filled('tipo_pago')) {
            $query->where('tipo_pago', $request->tipo_pago);
        }
        if ($request->filled('estado_contable')) {
            $query->where('estado_contable', $request->estado_contable);
        }

        $ventas = $query->orderBy('fecha_hora', 'desc')->paginate(20);
        $clientes = Cliente::orderBy('nombre')->get();

        return view('ventas.index', compact('ventas', 'clientes'));
    }

    /**
     * Formulario para crear venta
     */
    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $productos = Producto::orderBy('nombre')->get();
        
        // Agregar stock disponible a cada producto
        $productos->each(function ($producto) {
            $producto->stock_actual = $producto->stockDisponible();
        });
        
        return view('ventas.create', compact('clientes', 'productos'));
    }

    /**
     * Mostrar una venta específica
     */
    public function show($id)
    {
        $venta = Venta::with([
            'cliente',
            'usuario',
            'detalles.producto',
            'detalles.lote',
            'asientoContable.detalles.cuenta'
        ])->findOrFail($id);

        return view('ventas.show', compact('venta'));
    }

    /**
     * Crear nueva venta con contabilización automática
     */
    public function store(Request $request)
    {
        // Debug para ver qué está llegando
        \Log::info('Datos recibidos en store:', $request->all());
        
        $validator = Validator::make($request->all(), [
            'cliente_id' => 'nullable|exists:clientes,id',
            'fecha' => 'required|date',
            'tipo_pago' => 'required|in:efectivo,tarjeta,transferencia',
            'observaciones' => 'nullable|string|max:500',
            'detalles' => 'required|array|min:1',
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|numeric|min:0.01',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            \Log::error('Validación fallida:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            \Log::info('Iniciando transacción de venta');
            
            $resultado = DB::transaction(function () use ($request) {
                \Log::info('Dentro de la transacción');
                
                // Calcular totales desde el request
                $subtotal = floatval($request->input('subtotal', 0));
                $iva = floatval($request->input('iva', 0));
                $it = floatval($request->input('it', 0));
                $total = floatval($request->input('total', 0));
                
                \Log::info('Totales: subtotal=' . $subtotal . ', iva=' . $iva . ', it=' . $it . ', total=' . $total);

                // Crear venta
                $venta = Venta::create([
                    'fecha_hora' => now(),
                    'fecha' => $request->fecha,
                    'subtotal' => $subtotal,
                    'iva' => $iva,
                    'it' => $it,
                    'descuento' => 0,
                    'impuesto' => $iva,  // Mantener compatibilidad con columna legacy
                    'total' => $total,
                    'tipo_pago' => $request->tipo_pago ?? 'efectivo',
                    'observaciones' => $request->observaciones,
                    'estado_contable' => 'no_contabilizado',
                    'usuario_id' => auth()->id() ?? 1,
                    'cliente_id' => $request->cliente_id,
                ]);
                
                \Log::info('Venta creada con ID: ' . $venta->id);

                // Crear detalles y descontar stock usando PEPS
                foreach ($request->detalles as $detalleData) {
                    \Log::info('Procesando producto: ' . $detalleData['producto_id']);
                    $producto = Producto::findOrFail($detalleData['producto_id']);

                    // Descontar stock con PEPS
                    $lotesConsumidos = $this->inventarioService->descontarStock(
                        $producto,
                        $detalleData['cantidad'],
                        'salida',
                        $venta
                    );
                    \Log::info('Lotes consumidos: ' . count($lotesConsumidos));

                    // Crear detalle de venta por cada lote consumido
                    foreach ($lotesConsumidos as $loteInfo) {
                        $venta->detalles()->create([
                            'producto_id' => $producto->id,
                            'lote_id' => $loteInfo['lote']->id,
                            'cantidad' => $loteInfo['cantidad'],
                            'precio_unitario' => $detalleData['precio_unitario'],
                            'subtotal' => $loteInfo['cantidad'] * $detalleData['precio_unitario'],
                        ]);
                    }
                }
                \Log::info('Detalles creados');

                // Contabilizar automáticamente
                $asiento = $this->contabilidadService->generarAsientoVenta($venta);
                \Log::info('Asiento generado: ' . $asiento->id);

                return redirect()->route('ventas.show', $venta->id)
                    ->with('success', 'Venta creada y contabilizada exitosamente');
            });
            
            \Log::info('Transacción completada, retornando resultado');
            return $resultado;
            
        } catch (\Exception $e) {
            \Log::error('Error en store venta: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->with('error', 'Error al crear venta: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Contabilizar venta manualmente (si no se hizo automáticamente)
     */
    public function contabilizar($id)
    {
        try {
            $venta = Venta::findOrFail($id);

            if ($venta->estaContabilizada()) {
                return response()->json([
                    'error' => 'La venta ya está contabilizada'
                ], 400);
            }

            $asiento = $venta->contabilizar();

            return response()->json([
                'message' => 'Venta contabilizada exitosamente',
                'asiento' => $asiento->load('detalles.cuenta')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al contabilizar',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Anular venta (reversa de asiento contable)
     */
    public function anular($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $venta = Venta::findOrFail($id);

                if (!$venta->estaContabilizada()) {
                    return response()->json([
                        'error' => 'La venta no está contabilizada'
                    ], 400);
                }

                // Anular asiento contable
                $venta->asientoContable->anular();

                // Devolver stock a inventario
                foreach ($venta->detalles as $detalle) {
                    // Crear movimiento de ajuste para reintegrar stock
                    $this->inventarioService->ajustarInventario(
                        $detalle->lote,
                        $detalle->lote->cantidad_disponible + $detalle->cantidad,
                        "Anulación de venta #{$venta->numero_venta}"
                    );
                }

                // Actualizar estado
                $venta->update([
                    'estado_contable' => 'anulado',
                    'asiento_id' => null
                ]);

                return response()->json([
                    'message' => 'Venta anulada exitosamente',
                    'venta' => $venta
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al anular venta',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reporte diario de ventas
     */
    public function reporteDiario(Request $request)
    {
        $fecha = $request->input('fecha', now()->format('Y-m-d'));

        $ventas = Venta::whereDate('fecha', $fecha)
            ->with(['detalles.producto', 'cliente'])
            ->get();

        $resumen = [
            'fecha' => $fecha,
            'total_ventas' => $ventas->count(),
            'monto_total' => $ventas->sum('total'),
            'por_tipo_pago' => [
                'efectivo' => $ventas->where('tipo_pago', 'efectivo')->sum('total'),
                'tarjeta' => $ventas->where('tipo_pago', 'tarjeta')->sum('total'),
                'transferencia' => $ventas->where('tipo_pago', 'transferencia')->sum('total'),
            ],
            'ventas' => $ventas,
        ];

        return response()->json($resumen);
    }

    /**
     * Top productos vendidos
     */
    public function topProductos(Request $request)
    {
        $fechaDesde = $request->input('fecha_desde', now()->subMonth()->format('Y-m-d'));
        $fechaHasta = $request->input('fecha_hasta', now()->format('Y-m-d'));
        $limit = $request->input('limit', 10);

        $topProductos = DB::table('detalle_ventas')
            ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
            ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->whereBetween('ventas.fecha', [$fechaDesde, $fechaHasta])
            ->select(
                'productos.id',
                'productos.nombre',
                DB::raw('SUM(detalle_ventas.cantidad) as cantidad_vendida'),
                DB::raw('SUM(detalle_ventas.subtotal) as monto_total')
            )
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('cantidad_vendida')
            ->limit($limit)
            ->get();

        return response()->json($topProductos);
    }

    /**
     * Editar venta
     */
    public function edit($id)
    {
        $venta = Venta::with(['cliente', 'detalles.producto'])->findOrFail($id);
        $clientes = Cliente::orderBy('nombre')->get();
        $productos = Producto::orderBy('nombre')->get();
        
        return view('ventas.edit', compact('venta', 'clientes', 'productos'));
    }

    /**
     * Actualizar venta
     */
    public function update(Request $request, $id)
    {
        $venta = Venta::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_hora' => 'required|date',
            'tipo_comprobante' => 'required|in:factura,nota_venta',
            'numero_comprobante' => 'required|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $venta->update([
            'cliente_id' => $request->cliente_id,
            'fecha_hora' => $request->fecha_hora,
            'tipo_comprobante' => $request->tipo_comprobante,
            'numero_comprobante' => $request->numero_comprobante,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('ventas.index')
            ->with('success', 'Venta actualizada exitosamente');
    }

    /**
     * Eliminar venta
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $venta = Venta::findOrFail($id);
            
            // Eliminar detalles
            $venta->detalles()->delete();
            
            // Eliminar asiento contable si existe
            if ($venta->asiento_contable_id) {
                $asiento = $venta->asientoContable;
                if ($asiento) {
                    $asiento->detalles()->delete();
                    $asiento->delete();
                }
            }
            
            // Eliminar venta
            $venta->delete();
            
            DB::commit();
            
            return redirect()->route('ventas.index')
                ->with('success', 'Venta eliminada exitosamente');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('ventas.index')
                ->with('error', 'Error al eliminar la venta: ' . $e->getMessage());
        }
    }
}
