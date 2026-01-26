<?php

namespace App\Http\Controllers;

use App\Models\Asiento_contable;
use App\Models\Plan_cuenta;
use App\Models\Libro;
use App\Models\Venta;
use App\Models\Compra;
use App\Services\ContabilidadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReportesController extends Controller
{
    protected $contabilidadService;

    public function __construct(ContabilidadService $contabilidadService)
    {
        $this->contabilidadService = $contabilidadService;
    }

    /**
     * Reporte IVA (Débito Fiscal - Crédito Fiscal)
     */
    public function iva(Request $request)
    {
        // Manejo de mes y año
        $mes = $request->input('mes', date('n'));
        $anio = $request->input('anio', date('Y'));
        $fecha_desde = date('Y-m-01', strtotime("$anio-$mes-01"));
        $fecha_hasta = date('Y-m-t', strtotime("$anio-$mes-01"));

        // Obtener ventas con IVA (débito fiscal)
        $ventasConIVA = Venta::with('cliente')
            ->whereBetween('fecha_hora', [$fecha_desde . ' 00:00:00', $fecha_hasta . ' 23:59:59'])
            ->where('tipo_comprobante', 'factura') // Solo facturas generan débito fiscal
            ->orderBy('fecha_hora')
            ->get();

        // Obtener compras con IVA (crédito fiscal)
        $comprasConIVA = Compra::with('proveedor')
            ->whereBetween('fecha', [$fecha_desde, $fecha_hasta])
            ->where('estado', 'recibida') // Solo compras recibidas
            ->orderBy('fecha')
            ->get();

        // Calcular totales
        $totalDebitoFiscal = $ventasConIVA->sum('iva');
        $totalCreditoFiscal = $comprasConIVA->sum('credito_fiscal');
        $saldoIVA = $totalDebitoFiscal - $totalCreditoFiscal;

        return view('reportes.iva', [
            'mes' => $mes,
            'anio' => $anio,
            'fecha_desde' => $fecha_desde,
            'fecha_hasta' => $fecha_hasta,
            'ventasConIVA' => $ventasConIVA,
            'comprasConIVA' => $comprasConIVA,
            'totalDebitoFiscal' => $totalDebitoFiscal,
            'totalCreditoFiscal' => $totalCreditoFiscal,
            'saldoIVA' => $saldoIVA,
        ]);
    }

    /**
     * Reporte IT (3% sobre transacciones)
     */
    public function it(Request $request)
    {
        // Manejo de mes y año
        $mes = $request->input('mes', date('n'));
        $anio = $request->input('anio', date('Y'));
        $fecha_desde = date('Y-m-01', strtotime("$anio-$mes-01"));
        $fecha_hasta = date('Y-m-t', strtotime("$anio-$mes-01"));

        // Obtener ventas con IT
        $ventasConIT = Venta::with('cliente')
            ->whereBetween('fecha_hora', [$fecha_desde . ' 00:00:00', $fecha_hasta . ' 23:59:59'])
            ->orderBy('fecha_hora')
            ->get();

        // Calcular IT acumulado (3% sobre total de cada venta)
        $itAcumulado = $ventasConIT->sum('it');

        return view('reportes.it', [
            'mes' => $mes,
            'anio' => $anio,
            'fecha_desde' => $fecha_desde,
            'fecha_hasta' => $fecha_hasta,
            'ventasConIT' => $ventasConIT,
            'itAcumulado' => $itAcumulado,
        ]);
    }

    /**
     * Libro Mayor por cuenta
     */
    public function libroMayor(Request $request)
    {
        $fecha_desde = $request->input('fecha_desde', now()->startOfMonth()->format('Y-m-d'));
        $fecha_hasta = $request->input('fecha_hasta', now()->endOfMonth()->format('Y-m-d'));
        $cuenta_id = $request->input('cuenta_id');

        $cuentas = Plan_cuenta::where('activa', true)->orderBy('codigo')->get();
        $cuenta = null;
        $movimientos = collect([]);
        $total_debe = 0;
        $total_haber = 0;
        $saldo_final = 0;

        if ($cuenta_id) {
            $cuenta = Plan_cuenta::findOrFail($cuenta_id);

            // Obtener movimientos de la cuenta
            $movimientosRaw = DB::table('detalle_asientos')
                ->join('asiento_contables', 'detalle_asientos.asiento_id', '=', 'asiento_contables.id')
                ->where('detalle_asientos.cuenta_id', $cuenta->id)
                ->where('asiento_contables.estado', 'contabilizado')
                ->whereBetween('asiento_contables.fecha', [$fecha_desde, $fecha_hasta])
                ->orderBy('asiento_contables.fecha')
                ->orderBy('asiento_contables.numero_asiento')
                ->select(
                    'asiento_contables.fecha',
                    'asiento_contables.numero_asiento',
                    'detalle_asientos.descripcion as glosa',
                    'detalle_asientos.debe',
                    'detalle_asientos.haber'
                )
                ->get();

            // Calcular saldos
            $saldo = 0;
            $movimientos = $movimientosRaw->map(function ($mov) use (&$saldo, $cuenta) {
                if ($cuenta->naturaleza === 'deudora') {
                    $saldo += $mov->debe - $mov->haber;
                } else {
                    $saldo += $mov->haber - $mov->debe;
                }

                return (object) [
                    'fecha' => $mov->fecha,
                    'numero_asiento' => $mov->numero_asiento,
                    'glosa' => $mov->glosa,
                    'debe' => $mov->debe,
                    'haber' => $mov->haber,
                    'saldo' => $saldo,
                ];
            });

            $total_debe = $movimientosRaw->sum('debe');
            $total_haber = $movimientosRaw->sum('haber');
            $saldo_final = $saldo;
        }

        return view('reportes.libro-mayor', compact(
            'cuentas',
            'cuenta',
            'movimientos',
            'fecha_desde',
            'fecha_hasta',
            'total_debe',
            'total_haber',
            'saldo_final'
        ));
    }

    /**
     * Libro Diario (todos los asientos)
     */
    public function libroDiario(Request $request)
    {
        $fecha_desde = $request->input('fecha_desde', now()->startOfMonth()->format('Y-m-d'));
        $fecha_hasta = $request->input('fecha_hasta', now()->endOfMonth()->format('Y-m-d'));

        $asientos = Asiento_contable::with(['detalles.cuenta'])
            ->whereBetween('fecha', [$fecha_desde, $fecha_hasta])
            ->where('estado', 'contabilizado')
            ->orderBy('fecha')
            ->orderBy('numero_asiento')
            ->paginate(50);

        return view('reportes.libro-diario', compact('asientos', 'fecha_desde', 'fecha_hasta'));
    }

    /**
     * Balance General
     */
    public function balance(Request $request)
    {
        $fechaCorte = $request->input('fecha', now()->format('Y-m-d'));

        // Obtener todas las cuentas con movimientos
        $cuentas = Plan_cuenta::whereHas('detalleAsientos', function ($query) use ($fechaCorte) {
            $query->whereHas('asiento', function ($q) use ($fechaCorte) {
                $q->where('fecha', '<=', $fechaCorte)
                  ->where('estado', 'contabilizado');
            });
        })->with(['detalleAsientos' => function ($query) use ($fechaCorte) {
            $query->whereHas('asiento', function ($q) use ($fechaCorte) {
                $q->where('fecha', '<=', $fechaCorte)
                  ->where('estado', 'contabilizado');
            });
        }])->get();

        $balance = [
            'activo' => [],
            'pasivo' => [],
            'patrimonio' => [],
            'ingresos' => [],
            'gastos' => [],
        ];

        foreach ($cuentas as $cuenta) {
            $saldo = $cuenta->calcularSaldo($fechaCorte);

            if ($saldo == 0) continue;

            $item = [
                'codigo' => $cuenta->codigo,
                'nombre' => $cuenta->nombre,
                'saldo' => abs($saldo),
            ];

            if ($cuenta->tipo_cuenta === 'activo') {
                $balance['activo'][] = $item;
            } elseif ($cuenta->tipo_cuenta === 'pasivo') {
                $balance['pasivo'][] = $item;
            } elseif ($cuenta->tipo_cuenta === 'patrimonio') {
                $balance['patrimonio'][] = $item;
            } elseif ($cuenta->tipo_cuenta === 'ingreso') {
                $balance['ingresos'][] = $item;
            } elseif ($cuenta->tipo_cuenta === 'gasto') {
                $balance['gastos'][] = $item;
            }
        }

        $totalActivo = collect($balance['activo'])->sum('saldo');
        $totalPasivo = collect($balance['pasivo'])->sum('saldo');
        $totalPatrimonio = collect($balance['patrimonio'])->sum('saldo');
        $totalIngresos = collect($balance['ingresos'])->sum('saldo');
        $totalGastos = collect($balance['gastos'])->sum('saldo');
        
        // Calcular resultado del ejercicio (Ingresos - Gastos)
        $resultadoEjercicio = $totalIngresos - $totalGastos;
        
        // Verificar ecuación: Activo = Pasivo + Patrimonio + Resultado
        $pasivoMasPatrimonio = $totalPasivo + $totalPatrimonio + $resultadoEjercicio;
        $balance_verificacion = abs($totalActivo - $pasivoMasPatrimonio) < 0.01;

        return view('reportes.balance', [
            'fecha_corte' => $fechaCorte,
            'activo' => $balance['activo'],
            'total_activo' => $totalActivo,
            'pasivo' => $balance['pasivo'],
            'total_pasivo' => $totalPasivo,
            'patrimonio' => $balance['patrimonio'],
            'total_patrimonio' => $totalPatrimonio,
            'resultado_ejercicio' => $resultadoEjercicio,
            'total_ingresos' => $totalIngresos,
            'total_gastos' => $totalGastos,
            'balance_verificacion' => $balance_verificacion,
        ]);
    }

    /**
     * Estado de Resultados (Ingresos - Egresos)
     */
    public function estadoResultados(Request $request)
    {
        $fecha_desde = $request->input('fecha_desde', now()->startOfMonth()->format('Y-m-d'));
        $fecha_hasta = $request->input('fecha_hasta', now()->endOfMonth()->format('Y-m-d'));

        // Cuentas de ingresos (5.x)
        $ingresos = Plan_cuenta::where('codigo', 'like', '5.%')
            ->whereHas('detalleAsientos', function ($query) use ($fecha_desde, $fecha_hasta) {
                $query->whereHas('asiento', function ($q) use ($fecha_desde, $fecha_hasta) {
                    $q->whereBetween('fecha', [$fecha_desde, $fecha_hasta])
                      ->where('estado', 'contabilizado');
                });
            })->get();

        // Cuentas de egresos (6.x)
        $egresos = Plan_cuenta::where('codigo', 'like', '6.%')
            ->whereHas('detalleAsientos', function ($query) use ($fecha_desde, $fecha_hasta) {
                $query->whereHas('asiento', function ($q) use ($fecha_desde, $fecha_hasta) {
                    $q->whereBetween('fecha', [$fecha_desde, $fecha_hasta])
                      ->where('estado', 'contabilizado');
                });
            })->get();

        $detalleIngresos = $ingresos->map(function ($cuenta) use ($fecha_desde, $fecha_hasta) {
            $saldo = $cuenta->calcularSaldoPeriodo($fecha_desde, $fecha_hasta);
            return [
                'codigo' => $cuenta->codigo,
                'nombre' => $cuenta->nombre,
                'monto' => abs($saldo),
            ];
        });

        $detalleEgresos = $egresos->map(function ($cuenta) use ($fecha_desde, $fecha_hasta) {
            $saldo = $cuenta->calcularSaldoPeriodo($fecha_desde, $fecha_hasta);
            return [
                'codigo' => $cuenta->codigo,
                'nombre' => $cuenta->nombre,
                'monto' => abs($saldo),
            ];
        });

        $totalIngresos = $detalleIngresos->sum('monto');
        $totalEgresos = $detalleEgresos->sum('monto');
        $utilidadAntesImpuestos = $totalIngresos - $totalEgresos;
        
        // Calcular IUE (25% sobre utilidad)
        $iue = $utilidadAntesImpuestos > 0 ? $utilidadAntesImpuestos * 0.25 : 0;
        $utilidadNeta = $utilidadAntesImpuestos - $iue;

        return view('reportes.estado-resultados', [
            'fecha_desde' => $fecha_desde,
            'fecha_hasta' => $fecha_hasta,
            'ingresos' => $detalleIngresos,
            'total_ingresos' => $totalIngresos,
            'egresos' => $detalleEgresos,
            'total_egresos' => $totalEgresos,
            'utilidad_antes_impuestos' => $utilidadAntesImpuestos,
            'iue' => $iue,
            'utilidad_neta' => $utilidadNeta,
        ]);
    }
}
