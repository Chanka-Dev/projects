<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PagoTrabajadora;
use App\Models\Trabajadora;
use App\Models\HistorialServicio;
use Carbon\Carbon;

class PagoTrabajadoraSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n🔄 Generando pagos a trabajadoras...\n";
        
        $trabajadoras = Trabajadora::where('activo', true)->get();
        $pagosCreados = 0;
        
        foreach ($trabajadoras as $trabajadora) {
            // Obtener servicios pendientes de pago
            $servicios = HistorialServicio::where('trabajadora_id', $trabajadora->id)
                ->whereNull('pago_trabajadora_id')
                ->get();
            
            if ($servicios->isEmpty()) {
                continue;
            }
            
            // Calcular totales
            $totalServicios = $servicios->sum('precio_cobrado');
            $totalComisiones = $servicios->sum('monto_comision');
            
            if ($totalComisiones <= 0) {
                continue;
            }
            
            // Obtener rango de fechas
            $fechaInicio = $servicios->min('fecha_servicio');
            $fechaFin = $servicios->max('fecha_servicio');
            
            // Crear el pago
            $pago = PagoTrabajadora::create([
                'trabajadora_id' => $trabajadora->id,
                'fecha_inicio_periodo' => $fechaInicio,
                'fecha_fin_periodo' => $fechaFin,
                'total_servicios' => $totalServicios,
                'total_comisiones' => $totalComisiones,
                'monto_pagado' => $totalComisiones, // Marcar como pagado
                'fecha_pago' => Carbon::now(),
                'estado' => rand(0, 1) ? 'pendiente' : 'pagado',
                'metodo_pago' => collect(['efectivo', 'transferencia', 'qr'])->random(),
                'observaciones' => 'Generado por seeder - ' . $servicios->count() . ' servicios',
            ]);
            
            // Asociar los servicios al pago
            $servicios->each(function ($servicio) use ($pago) {
                $servicio->update(['pago_trabajadora_id' => $pago->id]);
            });
            
            $pagosCreados++;
            echo "✅ Pago creado para {$trabajadora->nombre}: $" . number_format($totalComisiones, 2) . " ({$servicios->count()} servicios)\n";
        }
        
        echo "\n✅ Se crearon {$pagosCreados} pagos a trabajadoras\n";
    }
}
