<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Venta;
use App\Models\Compra;
use App\Models\Detalle_venta;
use App\Models\Lote_inventario;
use App\Models\Movimiento_inventario;
use Illuminate\Support\Facades\DB;

class GenerarMovimientosInventario extends Command
{
    protected $signature = 'inventario:generar-movimientos';
    protected $description = 'Genera movimientos de inventario históricos para ventas y compras existentes';

    public function handle()
    {
        $this->info('=== GENERANDO MOVIMIENTOS DE INVENTARIO ===');
        $this->newLine();

        // Generar movimientos de ENTRADA para compras recibidas
        $this->info('1. GENERANDO ENTRADAS (Compras recibidas)...');
        $entradasGeneradas = $this->generarMovimientosCompras();

        $this->newLine();

        // Generar movimientos de SALIDA para ventas
        $this->info('2. GENERANDO SALIDAS (Ventas)...');
        $salidasGeneradas = $this->generarMovimientosVentas();

        $this->newLine();
        $this->info('✅ PROCESO COMPLETADO:');
        $this->info("   - Movimientos de entrada: $entradasGeneradas");
        $this->info("   - Movimientos de salida: $salidasGeneradas");
        $this->info("   - Total: " . ($entradasGeneradas + $salidasGeneradas));

        return 0;
    }

    protected function generarMovimientosCompras()
    {
        $contador = 0;
        
        // Obtener todas las compras recibidas
        $compras = Compra::where('estado', 'recibida')
            ->with(['detalles.producto'])
            ->orderBy('fecha_compra')
            ->get();

        $this->line("   Compras recibidas: " . $compras->count());

        foreach ($compras as $compra) {
            try {
                // Obtener los lotes creados para esta compra
                $lotes = Lote_inventario::where('compra_id', $compra->id)->get();

                if ($lotes->isEmpty()) {
                    $this->warn("   ⚠ Compra #{$compra->id} no tiene lotes asociados");
                    continue;
                }

                foreach ($lotes as $lote) {
                    // Verificar si ya existe un movimiento para este lote
                    $existe = Movimiento_inventario::where('lote_id', $lote->id)
                        ->where('tipo_movimiento', 'entrada')
                        ->where('referencia_id', $compra->id)
                        ->exists();

                    if (!$existe) {
                        Movimiento_inventario::create([
                            'lote_id' => $lote->id,
                            'tipo_movimiento' => 'entrada',
                            'cantidad' => $lote->cantidad_inicial,
                            'costo_unitario' => $lote->costo_unitario,
                            'fecha_movimiento' => $compra->fecha_compra,
                            'referencia' => 'Compra',
                            'referencia_id' => $compra->id,
                            'observaciones' => "Entrada inicial de compra #{$compra->id}",
                            'usuario_id' => 1,
                        ]);
                        $contador++;
                    }
                }

                $this->line("   ✓ Compra #{$compra->id} - {$lotes->count()} movimientos");
            } catch (\Exception $e) {
                $this->error("   ✗ Error en compra #{$compra->id}: " . $e->getMessage());
            }
        }

        return $contador;
    }

    protected function generarMovimientosVentas()
    {
        $contador = 0;
        
        // Obtener todas las ventas con sus detalles
        $ventas = Venta::with(['detalles.producto'])
            ->orderBy('fecha_hora')
            ->get();

        $this->line("   Ventas registradas: " . $ventas->count());

        foreach ($ventas as $venta) {
            try {
                foreach ($venta->detalles as $detalle) {
                    $producto = $detalle->producto;
                    $cantidadRestante = $detalle->cantidad;

                    // Obtener lotes disponibles en el momento de la venta (PEPS)
                    $lotes = Lote_inventario::where('producto_id', $producto->id)
                        ->where('fecha_ingreso', '<=', $venta->fecha_hora)
                        ->where('cantidad_actual', '>', 0)
                        ->orderBy('fecha_ingreso', 'asc')
                        ->orderBy('id', 'asc')
                        ->get();

                    // Consumir de los lotes usando PEPS
                    foreach ($lotes as $lote) {
                        if ($cantidadRestante <= 0) break;

                        $cantidadAConsumir = min($cantidadRestante, $lote->cantidad_actual);

                        // Verificar si ya existe el movimiento
                        $existe = Movimiento_inventario::where('lote_id', $lote->id)
                            ->where('tipo_movimiento', 'salida')
                            ->where('referencia_id', $venta->id)
                            ->exists();

                        if (!$existe) {
                            Movimiento_inventario::create([
                                'lote_id' => $lote->id,
                                'tipo_movimiento' => 'salida',
                                'cantidad' => -$cantidadAConsumir,
                                'costo_unitario' => $lote->costo_unitario,
                                'fecha_movimiento' => $venta->fecha_hora,
                                'referencia' => 'Venta',
                                'referencia_id' => $venta->id,
                                'observaciones' => "Salida por venta #{$venta->id} - {$producto->nombre}",
                                'usuario_id' => 1,
                            ]);
                            $contador++;
                        }

                        $cantidadRestante -= $cantidadAConsumir;
                    }

                    if ($cantidadRestante > 0) {
                        $this->warn("   ⚠ Venta #{$venta->id} - Producto {$producto->nombre}: faltaron {$cantidadRestante} unidades en lotes");
                    }
                }

                $this->line("   ✓ Venta #{$venta->id}");
            } catch (\Exception $e) {
                $this->error("   ✗ Error en venta #{$venta->id}: " . $e->getMessage());
            }
        }

        return $contador;
    }
}
