<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@distribuidora.com',
            'password' => Hash::make('admin123'),
            'role' => 'administrador',
        ]);

        // Crear usuario trabajador
        User::create([
            'name' => 'Trabajador Demo',
            'email' => 'trabajador@distribuidora.com',
            'password' => Hash::make('trabajador123'),
            'role' => 'trabajador',
        ]);

        // Crear usuario cliente
        User::create([
            'name' => 'Cliente Demo',
            'email' => 'cliente@distribuidora.com',
            'password' => Hash::make('cliente123'),
            'role' => 'cliente',
        ]);
    }
}
