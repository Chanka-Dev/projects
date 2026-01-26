<?php

namespace App\Services;

use App\Models\Asiento_contable;
use App\Models\Detalle_asiento;
use App\Models\Plan_cuenta;
use App\Models\Venta;
use App\Models\Compra;
use App\Models\Gasto_operativo;
use Illuminate\Support\Facades\DB;

/**
 * Servicio de Contabilidad para generación automática de asientos
 * según normativa boliviana (IVA 13%, IT 3%, IUE 25%)
 */
class ContabilidadService
{
    // Porcentajes de impuestos bolivianos
    const IVA_PORCENTAJE = 0.13;      // 13% (tasa nominal)
    const IT_PORCENTAJE = 0.03;       // 3%
    const IUE_PORCENTAJE = 0.25;      // 25%
    const TASA_EFECTIVA = 0.1491;     // 14.91% (incluye efecto IVA + IT)

    // Códigos de cuentas contables bolivianas
    const CUENTA_CAJA = '1.1.1.01';
    const CUENTA_INVENTARIO = '1.1.3.02';
    const CUENTA_CREDITO_FISCAL_IVA = '1.1.2.06';
    const CUENTA_DEBITO_FISCAL_IVA = '2.1.8';
    const CUENTA_IT_POR_PAGAR = '2.1.7.04';
    const CUENTA_IT_ACUMULADO = '2.1.7.06';
    const CUENTA_PROVEEDORES = '2.1.1';
    const CUENTA_COSTO_VENTAS = '6.1';
    const CUENTA_GASTO_IT = '6.4.2.01';
    const CUENTA_INGRESO_VENTAS = '5.1.1.01';

    /**
     * Genera asiento contable para una venta
     * 
     * Método corregido según docente:
     * 1. Precio Venta (base): lo que ingresamos en el sistema
     * 2. Precio Factura = Precio Venta × 1.1471 (tasa efectiva 14.71%)
     * 3. Débito Fiscal IVA = Precio Venta × 0.13 (13% sobre base)
     * 4. IT = Precio Factura × 0.03 (3% sobre precio factura)
     * 5. Total Cobrar = Precio Factura + IT
     * 
     * Asiento generado:
     * DEBE   Caja                        (Precio Factura + IT)
     * HABER  Ingreso por ventas          (Precio Venta base)
     * HABER  Débito Fiscal IVA           (13% sobre base)
     * DEBE   Gasto IT                    (3% sobre factura)
     * HABER  IT por pagar                (3% sobre factura)
     * DEBE   Costo de ventas             (Costo PEPS sin IVA)
     * HABER  Inventario                  (Costo PEPS sin IVA)
     * 
     * @param Venta $venta
     * @return Asiento_contable
     */
    public function generarAsientoVenta(Venta $venta): Asiento_contable
    {
        return DB::transaction(function () use ($venta) {
            // Nota: En la tabla ventas, el campo 'total' solo contiene el subtotal
            // El cliente paga: total (subtotal), el IVA e IT se registran pero no se suman al total
            $subtotal = $venta->total;     // Lo que realmente paga el cliente
            $iva = $venta->iva;            // IVA calculado (para declaración)
            $it = $venta->it;              // IT calculado (lo paga el negocio)
            
            // Costo de ventas (suma de costos de productos vendidos)
            $costoVentas = $venta->detalles->sum(function ($detalle) {
                return $detalle->cantidad * ($detalle->producto->precio_compra ?? 0);
            });

            // Crear asiento contable
            $asiento = Asiento_contable::create([
                'numero_asiento' => null,
                'fecha' => date('Y-m-d', strtotime($venta->fecha_hora)),
                'descripcion' => "Venta #" . $venta->id . " - Cliente: " . 
                          ($venta->cliente->nombre ?? 'Público general'),
                'tipo_asiento' => 'diario',
                'origen' => 'venta',
                'origen_id' => $venta->id,
                'estado' => 'pendiente',
                'usuario_id' => $venta->usuario_id ?? 1,
            ]);

            // Detalle 1: DEBE Caja (lo que ingresa)
            $this->crearDetalle($asiento, self::CUENTA_CAJA, $subtotal, 0, 
                'Cobro venta - Total Bs.' . number_format($subtotal, 2));

            // Detalle 2: HABER Ingreso por ventas
            // Restamos el IVA porque el ingreso es sin impuestos
            $ingresoNeto = $subtotal - $iva;
            $this->crearDetalle($asiento, self::CUENTA_INGRESO_VENTAS, 0, $ingresoNeto,
                'Ingreso venta mercaderías');

            // Detalle 3: HABER Débito Fiscal IVA
            $this->crearDetalle($asiento, self::CUENTA_DEBITO_FISCAL_IVA, 0, $iva,
                'Débito Fiscal IVA 13%');

            // Detalle 4: DEBE Gasto IT (la empresa asume el IT)
            if ($it > 0) {
                $this->crearDetalle($asiento, self::CUENTA_GASTO_IT, $it, 0,
                    'Gasto IT 3% asumido por la empresa');

                // Detalle 5: HABER IT Acumulado por pagar
                $this->crearDetalle($asiento, self::CUENTA_IT_ACUMULADO, 0, $it,
                    'IT acumulado por pagar');
            }

            // Detalle 6: DEBE Costo de mercaderías vendidas
            if ($costoVentas > 0) {
                $this->crearDetalle($asiento, self::CUENTA_COSTO_VENTAS, $costoVentas, 0,
                    'Costo de productos vendidos');

                // Detalle 7: HABER Inventario (salida de stock)
                $this->crearDetalle($asiento, self::CUENTA_INVENTARIO, 0, $costoVentas,
                    'Salida de inventario por venta');
            }

            // Contabilizar asiento
            $asiento->contabilizar();

            // Vincular asiento a venta
            $venta->update(['asiento_id' => $asiento->id]);

            return $asiento;
        });
    }

