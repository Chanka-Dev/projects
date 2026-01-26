<?php

namespace App\Http\Controllers;

use App\Models\Gasto_operativo;
use App\Models\Plan_cuenta;
use App\Models\Categoria_gasto;
use App\Services\ContabilidadService;
use Illuminate\Http\Request;

class GastoOperativoController extends Controller
{
    protected $contabilidadService;

    public function __construct(ContabilidadService $contabilidadService)
    {
        $this->contabilidadService = $contabilidadService;
    }

    /**
     * Mostrar listado de gastos
     */
    public function index(Request $request)
    {
        $fecha_desde = $request->input('fecha_desde', now()->startOfMonth()->format('Y-m-d'));
        $fecha_hasta = $request->input('fecha_hasta', now()->endOfMonth()->format('Y-m-d'));

        $gastos = Gasto_operativo::with(['categoria', 'cuenta'])
            ->whereBetween('fecha_gasto', [$fecha_desde, $fecha_hasta])
            ->orderBy('fecha_gasto', 'desc')
            ->paginate(20);

        $totalGastos = Gasto_operativo::whereBetween('fecha_gasto', [$fecha_desde, $fecha_hasta])->sum('monto');

        return view('gastos-operativos.index', compact('gastos', 'fecha_desde', 'fecha_hasta', 'totalGastos'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $categorias = Categoria_gasto::where('activa', true)->orderBy('nombre')->get();
        $cuentas = Plan_cuenta::where('codigo', 'like', '6%')
            ->where('acepta_movimientos', true)
            ->orderBy('codigo')
            ->get();

        return view('gastos-operativos.create', compact('categorias', 'cuentas'));
    }

    /**
     * Guardar nuevo gasto
     */
    public function store(Request $request)
    {
        $request->validate([
            'fecha_gasto' => 'required|date',
            'descripcion' => 'required|max:500',
            'monto' => 'required|numeric|min:0.01',
            'categoria_id' => 'required|exists:categoria_gastos,id',
            'cuenta_id' => 'required|exists:plan_cuentas,id',
            'tipo_comprobante' => 'nullable|max:50',
            'proveedor_id' => 'nullable|exists:proveedores,id',
        ]);

        $gasto = Gasto_operativo::create([
            'fecha_gasto' => $request->fecha_gasto,
            'descripcion' => $request->descripcion,
            'monto' => $request->monto,
            'categoria_gasto_id' => $request->categoria_id,
            'cuenta_id' => $request->cuenta_id,
            'tipo_comprobante' => $request->tipo_comprobante,
            'proveedor_id' => $request->proveedor_id,
            'estado' => 'pagado',
            'estado_contable' => 'no_contabilizado',
            'usuario_id' => auth()->id() ?? 1,
        ]);

        // Generar asiento contable automáticamente
        if ($gasto) {
            try {
                $this->contabilidadService->generarAsientoGasto($gasto);
            } catch (\Exception $e) {
                // Log error pero no fallar
                \Log::error('Error generando asiento para gasto: ' . $e->getMessage());
            }
        }

        return redirect()->route('gastos-operativos.index')
            ->with('success', 'Gasto registrado exitosamente');
    }

    /**
     * Mostrar detalle de gasto
     */
    public function show(Gasto_operativo $gastosOperativo)
    {
        $gastosOperativo->load(['categoria', 'cuenta', 'proveedor', 'asiento']);
        
        return view('gastos-operativos.show', compact('gastosOperativo'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Gasto_operativo $gastosOperativo)
    {
        // No permitir editar si ya está contabilizado
        if ($gastosOperativo->asiento_id) {
            return redirect()->route('gastos-operativos.index')
                ->with('error', 'No se puede editar un gasto que ya está contabilizado');
        }

        $categorias = Categoria_gasto::where('activa', true)->orderBy('nombre')->get();
        $cuentas = Plan_cuenta::where('codigo', 'like', '6%')
            ->where('acepta_movimientos', true)
            ->orderBy('codigo')
            ->get();

        return view('gastos-operativos.edit', compact('gastosOperativo', 'categorias', 'cuentas'));
    }

    /**
     * Actualizar gasto
     */
    public function update(Request $request, Gasto_operativo $gastosOperativo)
    {
        // No permitir editar si ya está contabilizado
        if ($gastosOperativo->asiento_id) {
            return redirect()->route('gastos-operativos.index')
                ->with('error', 'No se puede editar un gasto que ya está contabilizado');
        }

        $request->validate([
            'fecha_gasto' => 'required|date',
            'descripcion' => 'required|max:500',
            'monto' => 'required|numeric|min:0.01',
            'categoria_id' => 'required|exists:categoria_gastos,id',
            'cuenta_id' => 'required|exists:plan_cuentas,id',
            'tipo_comprobante' => 'nullable|max:50',
            'proveedor_id' => 'nullable|exists:proveedores,id',
        ]);

        $gastosOperativo->update([
            'fecha_gasto' => $request->fecha_gasto,
            'descripcion' => $request->descripcion,
            'monto' => $request->monto,
            'categoria_gasto_id' => $request->categoria_id,
            'cuenta_id' => $request->cuenta_id,
            'tipo_comprobante' => $request->tipo_comprobante,
            'proveedor_id' => $request->proveedor_id,
        ]);

        return redirect()->route('gastos-operativos.index')
            ->with('success', 'Gasto actualizado exitosamente');
    }

    /**
     * Eliminar gasto
     */
    public function destroy(Gasto_operativo $gastosOperativo)
    {
        // No permitir eliminar si ya está contabilizado
        if ($gastosOperativo->asiento_id) {
            return redirect()->route('gastos-operativos.index')
                ->with('error', 'No se puede eliminar un gasto que ya está contabilizado');
        }

        $gastosOperativo->delete();

        return redirect()->route('gastos-operativos.index')
            ->with('success', 'Gasto eliminado exitosamente');
    }
}
