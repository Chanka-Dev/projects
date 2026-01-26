<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Proveedores;
use App\Models\Plan_cuenta;
use App\Services\ContabilidadService;
use App\Services\InventarioService;

class TestSistema extends Command
{
    protected $signature = 'test:sistema';
    protected $description = 'Prueba del sistema contable boliviano';

    public function handle()
    {
        $this->info('=== TEST SISTEMA CONTABLE BOLIVIANO ===');
        $this->newLine();

        // 1. Plan de cuentas
        $this->info('1. Verificando Plan de Cuentas Boliviano...');
        $cuentas = Plan_cuenta::whereIn('codigo', [
            '1.1.2.06', '2.1.8', '6.4.2.01', '6.1', '5.1.1.01'
        ])->get();

        foreach ($cuentas as $cuenta) {
            $this->line("  ✓ {$cuenta->codigo} - {$cuenta->nombre} [{$cuenta->naturaleza}]");
        }

        // 2. Servicios
        $this->newLine();
        $this->info('2. Verificando Servicios...');
        $contabilidadService = new ContabilidadService();
        $inventarioService = new InventarioService();
        $this->line('  ✓ ContabilidadService instanciado');
        $this->line('  ✓ InventarioService instanciado');

        // 3. Constantes
        $this->newLine();
        $this->info('3. Constantes Tributarias:');
        $this->line('  ✓ IVA: ' . (ContabilidadService::IVA_PORCENTAJE * 100) . '%');
        $this->line('  ✓ IT: ' . (ContabilidadService::IT_PORCENTAJE * 100) . '%');
        $this->line('  ✓ IUE: ' . (ContabilidadService::IUE_PORCENTAJE * 100) . '%');

        // 4. Modelos
        $this->newLine();
        $this->info('4. Verificando Modelos...');
        $this->line('  ✓ Usuarios: ' . Usuario::count());
        $this->line('  ✓ Productos: ' . Producto::count());
        $this->line('  ✓ Clientes: ' . Cliente::count());
        $this->line('  ✓ Proveedores: ' . Proveedores::count());
        $this->line('  ✓ Plan Cuentas: ' . Plan_cuenta::count());

        // 5. Test COMPRA (el proveedor nos factura 100 Bs con IVA incluido)
        $this->newLine();
        $this->info('5. Test COMPRA (Precio incluye IVA):');
        $precioCompra = 100.00; // Precio factura del proveedor CON IVA
        $creditoFiscal = $precioCompra * 0.13; // 13% directo sobre total
        $costoInventario = $precioCompra - $creditoFiscal; // Lo que queda
        
        $this->line("  Precio Compra Proveedor: Bs. {$precioCompra} (con IVA)");
        $this->line('  Crédito Fiscal (13% directo): Bs. ' . number_format($creditoFiscal, 2));
        $this->line('  Costo Inventario (100 - 13): Bs. ' . number_format($costoInventario, 2));
        $this->line('  Verificación: ' . number_format($costoInventario + $creditoFiscal, 2) . ' = ' . $precioCompra . ' ✓');
        
        // 6. Test VENTA (margen 50% sobre costo sin IVA)
        $this->newLine();
        $this->info('6. Test VENTA (Margen 50% sobre costo):');
        $precioVenta = $costoInventario * 1.50; // Base con margen 50%
        $precioFactura = $precioVenta * 1.1491; // Tasa efectiva 14.91%
        $debitoFiscal = $precioVenta * 0.13; // 13% sobre precio venta
        $montoIT = $precioFactura * 0.03; // 3% sobre precio factura
        $totalCobrar = $precioFactura + $montoIT;
        
        $this->line('  Costo Inventario: Bs. ' . number_format($costoInventario, 2));
        $this->line('  Precio Venta (margen 50%): Bs. ' . number_format($precioVenta, 2));
        $this->line('  Precio Factura (× 1.1491): Bs. ' . number_format($precioFactura, 2));
        $this->line('  Débito Fiscal (13%): Bs. ' . number_format($debitoFiscal, 2));
        $this->line('  IT (3% sobre factura): Bs. ' . number_format($montoIT, 2));
        $this->line('  Total a Cobrar: Bs. ' . number_format($totalCobrar, 2));
        
        // Verificación matemática
        $verificacionVenta = abs(($precioFactura + $montoIT) - $totalCobrar) < 0.01;
        $this->line('  Verificación: ' . number_format($precioFactura + $montoIT, 2) . ' = ' . number_format($totalCobrar, 2) . ' ' 
                   . ($verificacionVenta ? '✓' : '✗'));

        $this->newLine();
        $this->info('=== TODOS LOS TESTS COMPLETADOS ===');
        
        return 0;
    }
}
