<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use Illuminate\Support\Str;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            'Cerveza Nacional' => 'Cervezas producidas en el país (Paceña, Huari, etc.).',
            'Cerveza Internacional' => 'Cervezas importadas (Corona, Heineken, Stella Artois, etc.).',
            'Cerveza Artesanal' => 'Cervezas de producción limitada y métodos tradicionales.',
            'Licores' => 'Whiskys, rones, vodkas y otros destilados.',
            'Vinos' => 'Vinos tintos, blancos y rosados de diversas regiones.',
            'Bebidas sin Alcohol' => 'Gaseosas, aguas y jugos para acompañar.',
        ];

        foreach ($categorias as $nombre => $descripcion) {
            Categoria::firstOrCreate(
                ['slug' => Str::slug($nombre)],
                [
                    'nombre' => $nombre,
                    'descripcion' => $descripcion,
                ]
            );
        }
    }
}
