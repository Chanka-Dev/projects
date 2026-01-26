<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::create([
            'name' => 'Administrador',
            'email' => 'admin@superorganico.com',
            'password' => Hash::make('admin123'),
            'rol' => 'administrador',
        ]);

        Usuario::create([
            'name' => 'Empleado',
            'email' => 'empleado@superorganico.com',
            'password' => Hash::make('empleado123'),
            'rol' => 'empleado',
        ]);

        echo "✓ Usuarios creados (admin@superorganico.com / admin123, empleado@superorganico.com / empleado123)\n";
    }
}
