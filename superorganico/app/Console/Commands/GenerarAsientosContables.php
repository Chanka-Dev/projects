<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Venta;
use App\Models\Compra;
use App\Services\ContabilidadService;

class GenerarAsientosContables extends Command
{
    protected $signature = 'contabilidad:generar-asientos';
    protected $description = 'Genera asientos contables para ventas y compras existentes';

    protected $contabilidadService;

    public function __construct(ContabilidadService $contabilidadService)
    {
        parent::__construct();
        $this->contabilidadService = $contabilidadService;
    }

    public function handle()
    {
        $this->info('Generando asientos contables...');

        // Generar asientos para ventas sin asiento
        $ventas = Venta::whereNull('asiento_id')->with(['cliente', 'detalles'])->get();
        $this->info("Ventas sin asiento: " . $ventas->count());

        $ventasGeneradas = 0;
        foreach ($ventas as $venta) {
            try {
                $asiento = $this->contabilidadService->generarAsientoVenta($venta);
                $ventasGeneradas++;
                $this->line("✓ Venta #{$venta->id} - Asiento #{$asiento->numero_asiento}");
            } catch (\Exception $e) {
                $this->error("✗ Error en venta #{$venta->id}: " . $e->getMessage());
            }
        }

        // Generar asientos para compras sin asiento
        $compras = Compra::whereNull('asiento_id')->with(['proveedor', 'detalles'])->get();
        $this->info("\nCompras sin asiento: " . $compras->count());

        $comprasGeneradas = 0;
        foreach ($compras as $compra) {
            try {
                $asiento = $this->contabilidadService->generarAsientoCompra($compra);
                $comprasGeneradas++;
                $this->line("✓ Compra #{$compra->id} - Asiento #{$asiento->numero_asiento}");
            } catch (\Exception $e) {
                $this->error("✗ Error en compra #{$compra->id}: " . $e->getMessage());
            }
        }

        $this->info("\n✅ Proceso completado:");
        $this->info("   - Asientos de ventas generados: $ventasGeneradas");
        $this->info("   - Asientos de compras generados: $comprasGeneradas");

        return 0;
    }
}
