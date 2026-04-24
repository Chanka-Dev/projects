<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingrediente;

class IngredienteSeeder extends Seeder
{
    public function run(): void
    {
        $ingredientes = [
            // Carnes y proteínas
            ['nombre' => 'Pollo',              'categoria' => 'Proteína',   'unidad_default' => 'gramo',    'es_alergeno' => false, 'descripcion' => 'Pollo entero o en presas'],
            ['nombre' => 'Res (carne molida)',  'categoria' => 'Proteína',   'unidad_default' => 'gramo',    'es_alergeno' => false, 'descripcion' => 'Carne de res molida'],
            ['nombre' => 'Charque',             'categoria' => 'Proteína',   'unidad_default' => 'gramo',    'es_alergeno' => false, 'descripcion' => 'Carne deshidratada y salada, típica boliviana'],
            ['nombre' => 'Chicharrón de cerdo', 'categoria' => 'Proteína',   'unidad_default' => 'gramo',    'es_alergeno' => false, 'descripcion' => 'Cerdo frito crujiente'],
            ['nombre' => 'Huevo',               'categoria' => 'Proteína',   'unidad_default' => 'unidad',   'es_alergeno' => true,  'descripcion' => 'Huevo de gallina'],
            // Verduras y tubérculos
            ['nombre' => 'Papa',                'categoria' => 'Vegetal',    'unidad_default' => 'unidad',   'es_alergeno' => false, 'descripcion' => 'Papa boliviana, variedad según la receta'],
            ['nombre' => 'Chuño',               'categoria' => 'Vegetal',    'unidad_default' => 'gramo',    'es_alergeno' => false, 'descripcion' => 'Papa deshidratada por frío, ingrediente andino esencial'],
            ['nombre' => 'Tomate',              'categoria' => 'Vegetal',    'unidad_default' => 'unidad',   'es_alergeno' => false, 'descripcion' => null],
            ['nombre' => 'Cebolla',             'categoria' => 'Vegetal',    'unidad_default' => 'unidad',   'es_alergeno' => false, 'descripcion' => null],
            ['nombre' => 'Locoto',              'categoria' => 'Vegetal',    'unidad_default' => 'unidad',   'es_alergeno' => false, 'descripcion' => 'Ají picante boliviano'],
            ['nombre' => 'Zanahoria',           'categoria' => 'Vegetal',    'unidad_default' => 'unidad',   'es_alergeno' => false, 'descripcion' => null],
            ['nombre' => 'Arveja',              'categoria' => 'Vegetal',    'unidad_default' => 'taza',     'es_alergeno' => false, 'descripcion' => 'Arveja verde o seca'],
            // Cereales y harinas
            ['nombre' => 'Maíz mote',           'categoria' => 'Cereal',     'unidad_default' => 'taza',     'es_alergeno' => false, 'descripcion' => 'Maíz blanco cocido'],
            ['nombre' => 'Arroz',               'categoria' => 'Cereal',     'unidad_default' => 'taza',     'es_alergeno' => false, 'descripcion' => null],
            ['nombre' => 'Harina de trigo',     'categoria' => 'Cereal',     'unidad_default' => 'taza',     'es_alergeno' => true,  'descripcion' => 'Contiene gluten'],
            ['nombre' => 'Quinua',              'categoria' => 'Cereal',     'unidad_default' => 'taza',     'es_alergeno' => false, 'descripcion' => 'Grano andino nutritivo'],
            // Condimentos y especias
            ['nombre' => 'Comino',              'categoria' => 'Condimento', 'unidad_default' => 'cdita',    'es_alergeno' => false, 'descripcion' => null],
            ['nombre' => 'Orégano',             'categoria' => 'Condimento', 'unidad_default' => 'cdita',    'es_alergeno' => false, 'descripcion' => null],
            ['nombre' => 'Ají amarillo molido', 'categoria' => 'Condimento', 'unidad_default' => 'cda',      'es_alergeno' => false, 'descripcion' => 'Color y sabor típico boliviano'],
            ['nombre' => 'Sal',                 'categoria' => 'Condimento', 'unidad_default' => 'cdita',    'es_alergeno' => false, 'descripcion' => null],
            // Lácteos
            ['nombre' => 'Leche',               'categoria' => 'Lácteo',     'unidad_default' => 'taza',     'es_alergeno' => true,  'descripcion' => null],
            ['nombre' => 'Queso',               'categoria' => 'Lácteo',     'unidad_default' => 'gramo',    'es_alergeno' => true,  'descripcion' => 'Queso fresco o de mesa'],
            // Aceites y líquidos
            ['nombre' => 'Aceite vegetal',      'categoria' => 'Aceite',     'unidad_default' => 'cda',      'es_alergeno' => false, 'descripcion' => null],
            ['nombre' => 'Caldo de pollo',      'categoria' => 'Líquido',    'unidad_default' => 'taza',     'es_alergeno' => false, 'descripcion' => 'Caldo casero o de cubito'],
            // Frutas y dulces
            ['nombre' => 'Azúcar',              'categoria' => 'Endulzante', 'unidad_default' => 'cda',      'es_alergeno' => false, 'descripcion' => null],
            ['nombre' => 'Naranja',             'categoria' => 'Fruta',      'unidad_default' => 'unidad',   'es_alergeno' => false, 'descripcion' => null],
            ['nombre' => 'Durazno',             'categoria' => 'Fruta',      'unidad_default' => 'unidad',   'es_alergeno' => false, 'descripcion' => 'Fruta muy usada en api y postres bolivianos'],
            ['nombre' => 'Maní',                'categoria' => 'Otro',       'unidad_default' => 'taza',     'es_alergeno' => true,  'descripcion' => 'Cacahuate, fruto seco'],
        ];

        foreach ($ingredientes as $data) {
            Ingrediente::firstOrCreate(['nombre' => $data['nombre']], $data);
        }
    }
}
