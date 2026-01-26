<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaGastoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener IDs de cuentas del plan de cuentas boliviano
        $cuentas = [
            'sueldos' => DB::table('plan_cuentas')->where('codigo', '6.4.2.02')->value('id'),
            'alquiler' => DB::table('plan_cuentas')->where('codigo', '6.4.1.08')->value('id'),
            'servicios' => DB::table('plan_cuentas')->where('codigo', '6.4.1.09')->value('id'),
            'mantenimiento' => DB::table('plan_cuentas')->where('codigo', '6.4.1.11')->value('id'),
            'limpieza' => DB::table('plan_cuentas')->where('codigo', '6.4.1.01')->value('id'),
            'escritorio' => DB::table('plan_cuentas')->where('codigo', '6.4.1.06')->value('id'),
            'publicidad' => DB::table('plan_cuentas')->where('codigo', '6.4.1.10')->value('id'),
            'transporte' => DB::table('plan_cuentas')->where('codigo', '6.4.1.04')->value('id'),
            'empaque' => DB::table('plan_cuentas')->where('codigo', '6.4.1.05')->value('id'),
            'combustible' => DB::table('plan_cuentas')->where('codigo', '6.4.1.07')->value('id'),
        ];

        $categorias = [
            ['nombre' => 'Sueldos y Salarios', 'cuenta_id' => $cuentas['sueldos'], 'activa' => true],
            ['nombre' => 'Alquiler de Local', 'cuenta_id' => $cuentas['alquiler'], 'activa' => true],
            ['nombre' => 'Electricidad', 'cuenta_id' => $cuentas['servicios'], 'activa' => true],
            ['nombre' => 'Agua Potable', 'cuenta_id' => $cuentas['servicios'], 'activa' => true],
            ['nombre' => 'Internet y Telefonía', 'cuenta_id' => $cuentas['servicios'], 'activa' => true],
            ['nombre' => 'Mantenimiento de Refrigeración', 'cuenta_id' => $cuentas['mantenimiento'], 'activa' => true],
            ['nombre' => 'Mantenimiento de Equipos', 'cuenta_id' => $cuentas['mantenimiento'], 'activa' => true],
            ['nombre' => 'Útiles de Oficina', 'cuenta_id' => $cuentas['escritorio'], 'activa' => true],
            ['nombre' => 'Aseo y Limpieza', 'cuenta_id' => $cuentas['limpieza'], 'activa' => true],
            ['nombre' => 'Publicidad y Marketing', 'cuenta_id' => $cuentas['publicidad'], 'activa' => true],
            ['nombre' => 'Transporte y Fletes', 'cuenta_id' => $cuentas['transporte'], 'activa' => true],
            ['nombre' => 'Empaque y Embalaje', 'cuenta_id' => $cuentas['empaque'], 'activa' => true],
            ['nombre' => 'Combustibles', 'cuenta_id' => $cuentas['combustible'], 'activa' => true],
        ];

        foreach ($categorias as $categoria) {
            DB::table('categoria_gastos')->insert([
                'nombre' => $categoria['nombre'],
                'cuenta_id' => $categoria['cuenta_id'],
                'activa' => $categoria['activa'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
