<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Plan_cuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    /**
     * Listar productos
     */
    public function index(Request $request)
    {
        $query = Producto::with(['cuentaInventario', 'cuentaCostoVenta', 'cuentaIngreso']);

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('codigo', 'like', "%{$buscar}%");
            });
        }

        if ($request->filled('tipo')) {
            $query->where('categoria', $request->tipo);
        }

        $productos = $query->orderBy('nombre')->paginate(20);

        $productos->each(function ($producto) {
            $producto->stock_actual = $producto->stockDisponible();
            $producto->costo_peps = $producto->costoPromedioPEPS();
        });

        return view('productos.index', compact('productos'));
    }

    /**
     * Formulario para crear producto
     */
    public function create()
    {
        $cuentasInventario = Plan_cuenta::where('activa', true)
            ->where('codigo', 'like', '1.1.3%')
            ->orderBy('codigo')
            ->get();
        
        $cuentasCostoVenta = Plan_cuenta::where('activa', true)
            ->where('codigo', 'like', '6%')
            ->orderBy('codigo')
            ->get();
        
        $cuentasIngreso = Plan_cuenta::where('activa', true)
            ->where('codigo', 'like', '5%')
            ->orderBy('codigo')
            ->get();
        
        return view('productos.create', compact('cuentasInventario', 'cuentasCostoVenta', 'cuentasIngreso'));
    }

    /**
     * Mostrar producto con detalle
     */
    public function show($id)
    {
        $producto = Producto::with([
            'cuentaInventario',
            'cuentaCostoVenta',
            'cuentaIngreso',
            'lotes' => function ($query) {
                $query->disponibles()->ordenarPEPS();
            }
        ])->findOrFail($id);

        $stock_disponible = $producto->stockDisponible();
        $costo_promedio_peps = $producto->costoPromedioPEPS();
        $margen_utilidad = $producto->margenUtilidad();
        
        return view('productos.show', compact(
            'producto',
            'stock_disponible',
            'costo_promedio_peps',
            'margen_utilidad'
        ));
    }

    /**
     * Crear producto
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'nullable|string|max:50|unique:productos,codigo',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:verdura,fruta,grano,lacteo,otro',
            'precio_venta' => 'required|numeric|min:0',
            'unidad_medida' => 'required|in:kg,unidad,litro,bolsa,caja',
            'stock_minimo' => 'required|numeric|min:0',
            'perecedero' => 'nullable|boolean',
            'plan_cuenta_inventario_id' => 'nullable|exists:plan_cuentas,id',
            'plan_cuenta_costo_venta_id' => 'nullable|exists:plan_cuentas,id',
            'plan_cuenta_ingreso_id' => 'nullable|exists:plan_cuentas,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $cuentas = $this->obtenerCuentasDefecto();

            $producto = Producto::create([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'categoria' => $request->tipo ?? 'otros',
                'tipo' => $request->tipo ?? 'otro',
                'precio_compra' => 0,
                'precio_venta' => $request->precio_venta,
                'unidad_medida' => $request->unidad_medida,
                'stock_minimo' => $request->stock_minimo ?? 0,
                'perecedero' => $request->perecedero ? 1 : 0,
                'activo' => $request->activo ? 1 : 0,
                'dias_alerta_vencimiento' => $request->dias_alerta_vencimiento ?? 7,
                'cuenta_inventario_id' => $request->plan_cuenta_inventario_id ?? $cuentas['inventario'],
                'cuenta_costo_venta_id' => $request->plan_cuenta_costo_venta_id ?? $cuentas['costo_venta'],
                'cuenta_ingreso_id' => $request->plan_cuenta_ingreso_id ?? $cuentas['ingreso'],
            ]);

            return redirect()->route('productos.index')
                ->with('success', 'Producto creado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al crear producto: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Formulario para editar producto
     */
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        
        $cuentasInventario = Plan_cuenta::where('activa', true)
            ->where('codigo', 'like', '1.1.3%')
            ->orderBy('codigo')
            ->get();
        
        $cuentasCostoVenta = Plan_cuenta::where('activa', true)
            ->where('codigo', 'like', '6%')
            ->orderBy('codigo')
            ->get();
        
        $cuentasIngreso = Plan_cuenta::where('activa', true)
            ->where('codigo', 'like', '5%')
            ->orderBy('codigo')
            ->get();
        
        return view('productos.edit', compact('producto', 'cuentasInventario', 'cuentasCostoVenta', 'cuentasIngreso'));
    }

    /**
     * Actualizar producto
     */
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'codigo' => "nullable|string|max:50|unique:productos,codigo,{$id}",
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:verdura,fruta,grano,lacteo,otro',
            'precio_venta' => 'required|numeric|min:0',
            'unidad_medida' => 'required|in:kg,unidad,litro,bolsa,caja',
            'stock_minimo' => 'required|numeric|min:0',
            'perecedero' => 'nullable|boolean',
            'plan_cuenta_inventario_id' => 'nullable|exists:plan_cuentas,id',
            'plan_cuenta_costo_venta_id' => 'nullable|exists:plan_cuentas,id',
            'plan_cuenta_ingreso_id' => 'nullable|exists:plan_cuentas,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $producto->update([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'categoria' => $request->tipo ?? 'otros',
                'tipo' => $request->tipo ?? 'otro',
                'precio_venta' => $request->precio_venta,
                'unidad_medida' => $request->unidad_medida,
                'stock_minimo' => $request->stock_minimo ?? 0,
                'perecedero' => $request->perecedero ? 1 : 0,
                'activo' => $request->activo ? 1 : 0,
                'dias_alerta_vencimiento' => $request->dias_alerta_vencimiento ?? 7,
                'cuenta_inventario_id' => $request->plan_cuenta_inventario_id,
                'cuenta_costo_venta_id' => $request->plan_cuenta_costo_venta_id,
                'cuenta_ingreso_id' => $request->plan_cuenta_ingreso_id,
            ]);

            return redirect()->route('productos.index')
                ->with('success', 'Producto actualizado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar producto: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar producto
     */
    public function destroy($id)
    {
        try {
            $producto = Producto::findOrFail($id);

            if ($producto->lotes()->exists()) {
                return redirect()->back()
                    ->with('error', 'No se puede eliminar el producto porque tiene movimientos de inventario');
            }

            $producto->delete();

            return redirect()->route('productos.index')
                ->with('success', 'Producto eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar producto: ' . $e->getMessage());
        }
    }

    /**
     * Obtener cuentas contables por defecto
     */
    private function obtenerCuentasDefecto()
    {
        return [
            'inventario' => Plan_cuenta::where('codigo', '1.1.3.02')->first()->id,
            'costo_venta' => Plan_cuenta::where('codigo', '6.1')->first()->id,
            'ingreso' => Plan_cuenta::where('codigo', '5.1.1.01')->first()->id,
        ];
    }
}
