<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente;
use Carbon\Carbon;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = [
            [
                'nombre' => 'Carla Vásquez',
                'telefono' => '70123456',
                'servicios_totales' => 0,
                'total_gastado' => 0,
                'fecha_registro' => Carbon::now()->subDays(30),
                'ultima_visita' => null,
            ],
            [
                'nombre' => 'Patricia López',
                'telefono' => '71234567',
                'servicios_totales' => 0,
                'total_gastado' => 0,
                'fecha_registro' => Carbon::now()->subDays(25),
                'ultima_visita' => null,
            ],
            [
                'nombre' => 'Daniela Flores',
                'telefono' => '72345678',
                'servicios_totales' => 0,
                'total_gastado' => 0,
                'fecha_registro' => Carbon::now()->subDays(20),
                'ultima_visita' => null,
            ],
            [
                'nombre' => 'Gabriela Morales',
                'telefono' => '73456789',
                'servicios_totales' => 0,
                'total_gastado' => 0,
                'fecha_registro' => Carbon::now()->subDays(15),
                'ultima_visita' => null,
            ],
            [
                'nombre' => 'Valentina Castro',
                'telefono' => '74567890',
                'servicios_totales' => 0,
                'total_gastado' => 0,
                'fecha_registro' => Carbon::now()->subDays(10),
                'ultima_visita' => null,
            ],
            [
                'nombre' => 'Camila Ríos',
                'telefono' => '75678901',
                'servicios_totales' => 0,
                'total_gastado' => 0,
                'fecha_registro' => Carbon::now()->subDays(5),
                'ultima_visita' => null,
            ],
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }
    }
}