    /**
     * Genera asiento contable para una compra
     * 
     * El proveedor nos factura con IVA incluido.
     * Ejemplo: Precio compra = 100 Bs (precio factura CON IVA)
     * - Costo Inventario = 100 ÷ 1.13 = 88.50 Bs (sin IVA)
     * - Crédito Fiscal = 100 ÷ 1.13 × 0.13 = 11.50 Bs (IVA recuperable)
     * 
     * Asiento generado:
     * DEBE   Inventario                  (Costo sin IVA = Total ÷ 1.13)
     * DEBE   Crédito Fiscal IVA          (IVA recuperable = Total ÷ 1.13 × 0.13)
     * HABER  Proveedores                 (Total factura = 100 Bs)
     * 
     * @param Compra $compra
     * @return Asiento_contable
     */
    public function generarAsientoCompra(Compra $compra): Asiento_contable
    {
        return DB::transaction(function () use ($compra) {
            // Precio de compra del proveedor (CON IVA incluido)
            $precioCompraConIVA = $compra->total;
            
            // Crédito Fiscal: 13% directo sobre el total
            $creditoFiscal = round($precioCompraConIVA * self::IVA_PORCENTAJE, 2);
            
            // Costo inventario: Total menos el crédito fiscal
            $costoInventario = round($precioCompraConIVA - $creditoFiscal, 2);

            // Crear asiento contable
            $asiento = Asiento_contable::create([
                'numero_asiento' => null,
                'fecha' => $compra->fecha,
                'descripcion' => "Compra #" . $compra->id . " - Proveedor: {$compra->proveedor->nombre}",
                'tipo_asiento' => 'diario',
                'origen' => 'compra',
                'origen_id' => $compra->id,
                'estado' => 'pendiente',
                'usuario_id' => 1,
            ]);

            // Detalle 1: DEBE Inventario (costo sin IVA)
            $this->crearDetalle($asiento, self::CUENTA_INVENTARIO, $costoInventario, 0,
                'Compra mercaderías - Costo sin IVA');

            // Detalle 2: DEBE Crédito Fiscal IVA (13% recuperable)
            $this->crearDetalle($asiento, self::CUENTA_CREDITO_FISCAL_IVA, $creditoFiscal, 0,
                'Crédito Fiscal IVA 13% sobre Bs.' . number_format($costoInventario, 2));

            // Detalle 3: HABER Proveedores (total factura con IVA)
            $this->crearDetalle($asiento, self::CUENTA_PROVEEDORES, 0, $precioCompraConIVA,
                'Deuda proveedor ' . $compra->proveedor->nombre . ' - Factura ' . ($compra->numero_factura ?? 'S/N'));

            // Contabilizar asiento
            $asiento->contabilizar();

            // Vincular asiento a compra
            $compra->update(['asiento_id' => $asiento->id]);

            return $asiento;
        });
    }

    /**
     * Genera asiento para pago de compra
     * 
     * DEBE   Proveedores
     * HABER  Caja/Banco
     * 
     * @param Compra $compra
     * @param string $cuentaPago Código cuenta (default: caja)
     * @return Asiento_contable
     */
    public function generarAsientoPagoCompra(Compra $compra, string $cuentaPago = self::CUENTA_CAJA): Asiento_contable
    {
        return DB::transaction(function () use ($compra, $cuentaPago) {
            $asiento = Asiento_contable::create([
                'numero_asiento' => null,
                'fecha' => now(),
                'glosa' => "Pago compra #{$compra->numero_compra} - {$compra->proveedor->nombre}",
                'tipo_asiento' => 'egreso',
                'estado' => 'borrador',
                'usuario_id' => auth()->id(),
            ]);

            // DEBE Proveedores (reducir deuda)
            $this->crearDetalle($asiento, self::CUENTA_PROVEEDORES, $compra->total, 0,
                'Pago a proveedor');

            // HABER Caja (salida de efectivo)
            $this->crearDetalle($asiento, $cuentaPago, 0, $compra->total,
                'Pago en efectivo/banco');

            $asiento->contabilizar();

            return $asiento;
        });
    }

