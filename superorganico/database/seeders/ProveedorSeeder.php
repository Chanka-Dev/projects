<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proveedores;

class ProveedorSeeder extends Seeder
{
    public function run(): void
    {
        $proveedores = [
            [
                'nombre' => 'Granja Orgánica San Miguel',
                'nit' => '1023456789',
                'ci' => null,
                'telefono' => '2678901',
                'email' => 'ventas@granjasanmiguel.com',
                'direccion' => 'Km 15 Carretera a Viacha',
                'ciudad' => 'La Paz',
                'pais' => 'Bolivia',
                'activo' => true,
            ],
            [
                'nombre' => 'Frutas y Verduras El Campesino',
                'nit' => '2034567890',
                'ci' => null,
                'telefono' => '2789012',
                'email' => 'info@elcampesino.com',
                'direccion' => 'Mercado Mayorista, Puesto 45',
                'ciudad' => 'La Paz',
                'pais' => 'Bolivia',
                'activo' => true,
            ],
            [
                'nombre' => 'Lácteos Valle Alto',
                'nit' => '3045678901',
                'ci' => null,
                'telefono' => '2890123',
                'email' => 'ventas@vallealta.com',
                'direccion' => 'Zona Achocalla, Km 18',
                'ciudad' => 'La Paz',
                'pais' => 'Bolivia',
                'activo' => true,
            ],
            [
                'nombre' => 'Distribuidora Quinua Real',
                'nit' => '4056789012',
                'ci' => null,
                'telefono' => '2901234',
                'email' => 'contacto@quinuareal.com',
                'direccion' => 'Av. Buenos Aires #567',
                'ciudad' => 'La Paz',
                'pais' => 'Bolivia',
                'activo' => true,
            ],
            [
                'nombre' => 'Agroecológica Los Andes',
                'nit' => '5067890123',
                'ci' => null,
                'telefono' => '2012345',
                'email' => 'ventas@agrolosandes.com',
                'direccion' => 'Zona Río Seco, Calle 8 #234',
                'ciudad' => 'La Paz',
                'pais' => 'Bolivia',
                'activo' => true,
            ],
        ];

        foreach ($proveedores as $proveedor) {
            Proveedores::create($proveedor);
        }
    }
}
