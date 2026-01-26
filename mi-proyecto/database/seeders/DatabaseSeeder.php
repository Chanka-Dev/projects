<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TrabajadoraSeeder::class,
            ClienteSeeder::class,
            ServicioSeeder::class,
            CitaSeeder::class,
            PagoSeeder::class,
        ]);
        
        echo "\n🎉 ¡Base de datos cargada exitosamente!\n";
        echo "Ahora puedes:\n";
        echo "1. Ver las trabajadoras en: /trabajadoras\n";
        echo "2. Ver los servicios con comisiones en: /servicios\n";
        echo "3. Ver pagos de clientes en: /pagos\n";
        echo "4. Generar un pago semanal en: /pago-trabajadoras/generar-semanal\n\n";
    }
}
