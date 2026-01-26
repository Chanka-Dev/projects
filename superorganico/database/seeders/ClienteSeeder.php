<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = [
            [
                'nombre' => 'Juan Pérez García',
                'tipo' => 'persona',
                'nit' => '1234567012',
                'ci' => '7890123 LP',
                'telefono' => '70123456',
                'email' => 'juan.perez@email.com',
                'direccion' => 'Av. 6 de Agosto #123',
                'ciudad' => 'La Paz',
                'pais' => 'Bolivia',
                'activo' => true,
            ],
            [
                'nombre' => 'María López Fernández',
                'tipo' => 'persona',
                'nit' => '2345678013',
                'ci' => '8901234 LP',
                'telefono' => '71234567',
                'email' => 'maria.lopez@email.com',
                'direccion' => 'Calle Comercio #456',
                'ciudad' => 'La Paz',
                'pais' => 'Bolivia',
                'activo' => true,
            ],
            [
                'nombre' => 'Restaurante El Buen Sabor',
                'tipo' => 'empresa',
                'nit' => '3456789014',
                'ci' => null,
                'telefono' => '2234567',
                'email' => 'ventas@elbuensabor.com',
                'direccion' => 'Zona Sopocachi, Calle 20 #789',
                'ciudad' => 'La Paz',
                'pais' => 'Bolivia',
                'activo' => true,
            ],
            [
                'nombre' => 'Hotel Plaza Premium',
                'tipo' => 'empresa',
                'nit' => '4567890015',
                'ci' => null,
                'telefono' => '2345678',
                'email' => 'compras@hotelplaza.com',
                'direccion' => 'Av. Arce #1234',
                'ciudad' => 'La Paz',
                'pais' => 'Bolivia',
                'activo' => true,
            ],
            [
                'nombre' => 'Carlos Ramírez',
                'tipo' => 'persona',
                'nit' => '5678901016',
                'ci' => '9012345 LP',
                'telefono' => '72345678',
                'email' => 'carlos.ramirez@email.com',
                'direccion' => 'Zona Sur, Calle 21 #567',
                'ciudad' => 'La Paz',
                'pais' => 'Bolivia',
                'activo' => true,
            ],
            [
                'nombre' => 'Supermercado Orgánico Verde',
                'tipo' => 'empresa',
                'nit' => '6789012017',
                'ci' => null,
                'telefono' => '2456789',
                'email' => 'compras@organicoverde.com',
                'direccion' => 'Av. Montenegro #890',
                'ciudad' => 'La Paz',
                'pais' => 'Bolivia',
                'activo' => true,
            ],
            [
                'nombre' => 'Ana Gutiérrez',
                'tipo' => 'persona',
                'nit' => '7890123018',
                'ci' => '1234567 LP',
                'telefono' => '73456789',
                'email' => 'ana.gutierrez@email.com',
                'direccion' => 'Calle México #234',
                'ciudad' => 'La Paz',
                'pais' => 'Bolivia',
                'activo' => true,
            ],
            [
                'nombre' => 'Cafetería Vida Sana',
                'tipo' => 'empresa',
                'nit' => '8901234019',
                'ci' => null,
                'telefono' => '2567890',
                'email' => 'info@vidasana.com',
                'direccion' => 'Zona Calacoto, Calle 10 #345',
                'ciudad' => 'La Paz',
                'pais' => 'Bolivia',
                'activo' => true,
            ],
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }
    }
}
