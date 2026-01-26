<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Venta;
use App\Models\Movimiento_inventario;

class GenerarMovimientosVentas extends Command
{
    protected $signature = 'ventas:generar-movimientos';
    protected $description = 'Genera movimientos de inventario para ventas que no los tienen';

    public function handle()
    {
        $this->info('Generando movimientos de inventario para ventas históricas...');
        
        $ventas = Venta::with('detalles.producto')->orderBy('fecha_hora')->get();
        $creados = 0;

        foreach ($ventas as $venta) {
            foreach ($venta->detalles as $detalle) {
                // Verificar si ya existe un movimiento para esta venta y producto
                $existe = Movimiento_inventario::where('referencia', 'Venta')
                    ->where('referencia_id', $venta->id)
                    ->whereHas('lote', function($q) use ($detalle) {
                        $q->where('producto_id', $detalle->producto_id);
                    })
                    ->exists();
                
                if (!$existe && $detalle->producto) {
                    // Buscar el lote más antiguo del producto (PEPS)
                    $lote = \App\Models\Lote_inventario::where('producto_id', $detalle->producto_id)
                        ->where('fecha_ingreso', '<=', $venta->fecha_hora)
                        ->orderBy('fecha_ingreso')
                        ->first();
                    
                    if ($lote) {
                        Movimiento_inventario::create([
                            'lote_id' => $lote->id,
                            'tipo_movimiento' => 'salida',
                            'cantidad' => $detalle->cantidad,
                            'fecha_movimiento' => $venta->fecha_hora,
                            'costo_unitario' => $lote->costo_unitario,
                            'referencia' => 'Venta',
                            'referencia_id' => $venta->id,
                            'usuario_id' => $venta->usuario_id ?? 1,
                        ]);
                        
                        $this->line("Venta #{$venta->id} - {$detalle->producto->nombre} ({$detalle->cantidad} und)");
                        $creados++;
                    } else {
                        $this->warn("No se encontró lote para Venta #{$venta->id} - {$detalle->producto->nombre}");
                    }
                }
            }
        }

        $this->info("\nTotal de movimientos creados: {$creados}");
        return 0;
    }
}
