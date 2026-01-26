<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Trabajadora;
use App\Models\Servicio;
use App\Models\HistorialServicio;
use Carbon\Carbon;

class CitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener datos
        $clientes = Cliente::all();
        $trabajadoras = Trabajadora::all();
        $servicios = Servicio::all();

        // Generar citas de la semana actual y anterior (algunas completadas)
        $fechas = [
            // Semana anterior (completadas)
            Carbon::now()->subDays(7)->setTime(9, 0),
            Carbon::now()->subDays(6)->setTime(10, 30),
            Carbon::now()->subDays(5)->setTime(14, 0),
            Carbon::now()->subDays(4)->setTime(15, 30),
            Carbon::now()->subDays(3)->setTime(11, 0),
            
            // Semana actual (algunas completadas, algunas pendientes)
            Carbon::now()->subDays(2)->setTime(9, 30),
            Carbon::now()->subDays(1)->setTime(13, 0),
            Carbon::now()->setTime(16, 0),
            Carbon::now()->addDays(1)->setTime(10, 0),
            Carbon::now()->addDays(2)->setTime(11, 30),
        ];

        foreach ($fechas as $index => $fecha) {
            $cliente = $clientes->random();
            $trabajadora = $trabajadoras->random();
            
            // Determinar estado (las de la semana anterior y algunas de esta semana están completadas)
            $estado = ($index < 7) ? 'completada' : (rand(0, 1) ? 'completada' : 'confirmada');
            
            // Seleccionar 1-3 servicios aleatorios
            $serviciosSeleccionados = $servicios->random(rand(1, 3));
            
            // Calcular duración total
            $duracionTotal = $serviciosSeleccionados->sum('duracion_minutos');
            $horaFin = (clone $fecha)->addMinutes($duracionTotal);
            
            // Crear la cita
            $cita = Cita::create([
                'cliente_id' => $cliente->id,
                'trabajadora_id' => $trabajadora->id,
                'fecha' => $fecha->toDateString(),
                'hora_inicio' => $fecha->format('H:i'),
                'hora_fin' => $horaFin->format('H:i'),
                'estado' => $estado,
                'observaciones' => $estado === 'completada' ? 'Servicio completado satisfactoriamente' : 'Pendiente de confirmación',
            ]);
            
            // Asociar servicios a la cita
            foreach ($serviciosSeleccionados as $servicio) {
                $cita->servicios()->attach($servicio->id, [
                    'precio_aplicado' => $servicio->precio_base,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Si la cita está completada, agregar al historial con comisiones
                if ($estado === 'completada') {
                    $precioAplicado = $servicio->precio_base;
                    $porcentajeComision = $servicio->porcentaje_comision;
                    $montoComision = ($precioAplicado * $porcentajeComision) / 100;
                    
                    HistorialServicio::create([
                        'cliente_id' => $cliente->id,
                        'trabajadora_id' => $trabajadora->id,
                        'servicio_id' => $servicio->id,
                        'fecha_servicio' => $fecha->toDateString(),
                        'precio_cobrado' => $precioAplicado,
                        'porcentaje_comision' => $porcentajeComision,
                        'monto_comision' => $montoComision,
                        'metodo_pago' => collect(['efectivo', 'transferencia', 'deposito'])->random(),
                    ]);
                }
            }
        }
        
        echo "\n✅ Se crearon " . Cita::count() . " citas\n";
        echo "✅ " . Cita::where('estado', 'completada')->count() . " citas completadas\n";
        echo "✅ " . HistorialServicio::count() . " servicios en historial\n";
        echo "✅ Total de comisiones pendientes: $" . number_format(HistorialServicio::whereNull('pago_trabajadora_id')->sum('monto_comision'), 2) . "\n\n";
    }
}
