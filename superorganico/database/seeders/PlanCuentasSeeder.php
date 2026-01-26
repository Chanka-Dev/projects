<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Plan de Cuentas según normativa contable boliviana
 * Estructura estándar para supermercado orgánico
 */
class PlanCuentasSeeder extends Seeder
{
    public function run(): void
    {
        $cuentas = [
            // ==================== 1. ACTIVO ====================
            ['codigo' => '1', 'nombre' => 'ACTIVO', 'tipo_cuenta' => 'activo', 'subtipo' => 'raiz', 'nivel' => 1, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'deudora', 'activa' => true],
            
            // 1.1 ACTIVO CORRIENTE
            ['codigo' => '1.1', 'nombre' => 'ACTIVO CORRIENTE', 'tipo_cuenta' => 'activo', 'subtipo' => 'grupo', 'nivel' => 2, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'deudora', 'activa' => true],
            
            // 1.1.1 ACTIVO DISPONIBLE
            ['codigo' => '1.1.1', 'nombre' => 'ACTIVO DISPONIBLE', 'tipo_cuenta' => 'activo', 'subtipo' => 'subgrupo', 'nivel' => 3, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '1.1.1.01', 'nombre' => 'CAJA MONEDA NACIONAL', 'tipo_cuenta' => 'activo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '1.1.1.02', 'nombre' => 'FONDO FIJO DE CAJA CHICA', 'tipo_cuenta' => 'activo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '1.1.1.04', 'nombre' => 'BANCO MONEDA NACIONAL', 'tipo_cuenta' => 'activo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            
            // 1.1.2 ACTIVO EXIGIBLE
            ['codigo' => '1.1.2', 'nombre' => 'ACTIVO EXIGIBLE', 'tipo_cuenta' => 'activo', 'subtipo' => 'subgrupo', 'nivel' => 3, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '1.1.2.01', 'nombre' => 'CUENTAS POR COBRAR', 'tipo_cuenta' => 'activo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '1.1.2.02', 'nombre' => 'DOCUMENTOS POR COBRAR', 'tipo_cuenta' => 'activo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '1.1.2.06', 'nombre' => 'CREDITO FISCAL IVA', 'tipo_cuenta' => 'activo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '1.1.2.09', 'nombre' => 'IUE POR COMPENSAR', 'tipo_cuenta' => 'activo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            
            // 1.1.3 ACTIVO REALIZABLE (INVENTARIOS)
            ['codigo' => '1.1.3', 'nombre' => 'ACTIVO REALIZABLE', 'tipo_cuenta' => 'activo', 'subtipo' => 'subgrupo', 'nivel' => 3, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '1.1.3.01', 'nombre' => 'INVENTARIO INICIAL', 'tipo_cuenta' => 'activo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '1.1.3.02', 'nombre' => 'COMPRAS', 'tipo_cuenta' => 'activo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '1.1.3.03', 'nombre' => 'MERCADERIAS EN TRANSITO', 'tipo_cuenta' => 'activo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '1.1.3.04', 'nombre' => 'INVENTARIO PRODUCTOS ORGANICOS', 'tipo_cuenta' => 'activo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            
            // 1.2 ACTIVO NO CORRIENTE
            ['codigo' => '1.2', 'nombre' => 'ACTIVO NO CORRIENTE', 'tipo_cuenta' => 'activo', 'subtipo' => 'grupo', 'nivel' => 2, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'deudora', 'activa' => true],
            
            // 1.2.1 BIENES DE USO
            ['codigo' => '1.2.1', 'nombre' => 'BIENES DE USO', 'tipo_cuenta' => 'activo', 'subtipo' => 'subgrupo', 'nivel' => 3, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '1.2.1.03', 'nombre' => 'MUEBLES Y ENSERES', 'tipo_cuenta' => 'activo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '1.2.1.04', 'nombre' => 'VEHICULOS', 'tipo_cuenta' => 'activo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '1.2.1.05', 'nombre' => 'EQUIPO DE COMPUTACION', 'tipo_cuenta' => 'activo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '1.2.1.06', 'nombre' => 'EQUIPO DE REFRIGERACION', 'tipo_cuenta' => 'activo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            
            // ==================== 2. PASIVO ====================
            ['codigo' => '2', 'nombre' => 'PASIVO', 'tipo_cuenta' => 'pasivo', 'subtipo' => 'raiz', 'nivel' => 1, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'acreedora', 'activa' => true],
            
            // 2.1 PASIVO CORRIENTE
            ['codigo' => '2.1', 'nombre' => 'PASIVO CORRIENTE', 'tipo_cuenta' => 'pasivo', 'subtipo' => 'grupo', 'nivel' => 2, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '2.1.1', 'nombre' => 'CUENTAS POR PAGAR', 'tipo_cuenta' => 'pasivo', 'subtipo' => 'detalle', 'nivel' => 3, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '2.1.3', 'nombre' => 'DOCUMENTOS POR PAGAR', 'tipo_cuenta' => 'pasivo', 'subtipo' => 'detalle', 'nivel' => 3, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'acreedora', 'activa' => true],
            
            // 2.1.5 OBLIGACIONES CON EL PERSONAL
            ['codigo' => '2.1.5', 'nombre' => 'OBLIGACIONES CON EL PERSONAL', 'tipo_cuenta' => 'pasivo', 'subtipo' => 'subgrupo', 'nivel' => 3, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '2.1.5.01', 'nombre' => 'SUELDOS Y SALARIOS POR PAGAR', 'tipo_cuenta' => 'pasivo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '2.1.5.02', 'nombre' => 'AGUINALDOS POR PAGAR', 'tipo_cuenta' => 'pasivo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'acreedora', 'activa' => true],
            
            // 2.1.6 APORTES Y RETENCIONES
            ['codigo' => '2.1.6', 'nombre' => 'APORTES Y RETENCIONES POR PAGAR', 'tipo_cuenta' => 'pasivo', 'subtipo' => 'subgrupo', 'nivel' => 3, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '2.1.6.01', 'nombre' => 'CAJA NACIONAL DE SALUD', 'tipo_cuenta' => 'pasivo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '2.1.6.04', 'nombre' => 'AFP', 'tipo_cuenta' => 'pasivo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'acreedora', 'activa' => true],
            
            // 2.1.7 IMPUESTOS POR PAGAR
            ['codigo' => '2.1.7', 'nombre' => 'IMPUESTOS POR PAGAR', 'tipo_cuenta' => 'pasivo', 'subtipo' => 'subgrupo', 'nivel' => 3, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '2.1.7.01', 'nombre' => 'RC IVA DEPENDIENTES', 'tipo_cuenta' => 'pasivo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '2.1.7.04', 'nombre' => 'IT POR PAGAR', 'tipo_cuenta' => 'pasivo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '2.1.7.05', 'nombre' => 'IUE POR PAGAR', 'tipo_cuenta' => 'pasivo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '2.1.7.06', 'nombre' => 'IT ACUMULADO POR PAGAR', 'tipo_cuenta' => 'pasivo', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'acreedora', 'activa' => true],
            
            ['codigo' => '2.1.8', 'nombre' => 'DEBITO FISCAL IVA', 'tipo_cuenta' => 'pasivo', 'subtipo' => 'detalle', 'nivel' => 3, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'acreedora', 'activa' => true],
            
            // 2.5 DEPRECIACION ACUMULADA
            ['codigo' => '2.5', 'nombre' => 'DEPRECIACION ACUMULADA', 'tipo_cuenta' => 'pasivo', 'subtipo' => 'grupo', 'nivel' => 2, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '2.5.2', 'nombre' => 'DEPRECIACION ACUMULADA MUEBLES Y ENSERES', 'tipo_cuenta' => 'pasivo', 'subtipo' => 'detalle', 'nivel' => 3, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '2.5.4', 'nombre' => 'DEPRECIACION ACUMULADA EQUIPO DE COMPUTACION', 'tipo_cuenta' => 'pasivo', 'subtipo' => 'detalle', 'nivel' => 3, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'acreedora', 'activa' => true],
            
            // ==================== 3. PATRIMONIO ====================
            ['codigo' => '3', 'nombre' => 'PATRIMONIO', 'tipo_cuenta' => 'patrimonio', 'subtipo' => 'raiz', 'nivel' => 1, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '3.1', 'nombre' => 'CAPITAL SOCIAL', 'tipo_cuenta' => 'patrimonio', 'subtipo' => 'detalle', 'nivel' => 2, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '3.3', 'nombre' => 'UTILIDADES RETENIDAS', 'tipo_cuenta' => 'patrimonio', 'subtipo' => 'detalle', 'nivel' => 2, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '3.4', 'nombre' => 'PERDIDAS ACUMULADAS', 'tipo_cuenta' => 'patrimonio', 'subtipo' => 'detalle', 'nivel' => 2, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '3.5', 'nombre' => 'UTILIDAD DE LA GESTION', 'tipo_cuenta' => 'patrimonio', 'subtipo' => 'detalle', 'nivel' => 2, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '3.6', 'nombre' => 'PERDIDA DE LA GESTION', 'tipo_cuenta' => 'patrimonio', 'subtipo' => 'detalle', 'nivel' => 2, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            
            // ==================== 5. INGRESOS ====================
            ['codigo' => '5', 'nombre' => 'CUENTAS DE INGRESOS', 'tipo_cuenta' => 'ingreso', 'subtipo' => 'raiz', 'nivel' => 1, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '5.1', 'nombre' => 'INGRESOS', 'tipo_cuenta' => 'ingreso', 'subtipo' => 'grupo', 'nivel' => 2, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'acreedora', 'activa' => true],
            
            // 5.1.1 INGRESOS DE OPERACION
            ['codigo' => '5.1.1', 'nombre' => 'INGRESOS DE OPERACION', 'tipo_cuenta' => 'ingreso', 'subtipo' => 'subgrupo', 'nivel' => 3, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '5.1.1.01', 'nombre' => 'VENTAS', 'tipo_cuenta' => 'ingreso', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'acreedora', 'activa' => true],
            
            // 5.1.2 OTROS INGRESOS
            ['codigo' => '5.1.2', 'nombre' => 'OTROS INGRESOS', 'tipo_cuenta' => 'ingreso', 'subtipo' => 'subgrupo', 'nivel' => 3, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '5.1.2.01', 'nombre' => 'DESCUENTOS SOBRE COMPRAS', 'tipo_cuenta' => 'ingreso', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'acreedora', 'activa' => true],
            ['codigo' => '5.1.2.02', 'nombre' => 'DEVOLUCION Y BONIFICACION SOBRE COMPRAS', 'tipo_cuenta' => 'ingreso', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'acreedora', 'activa' => true],
            
            // ==================== 6. EGRESOS ====================
            ['codigo' => '6', 'nombre' => 'EGRESOS', 'tipo_cuenta' => 'gasto', 'subtipo' => 'raiz', 'nivel' => 1, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'deudora', 'activa' => true],
            
            // 6.1 COSTO DE MERCADERIAS VENDIDAS
            ['codigo' => '6.1', 'nombre' => 'COSTO MERCADERIAS VENDIDAS', 'tipo_cuenta' => 'gasto', 'subtipo' => 'detalle', 'nivel' => 2, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            
            // 6.2 DEVOLUCIONES Y BONIFICACIONES
            ['codigo' => '6.2', 'nombre' => 'DEVOLUCION Y BONIFICACION SOBRE VENTAS', 'tipo_cuenta' => 'gasto', 'subtipo' => 'detalle', 'nivel' => 2, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '6.3', 'nombre' => 'DESCUENTOS SOBRE VENTAS', 'tipo_cuenta' => 'gasto', 'subtipo' => 'detalle', 'nivel' => 2, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            
            // 6.4 GASTOS DE OPERACION
            ['codigo' => '6.4', 'nombre' => 'GASTOS DE OPERACION', 'tipo_cuenta' => 'gasto', 'subtipo' => 'grupo', 'nivel' => 2, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'deudora', 'activa' => true],
            
            // 6.4.1 GASTOS ADMINISTRATIVOS Y DE VENTAS
            ['codigo' => '6.4.1', 'nombre' => 'GASTOS ADMINISTRATIVOS Y DE VENTAS', 'tipo_cuenta' => 'gasto', 'subtipo' => 'subgrupo', 'nivel' => 3, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '6.4.1.01', 'nombre' => 'ASEO Y LIMPIEZA', 'tipo_cuenta' => 'gasto', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '6.4.1.02', 'nombre' => 'COMISIONES A VENDEDORES', 'tipo_cuenta' => 'gasto', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '6.4.1.04', 'nombre' => 'FLETES Y TRANSPORTE', 'tipo_cuenta' => 'gasto', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '6.4.1.05', 'nombre' => 'GASTOS DE EMPAQUE', 'tipo_cuenta' => 'gasto', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '6.4.1.06', 'nombre' => 'MATERIAL DE ESCRITORIO', 'tipo_cuenta' => 'gasto', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '6.4.1.07', 'nombre' => 'COMBUSTIBLES Y LUBRICANTES', 'tipo_cuenta' => 'gasto', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '6.4.1.08', 'nombre' => 'ALQUILERES', 'tipo_cuenta' => 'gasto', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '6.4.1.09', 'nombre' => 'SERVICIOS BASICOS', 'tipo_cuenta' => 'gasto', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '6.4.1.10', 'nombre' => 'PUBLICIDAD Y MARKETING', 'tipo_cuenta' => 'gasto', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '6.4.1.11', 'nombre' => 'MANTENIMIENTO Y REPARACIONES', 'tipo_cuenta' => 'gasto', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            
            // 6.4.2 IMPUESTOS FISCALES
            ['codigo' => '6.4.2', 'nombre' => 'IMPUESTOS FISCALES E INDIRECTOS', 'tipo_cuenta' => 'gasto', 'subtipo' => 'subgrupo', 'nivel' => 3, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '6.4.2.01', 'nombre' => 'GASTO IT', 'tipo_cuenta' => 'gasto', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '6.4.2.02', 'nombre' => 'SUELDOS Y SALARIOS', 'tipo_cuenta' => 'gasto', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '6.4.2.03', 'nombre' => 'APORTES PATRONALES', 'tipo_cuenta' => 'gasto', 'subtipo' => 'detalle', 'nivel' => 4, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            
            // 6.5 GASTOS NO OPERACIONALES
            ['codigo' => '6.5', 'nombre' => 'GASTOS NO OPERACIONALES', 'tipo_cuenta' => 'gasto', 'subtipo' => 'grupo', 'nivel' => 2, 'cuenta_padre_id' => null, 'acepta_movimientos' => false, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '6.5.1', 'nombre' => 'PERDIDA POR MERMA', 'tipo_cuenta' => 'gasto', 'subtipo' => 'detalle', 'nivel' => 3, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
            ['codigo' => '6.5.2', 'nombre' => 'PERDIDA POR CADUCIDAD', 'tipo_cuenta' => 'gasto', 'subtipo' => 'detalle', 'nivel' => 3, 'cuenta_padre_id' => null, 'acepta_movimientos' => true, 'naturaleza' => 'deudora', 'activa' => true],
        ];

        // Insertar cuentas y establecer relaciones
        $cuentasInsertadas = [];
        
        foreach ($cuentas as $cuenta) {
            $id = DB::table('plan_cuentas')->insertGetId([
                'codigo' => $cuenta['codigo'],
                'nombre' => $cuenta['nombre'],
                'tipo_cuenta' => $cuenta['tipo_cuenta'],
                'subtipo' => $cuenta['subtipo'],
                'nivel' => $cuenta['nivel'],
                'cuenta_padre_id' => $cuenta['cuenta_padre_id'],
                'acepta_movimientos' => $cuenta['acepta_movimientos'],
                'naturaleza' => $cuenta['naturaleza'],
                'activa' => $cuenta['activa'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $cuentasInsertadas[$cuenta['codigo']] = $id;
        }

        // Establecer relaciones padre-hijo
        $this->establecerRelaciones($cuentasInsertadas);
    }

    private function establecerRelaciones($cuentasInsertadas)
    {
        foreach ($cuentasInsertadas as $codigo => $id) {
            $partes = explode('.', $codigo);
            
            if (count($partes) > 1) {
                array_pop($partes);
                $codigoPadre = implode('.', $partes);
                
                if (isset($cuentasInsertadas[$codigoPadre])) {
                    DB::table('plan_cuentas')
                        ->where('id', $id)
                        ->update(['cuenta_padre_id' => $cuentasInsertadas[$codigoPadre]]);
                }
            }
        }
    }
}