    /**
     * Genera asiento para gasto operativo
     * 
     * DEBE   Cuenta de gasto (según categoría)
     * HABER  Caja
     * 
     * @param Gasto_operativo $gasto
     * @return Asiento_contable
     */
    public function generarAsientoGasto(Gasto_operativo $gasto): Asiento_contable
    {
        return DB::transaction(function () use ($gasto) {
            // Usar la cuenta_id del gasto directamente
            $cuentaGasto = $gasto->cuenta_id;

            // Generar número de asiento manualmente
            $fecha = \Carbon\Carbon::parse($gasto->fecha_gasto);
            $fechaStr = $fecha->format('Ym');
            $ultimo = Asiento_contable::whereYear('fecha', $fecha->year)
                          ->whereMonth('fecha', $fecha->month)
                          ->count() + 1;
            $numeroAsiento = 'ASI-' . $fechaStr . '-' . str_pad($ultimo, 5, '0', STR_PAD_LEFT);

            $asiento = Asiento_contable::create([
                'numero_asiento' => $numeroAsiento,
                'fecha' => $gasto->fecha_gasto,
                'tipo_asiento' => 'diario',
                'descripcion' => 'Gasto operativo: ' . $gasto->descripcion,
                'estado' => 'contabilizado',
                'origen' => 'gasto',
                'origen_id' => $gasto->id,
            ]);

            // DEBE Cuenta de gasto
            $this->crearDetalle($asiento, $cuentaGasto, $gasto->monto, 0,
                $gasto->descripcion);

            // HABER Caja
            $this->crearDetalle($asiento, self::CUENTA_CAJA, 0, $gasto->monto,
                'Pago de gasto operativo');

            // Vincular asiento a gasto
            $gasto->update(['asiento_id' => $asiento->id]);

            return $asiento;
        });
    }

    /**
     * Crea un detalle de asiento contable
     * 
     * @param Asiento_contable $asiento
     * @param string|int $cuentaId Código o ID de cuenta
     * @param float $debe
     * @param float $haber
     * @param string $glosa
     * @return Detalle_asiento
     */
    private function crearDetalle(
        Asiento_contable $asiento, 
        $cuentaId, 
        float $debe, 
        float $haber, 
        string $glosa
    ): Detalle_asiento {
        // Si es código de cuenta, buscar ID
        if (is_string($cuentaId)) {
            $cuenta = Plan_cuenta::where('codigo', $cuentaId)->firstOrFail();
            $cuentaId = $cuenta->id;
        }

        return Detalle_asiento::create([
            'asiento_id' => $asiento->id,
            'cuenta_id' => $cuentaId,
            'debe' => $debe,
            'haber' => $haber,
            'descripcion' => $glosa,
        ]);
    }

    /**
     * Calcula el saldo de IVA a pagar o recuperar
     * Débito Fiscal - Crédito Fiscal
     * 
     * @param string $fechaInicio
     * @param string $fechaFin
     * @return array
     */
    public function calcularSaldoIVA(string $fechaInicio, string $fechaFin): array
    {
        $debitoFiscal = Detalle_asiento::whereHas('cuenta', function ($q) {
                $q->where('codigo', self::CUENTA_DEBITO_FISCAL_IVA);
            })
            ->whereHas('asiento', function ($q) use ($fechaInicio, $fechaFin) {
                $q->whereBetween('fecha', [$fechaInicio, $fechaFin])
                  ->where('estado', 'contabilizado');
            })
            ->sum('haber');

        $creditoFiscal = Detalle_asiento::whereHas('cuenta', function ($q) {
                $q->where('codigo', self::CUENTA_CREDITO_FISCAL_IVA);
            })
            ->whereHas('asiento', function ($q) use ($fechaInicio, $fechaFin) {
                $q->whereBetween('fecha', [$fechaInicio, $fechaFin])
                  ->where('estado', 'contabilizado');
            })
            ->sum('debe');

        $saldo = $debitoFiscal - $creditoFiscal;

        return [
            'debito_fiscal' => $debitoFiscal,
            'credito_fiscal' => $creditoFiscal,
            'saldo' => $saldo,
            'tipo' => $saldo > 0 ? 'A pagar' : ($saldo < 0 ? 'A favor' : 'Neutral'),
        ];
    }

    /**
     * Calcula el saldo de IT acumulado
     * 
     * @param string $fechaInicio
     * @param string $fechaFin
     * @return float
     */
    public function calcularSaldoIT(string $fechaInicio, string $fechaFin): float
    {
        return Detalle_asiento::whereHas('cuenta', function ($q) {
                $q->where('codigo', self::CUENTA_IT_POR_PAGAR);
            })
            ->whereHas('asiento', function ($q) use ($fechaInicio, $fechaFin) {
                $q->whereBetween('fecha', [$fechaInicio, $fechaFin])
                  ->where('estado', 'contabilizado');
            })
            ->sum('haber');
    }
}
