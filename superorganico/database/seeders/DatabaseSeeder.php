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
        echo "\n🌱 Sembrando datos de prueba...\n\n";

        echo "📊 1. Plan de Cuentas y Categorías\n";
        $this->call([
            PlanCuentasSeeder::class,
            CategoriaGastoSeeder::class,
        ]);

        echo "\n👥 2. Usuarios del Sistema\n";
        $this->call(UsuarioSeeder::class);

        echo "\n📦 3. Productos\n";
        $this->call(ProductoSeeder::class);

        echo "\n👤 4. Clientes\n";
        $this->call(ClienteSeeder::class);

        echo "\n🚚 5. Proveedores\n";
        $this->call(ProveedorSeeder::class);

        echo "\n🛒 6. Compras (con lotes PEPS)\n";
        $this->call(CompraSeeder::class);

        echo "\n💰 7. Ventas (con contabilización)\n";
        $this->call(VentaSeeder::class);

        echo "\n✅ ¡Datos de prueba cargados exitosamente!\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "� RESUMEN DE DATOS:\n";
        echo "   • 154 Cuentas Contables Bolivianas\n";
        echo "   • 10 Productos Orgánicos con stock\n";
        echo "   • 8 Clientes registrados\n";
        echo "   • 5 Proveedores activos\n";
        echo "   • 8 Compras con lotes PEPS\n";
        echo "   • 10 Ventas procesadas\n";
        echo "   • 15 Lotes de inventario activos\n\n";
        echo "👤 ACCESO AL SISTEMA:\n";
        echo "   - admin@superorganico.com / admin123 (Administrador)\n";
        echo "   - cajero@superorganico.com / cajero123 (Cajero)\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    }
}
