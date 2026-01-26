<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Proveedores;
use App\Models\Producto;
use App\Models\Lote_inventario;
use App\Services\ContabilidadService;
use App\Services\InventarioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CompraController extends Controller
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
     * Listar compras
     */
    public function index(Request $request)
    {
        $query = Compra::with(['proveedor', 'usuario', 'detalles.producto', 'lotes', 'asientoContable']);

        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }
        if ($request->filled('proveedor_id')) {
            $query->where('proveedor_id', $request->proveedor_id);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $compras = $query->orderBy('fecha', 'desc')->paginate(20);
        $proveedores = Proveedores::orderBy('nombre')->get();

        return view('compras.index', compact('compras', 'proveedores'));
    }

    /**
     * Formulario para crear compra
     */
    public function create()
    {
        $proveedores = Proveedores::orderBy('nombre')->get();
        $productos = Producto::orderBy('nombre')->get();
        
        // Agregar stock disponible a cada producto
        $productos->each(function ($producto) {
            $producto->stock_actual = $producto->stockDisponible();
        });
        
        return view('compras.create', compact('proveedores', 'productos'));
    }

    /**
     * Mostrar compra específica
     */
    public function show($id)
    {
        $compra = Compra::with([
            'proveedor',
            'usuario',
            'detalles.producto',
            'lotes.producto',
            'asientoContable.detalles.cuenta'
        ])->findOrFail($id);

        return view('compras.show', compact('compra'));
    }

    /**
     * Crear nueva compra
     */
    public function store(Request $request)
    {
        // Debug temporal
        \Log::info('Compra Store Request:', [
            'all' => $request->all(),
            'fecha' => $request->fecha,
            'has_detalles' => $request->has('detalles'),
            'detalles_count' => is_array($request->detalles) ? count($request->detalles) : 0
        ]);

        $validator = Validator::make($request->all(), [
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha' => 'required|date',
            'numero_factura' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string|max:500',
            'detalles' => 'required|array|min:1',
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|numeric|min:0.01',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            \Log::error('Compra Validation Failed:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            return DB::transaction(function () use ($request) {
                // Calcular total
                $total = 0;
                foreach ($request->detalles as $detalle) {
                    $total += $detalle['cantidad'] * $detalle['precio_unitario'];
                }

                // Crear compra
                $compra = Compra::create([
                    'numero_compra' => null,
                    'proveedor_id' => $request->proveedor_id,
                    'fecha' => $request->fecha,
                    'numero_factura' => $request->numero_factura,
                    'subtotal' => $total,
                    'total' => $total,
                    'observaciones' => $request->observaciones,
                    'estado' => 'pendiente',
                    'estado_contable' => 'no_contabilizado',
                    'usuario_id' => auth()->id() ?? 1,
                ]);

                // Crear detalles
                foreach ($request->detalles as $detalleData) {
                    $compra->detalles()->create([
                        'producto_id' => $detalleData['producto_id'],
                        'cantidad' => $detalleData['cantidad'],
                        'costo_unitario' => $detalleData['precio_unitario'],
                        'subtotal' => $detalleData['cantidad'] * $detalleData['precio_unitario'],
                    ]);
                }

                return redirect()->route('compras.show', $compra->id)
                    ->with('success', 'Compra creada exitosamente. Recuerde marcarla como recibida para generar los lotes.');
            });
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear compra: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Marcar compra como recibida y generar lotes
     */
    public function recibir($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_recepcion' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            return DB::transaction(function () use ($id, $request) {
                $compra = Compra::findOrFail($id);

                if ($compra->estado === 'recibida') {
                    return redirect()->back()
                        ->with('error', 'La compra ya fue recibida');
                }

                // Generar lotes para cada detalle
                foreach ($compra->detalles as $detalle) {
                    $fechaCaducidad = null;
                    if ($detalle->producto->perecedero) {
                        $fechaCaducidad = $request->input('fecha_caducidad_' . $detalle->id);
                    }

                    $this->inventarioService->ingresarStock([
                        'producto_id' => $detalle->producto_id,
                        'proveedor_id' => $compra->proveedor_id,
                        'compra_id' => $compra->id,
                        'numero_lote' => null,
                        'cantidad_inicial' => $detalle->cantidad,
                        'cantidad_actual' => $detalle->cantidad,
                        'costo_unitario' => $detalle->costo_unitario,
                        'fecha_ingreso' => $request->fecha_recepcion ?? now(),
                        'fecha_caducidad' => $fechaCaducidad,
                    ]);
                }

                $compra->update(['estado' => 'recibida']);
                $asiento = $this->contabilidadService->generarAsientoCompra($compra);

                return redirect()->route('compras.show', $compra->id)
                    ->with('success', 'Compra recibida y contabilizada exitosamente');
            });
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al recibir compra: ' . $e->getMessage());
        }
    }

    /**
     * Pagar compra
     */
    public function pagar($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cuenta_pago' => 'required|string',
            'fecha_pago' => 'required|date',
            'referencia' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $compra = Compra::findOrFail($id);

            if ($compra->estado !== 'recibida') {
                return response()->json(['error' => 'Solo se pueden pagar compras recibidas'], 400);
            }

            $asientoPago = $this->contabilidadService->generarAsientoPagoCompra(
                $compra,
                $request->cuenta_pago
            );

            return response()->json([
                'message' => 'Pago registrado exitosamente',
                'asiento_pago' => $asientoPago->load('detalles.cuenta')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al registrar pago',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reporte por proveedor
     */
    public function reportePorProveedor(Request $request)
    {
        $fechaDesde = $request->input('fecha_desde', now()->subMonth()->format('Y-m-d'));
        $fechaHasta = $request->input('fecha_hasta', now()->format('Y-m-d'));

        $comprasPorProveedor = DB::table('compras')
            ->join('proveedores', 'compras.proveedor_id', '=', 'proveedores.id')
            ->whereBetween('compras.fecha', [$fechaDesde, $fechaHasta])
            ->select(
                'proveedores.id',
                'proveedores.nombre',
                DB::raw('COUNT(compras.id) as total_compras'),
                DB::raw('SUM(compras.total) as monto_total')
            )
            ->groupBy('proveedores.id', 'proveedores.nombre')
            ->orderByDesc('monto_total')
            ->get();

        return response()->json($comprasPorProveedor);
    }

    /**
     * Compras pendientes de pago
     */
    public function pendientesPago()
    {
        $compras = Compra::where('estado', 'recibida')
            ->whereHas('asientoContable')
            ->with(['proveedor', 'asientoContable'])
            ->orderBy('fecha', 'asc')
            ->get();

        return response()->json([
            'compras' => $compras,
            'total_deuda' => $compras->sum('total'),
        ]);
    }

    /**
     * Editar compra
     */
    public function edit($id)
    {
        $compra = Compra::with(['proveedor', 'detalles.producto'])->findOrFail($id);
        $proveedores = Proveedores::orderBy('nombre')->get();
        $productos = Producto::orderBy('nombre')->get();
        
        return view('compras.edit', compact('compra', 'proveedores', 'productos'));
    }

    /**
     * Actualizar compra
     */
    public function update(Request $request, $id)
    {
        $compra = Compra::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha' => 'required|date',
            'numero_factura' => 'required|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $compra->update([
            'proveedor_id' => $request->proveedor_id,
            'fecha' => $request->fecha,
            'numero_factura' => $request->numero_factura,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('compras.index')
            ->with('success', 'Compra actualizada exitosamente');
    }

    /**
     * Eliminar compra
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $compra = Compra::findOrFail($id);
            
            // Eliminar detalles
            $compra->detalles()->delete();
            
            // Eliminar lotes asociados
            Lote_inventario::where('compra_id', $id)->delete();
            
            // Eliminar compra
            $compra->delete();
            
            DB::commit();
            
            return redirect()->route('compras.index')
                ->with('success', 'Compra eliminada exitosamente');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('compras.index')
                ->with('error', 'Error al eliminar la compra: ' . $e->getMessage());
        }
    }
}
