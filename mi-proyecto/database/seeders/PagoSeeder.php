<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pago;
use App\Models\Cita;
use App\Models\HistorialServicio;
use Carbon\Carbon;

class PagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener citas completadas
        $citasCompletadas = Cita::where('estado', 'completada')->with('servicios')->get();
        
        foreach ($citasCompletadas as $cita) {
            // Calcular el total de la cita
            $montoTotal = $cita->servicios->sum('pivot.precio_aplicado');
            
            // Crear pago (algunas pagadas, algunas pendientes)
            $estadoPago = rand(0, 10) > 3 ? 'completado' : 'pendiente'; // 70% pagadas
            $metodoPago = collect(['efectivo', 'tarjeta', 'transferencia'])->random();
            
            $pago = Pago::create([
                'cita_id' => $cita->id,
                'monto_total' => $montoTotal,
                'metodo_pago' => $metodoPago,
                'estado' => $estadoPago,
                'fecha_pago' => $estadoPago === 'completado' ? $cita->fecha : Carbon::now(),
            ]);
            
            // Si el pago está completado, crear historial de servicios
            if ($estadoPago === 'completado') {
                foreach ($cita->servicios as $servicio) {
                    $precioAplicado = $servicio->pivot->precio_aplicado;
                    $montoComision = $servicio->monto_comision ?? 0;
                    
                    HistorialServicio::create([
                        'cita_id' => $cita->id,
                        'cliente_id' => $cita->cliente_id,
                        'trabajadora_id' => $cita->trabajadora_id,
                        'servicio_id' => $servicio->id,
                        'fecha_servicio' => $cita->fecha,
                        'precio_cobrado' => $precioAplicado,
                        'monto_comision' => $montoComision,
                        'metodo_pago' => $metodoPago,
                    ]);
                }
            }
        }
        
        echo "\n✅ Se crearon " . Pago::count() . " pagos de clientes\n";
        echo "✅ Pagos completados: " . Pago::where('estado', 'completado')->count() . "\n";
        echo "✅ Pagos pendientes: " . Pago::where('estado', 'pendiente')->count() . "\n";
        echo "✅ HistorialServicio creados: " . HistorialServicio::count() . "\n\n";
    }
}
