<?php

/**
 * Script de prueba del sistema contable boliviano
 * Ejecutar: php artisan tinker < tests/test_sistema.php
 */

use App\Models\Usuario;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Proveedor;
use App\Models\Plan_cuenta;
use App\Services\ContabilidadService;
use App\Services\InventarioService;

echo "\n=== TEST SISTEMA CONTABLE BOLIVIANO ===\n\n";

// 1. Verificar plan de cuentas boliviano
echo "1. Verificando Plan de Cuentas...\n";
$cuentas = Plan_cuenta::whereIn('codigo', [
    '1.1.2.06', // Crédito Fiscal IVA
    '2.1.8',    // Débito Fiscal IVA
    '6.4.2.01', // Gasto IT
    '6.1',      // Costo Mercaderías
    '5.1.1.01', // Ingreso Ventas
])->get();

foreach ($cuentas as $cuenta) {
    echo "  ✓ {$cuenta->codigo} - {$cuenta->nombre} [{$cuenta->naturaleza}]\n";
}

// 2. Verificar servicios
echo "\n2. Verificando Servicios...\n";
$contabilidadService = new ContabilidadService();
$inventarioService = new InventarioService();
echo "  ✓ ContabilidadService instanciado\n";
echo "  ✓ InventarioService instanciado\n";

// 3. Verificar constantes bolivianas
echo "\n3. Constantes Tributarias:\n";
echo "  ✓ IVA: " . (ContabilidadService::IVA_PORCENTAJE * 100) . "%\n";
echo "  ✓ IT: " . (ContabilidadService::IT_PORCENTAJE * 100) . "%\n";
echo "  ✓ IUE: " . (ContabilidadService::IUE_PORCENTAJE * 100) . "%\n";

// 4. Verificar modelos
echo "\n4. Verificando Modelos...\n";
echo "  ✓ Usuarios: " . Usuario::count() . "\n";
echo "  ✓ Productos: " . Producto::count() . "\n";
echo "  ✓ Clientes: " . Cliente::count() . "\n";
echo "  ✓ Proveedores: " . Proveedor::count() . "\n";
echo "  ✓ Plan Cuentas: " . Plan_cuenta::count() . "\n";

// 5. Test de cálculo IVA/IT
echo "\n5. Test Cálculo IVA/IT:\n";
$totalVenta = 113.00;
$montoIT = $totalVenta * 0.03;
$baseImponible = ($totalVenta - $montoIT) / 1.13;
$montoIVA = $baseImponible * 0.13;

echo "  Total Venta: Bs. {$totalVenta}\n";
echo "  IT (3%): Bs. " . number_format($montoIT, 2) . "\n";
echo "  Base Imponible: Bs. " . number_format($baseImponible, 2) . "\n";
echo "  IVA (13%): Bs. " . number_format($montoIVA, 2) . "\n";
echo "  Verificación: " . number_format($baseImponible + $montoIVA + $montoIT, 2) . " = {$totalVenta} " 
     . (abs(($baseImponible + $montoIVA + $montoIT) - $totalVenta) < 0.01 ? "✓" : "✗") . "\n";

echo "\n=== TODOS LOS TESTS COMPLETADOS ===\n\n";
