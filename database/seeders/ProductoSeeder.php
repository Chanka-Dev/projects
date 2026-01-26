<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener IDs de categorías
        $nac = Categoria::where('slug', 'cerveza-nacional')->first()->id;
        $int = Categoria::where('slug', 'cerveza-internacional')->first()->id;
        $art = Categoria::where('slug', 'cerveza-artesanal')->first()->id;
        $lic = Categoria::where('slug', 'licores')->first()->id;
        $vin = Categoria::where('slug', 'vinos')->first()->id;
        $beb = Categoria::where('slug', 'bebidas-sin-alcohol')->first()->id;

        $productos = [
            [
                'nombre' => 'Paceña Pilsener 1L',
                'descripcion' => 'Cerveza tradicional boliviana, botella de 1 litro.',
                'precio' => 15.00,
                'stock' => 100,
                'categoria_id' => $nac,
                'image_path' => null,
            ],
            [
                'nombre' => 'Huari Tradicional 330ml',
                'descripcion' => 'Cerveza premium elaborada con agua de las serranías de Azanaque.',
                'precio' => 12.00,
                'stock' => 50,
                'categoria_id' => $nac,
                'image_path' => null,
            ],
            [
                'nombre' => 'Corona Extra 355ml',
                'descripcion' => 'Cerveza mexicana tipo pilsner de fama mundial.',
                'precio' => 18.00,
                'stock' => 80,
                'categoria_id' => $int,
                'image_path' => null,
            ],
            [
                'nombre' => 'Heineken 330ml',
                'descripcion' => 'Cerveza lager premium holandesa.',
                'precio' => 17.00,
                'stock' => 60,
                'categoria_id' => $int,
                'image_path' => null,
            ],
             [
                'nombre' => 'Fernet Branca 750ml',
                'descripcion' => 'Licor de hierbas amargo, ideal para combinar con cola.',
                'precio' => 90.00,
                'stock' => 20,
                'categoria_id' => $lic,
                'image_path' => null,
            ],
            [
                'nombre' => 'Vino Kohlberg Tinto 750ml',
                'descripcion' => 'Vino tinto varietal, producción tarijeña.',
                'precio' => 45.00,
                'stock' => 30,
                'categoria_id' => $vin,
                'image_path' => null,
            ],
             [
                'nombre' => 'Coca Cola 2L',
                'descripcion' => 'Refresco sabor cola, botella retornable.',
                'precio' => 10.00,
                'stock' => 150,
                'categoria_id' => $beb,
                'image_path' => null,
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
