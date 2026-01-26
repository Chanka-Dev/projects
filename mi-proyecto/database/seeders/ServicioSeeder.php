<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servicios = [
            [
                'nombre' => 'Corte de Cabello',
                'descripcion' => 'Corte de cabello profesional',
                'precio_base' => 80.00,
                'duracion_minutos' => 45,
                'porcentaje_comision' => 30.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Tinte Completo',
                'descripcion' => 'Aplicación de tinte en todo el cabello',
                'precio_base' => 250.00,
                'duracion_minutos' => 120,
                'porcentaje_comision' => 35.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Maquillaje Social',
                'descripcion' => 'Maquillaje para eventos sociales',
                'precio_base' => 150.00,
                'duracion_minutos' => 60,
                'porcentaje_comision' => 40.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Maquillaje de Novia',
                'descripcion' => 'Maquillaje profesional para novias',
                'precio_base' => 350.00,
                'duracion_minutos' => 90,
                'porcentaje_comision' => 45.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Peinado',
                'descripcion' => 'Peinado profesional',
                'precio_base' => 120.00,
                'duracion_minutos' => 60,
                'porcentaje_comision' => 30.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Manicure',
                'descripcion' => 'Manicure completo',
                'precio_base' => 60.00,
                'duracion_minutos' => 45,
                'porcentaje_comision' => 25.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Pedicure',
                'descripcion' => 'Pedicure completo',
                'precio_base' => 70.00,
                'duracion_minutos' => 60,
                'porcentaje_comision' => 25.00,
                'activo' => true,
            ],
            [
                'nombre' => 'Tratamiento Capilar',
                'descripcion' => 'Tratamiento profundo de hidratación',
                'precio_base' => 180.00,
                'duracion_minutos' => 90,
                'porcentaje_comision' => 30.00,
                'activo' => true,
            ],
        ];

        foreach ($servicios as $servicio) {
            Servicio::create($servicio);
        }
    }
}
