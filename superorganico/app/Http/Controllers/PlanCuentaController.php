<?php

namespace App\Http\Controllers;

use App\Models\Plan_cuenta;
use Illuminate\Http\Request;

class PlanCuentaController extends Controller
{
    /**
     * Mostrar listado de cuentas
     */
    public function index(Request $request)
    {
        $tipo = $request->input('tipo');
        $buscar = $request->input('buscar');

        $query = Plan_cuenta::query()->orderBy('codigo');

        if ($tipo) {
            $query->where('tipo_cuenta', $tipo);
        }

        if ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where('codigo', 'like', "%{$buscar}%")
                  ->orWhere('nombre', 'like', "%{$buscar}%");
            });
        }

        $cuentas = $query->paginate(50);

        return view('plan-cuentas.index', compact('cuentas', 'tipo', 'buscar'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $cuentasPadre = Plan_cuenta::where('nivel', '<', 4)
            ->where('acepta_movimientos', false)
            ->orderBy('codigo')
            ->get();

        return view('plan-cuentas.create', compact('cuentasPadre'));
    }

    /**
     * Guardar nueva cuenta
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|unique:plan_cuentas,codigo|max:20',
            'nombre' => 'required|max:255',
            'tipo_cuenta' => 'required|in:activo,pasivo,patrimonio,ingreso,egreso',
            'naturaleza' => 'required|in:deudora,acreedora',
            'nivel' => 'required|integer|min:1|max:5',
            'acepta_movimientos' => 'boolean',
        ]);

        Plan_cuenta::create($request->all());

        return redirect()->route('plan-cuentas.index')
            ->with('success', 'Cuenta creada exitosamente');
    }

    /**
     * Mostrar detalle de cuenta
     */
    public function show(Plan_cuenta $planCuenta)
    {
        $planCuenta->load(['detalleAsientos.asiento', 'cuentasHijas']);
        
        $saldo = $planCuenta->calcularSaldo();
        
        return view('plan-cuentas.show', compact('planCuenta', 'saldo'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Plan_cuenta $planCuenta)
    {
        $cuentasPadre = Plan_cuenta::where('nivel', '<', 4)
            ->where('acepta_movimientos', false)
            ->where('id', '!=', $planCuenta->id)
            ->orderBy('codigo')
            ->get();

        return view('plan-cuentas.edit', compact('planCuenta', 'cuentasPadre'));
    }

    /**
     * Actualizar cuenta
     */
    public function update(Request $request, Plan_cuenta $planCuenta)
    {
        $request->validate([
            'codigo' => 'required|max:20|unique:plan_cuentas,codigo,' . $planCuenta->id,
            'nombre' => 'required|max:255',
            'tipo_cuenta' => 'required|in:activo,pasivo,patrimonio,ingreso,egreso',
            'naturaleza' => 'required|in:deudora,acreedora',
            'nivel' => 'required|integer|min:1|max:5',
            'acepta_movimientos' => 'boolean',
        ]);

        $planCuenta->update($request->all());

        return redirect()->route('plan-cuentas.index')
            ->with('success', 'Cuenta actualizada exitosamente');
    }

    /**
     * Eliminar cuenta
     */
    public function destroy(Plan_cuenta $planCuenta)
    {
        // Verificar si tiene movimientos
        if ($planCuenta->detalleAsientos()->count() > 0) {
            return redirect()->route('plan-cuentas.index')
                ->with('error', 'No se puede eliminar una cuenta con movimientos contables');
        }

        // Verificar si tiene cuentas hijas
        if ($planCuenta->cuentasHijas()->count() > 0) {
            return redirect()->route('plan-cuentas.index')
                ->with('error', 'No se puede eliminar una cuenta que tiene subcuentas');
        }

        $planCuenta->delete();

        return redirect()->route('plan-cuentas.index')
            ->with('success', 'Cuenta eliminada exitosamente');
    }
}
