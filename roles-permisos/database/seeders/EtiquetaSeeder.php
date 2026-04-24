<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Etiqueta;

class EtiquetaSeeder extends Seeder
{
    public function run(): void
    {
        $etiquetas = [
            ['nombre' => 'Almuerzo',  'color' => '#e67e22'],
            ['nombre' => 'Cena',      'color' => '#8e44ad'],
            ['nombre' => 'Desayuno',  'color' => '#f39c12'],
            ['nombre' => 'Postre',    'color' => '#e91e8c'],
            ['nombre' => 'Sopa',      'color' => '#27ae60'],
            ['nombre' => 'Bebida',    'color' => '#2980b9'],
            ['nombre' => 'Snack',     'color' => '#16a085'],
            ['nombre' => 'Salteado',  'color' => '#c0392b'],
            ['nombre' => 'Guiso',     'color' => '#d35400'],
            ['nombre' => 'Ensalada',  'color' => '#1abc9c'],
        ];

        foreach ($etiquetas as $data) {
            Etiqueta::firstOrCreate(['nombre' => $data['nombre']], $data);
        }
    }
}
