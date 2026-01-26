<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Trabajadora;

class TrabajadoraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trabajadoras = [
            [
                'nombre' => 'María González',
                'tipo_contrato' => 'planta',
                'porcentaje_comision' => 0,
                'activo' => true,
            ],
            [
                'nombre' => 'Ana Rodríguez',
                'tipo_contrato' => 'independiente',
                'porcentaje_comision' => 0,
                'activo' => true,
            ],
            [
                'nombre' => 'Sofía Martínez',
                'tipo_contrato' => 'planta',
                'porcentaje_comision' => 0,
                'activo' => true,
            ],
            [
                'nombre' => 'Laura Pérez',
                'tipo_contrato' => 'independiente',
                'porcentaje_comision' => 0,
                'activo' => true,
            ],
        ];

        foreach ($trabajadoras as $trabajadora) {
            Trabajadora::create($trabajadora);
        }
    }
}
