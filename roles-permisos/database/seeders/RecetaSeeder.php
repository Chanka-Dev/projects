<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Etiqueta;
use App\Models\Ingrediente;
use App\Models\Receta;
use App\Models\RecetaParte;
use App\Models\RecetaIngrediente;
use App\Models\User;

class RecetaSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@gmail.com')->first();

        // Etiquetas por nombre para fácil referencia
        $e = Etiqueta::pluck('id', 'nombre');

        // Ingredientes por nombre
        $i = Ingrediente::pluck('id', 'nombre');

        $recetas = [
            // ── ALMUERZO ──────────────────────────────────────────
            [
                'titulo'        => 'Fricasé de Cerdo',
                'etiqueta'      => 'Almuerzo',
                'fuente'        => 'Cocina boliviana tradicional',
                'instrucciones' => 'Hervir el maíz mote hasta que esté blando. Freír el chicharrón en su propia grasa. Preparar el caldo con ají amarillo, cebolla y comino. Agregar el chicharrón y el chuño. Servir con mote y locoto encurtido.',
                'ingredientes'  => [
                    ['nombre' => 'Chicharrón de cerdo', 'cantidad' => '800 g'],
                    ['nombre' => 'Chuño',               'cantidad' => '200 g', 'notas' => 'remojado la noche anterior'],
                    ['nombre' => 'Maíz mote',           'cantidad' => '2 tazas'],
                    ['nombre' => 'Ají amarillo molido',  'cantidad' => '3 cdas'],
                    ['nombre' => 'Cebolla',             'cantidad' => '2 unidades', 'notas' => 'picada finamente'],
                    ['nombre' => 'Comino',              'cantidad' => '1 cdita'],
                    ['nombre' => 'Locoto',              'cantidad' => '1 unidad', 'notas' => 'para acompañar'],
                    ['nombre' => 'Sal',                 'cantidad' => 'a gusto'],
                ],
            ],
            // ── SOPA ──────────────────────────────────────────────
            [
                'titulo'        => 'Sopa de Maní',
                'etiqueta'      => 'Sopa',
                'fuente'        => 'Receta familiar cochabambina',
                'instrucciones' => 'Freír la cebolla y el tomate con ají. Agregar el pollo y dorar. Añadir el caldo y las papas. Incorporar el maní molido y cocinar a fuego lento hasta espesar. Servir con orégano y arroz.',
                'ingredientes'  => [
                    ['nombre' => 'Pollo',              'cantidad' => '500 g', 'notas' => 'en presas'],
                    ['nombre' => 'Maní',               'cantidad' => '1 taza', 'notas' => 'tostado y molido'],
                    ['nombre' => 'Papa',               'cantidad' => '3 unidades', 'notas' => 'en cubos'],
                    ['nombre' => 'Cebolla',            'cantidad' => '1 unidad'],
                    ['nombre' => 'Tomate',             'cantidad' => '2 unidades'],
                    ['nombre' => 'Ají amarillo molido', 'cantidad' => '2 cdas'],
                    ['nombre' => 'Arroz',              'cantidad' => '½ taza', 'notas' => 'para acompañar'],
                    ['nombre' => 'Orégano',            'cantidad' => '1 cdita'],
                    ['nombre' => 'Caldo de pollo',     'cantidad' => '1.5 litros'],
                    ['nombre' => 'Sal',                'cantidad' => 'a gusto'],
                ],
            ],
            // ── GUISO ─────────────────────────────────────────────
            [
                'titulo'        => 'Sajta de Pollo',
                'etiqueta'      => 'Guiso',
                'fuente'        => 'Cocina paceña',
                'instrucciones' => 'Remojar el chuño. Sofreír cebolla y tomate, agregar ají y especias. Incorporar el pollo sancochado y deshilachado. Añadir el chuño y cocinar 20 minutos. Servir con papa y llajua.',
                'ingredientes'  => [
                    ['nombre' => 'Pollo',              'cantidad' => '1 kg', 'notas' => 'sancochado y deshilachado'],
                    ['nombre' => 'Chuño',              'cantidad' => '150 g', 'notas' => 'remojado'],
                    ['nombre' => 'Cebolla',            'cantidad' => '2 unidades'],
                    ['nombre' => 'Tomate',             'cantidad' => '2 unidades'],
                    ['nombre' => 'Ají amarillo molido', 'cantidad' => '3 cdas'],
                    ['nombre' => 'Papa',               'cantidad' => '4 unidades', 'notas' => 'cocidas para acompañar'],
                    ['nombre' => 'Comino',             'cantidad' => '½ cdita'],
                    ['nombre' => 'Orégano',            'cantidad' => '1 cdita'],
                    ['nombre' => 'Aceite vegetal',     'cantidad' => '3 cdas'],
                    ['nombre' => 'Sal',                'cantidad' => 'a gusto'],
                ],
            ],
            // ── POSTRE ────────────────────────────────────────────
            [
                'titulo'        => 'Buñuelos con Miel',
                'etiqueta'      => 'Postre',
                'fuente'        => 'Tradición carnavalera boliviana',
                'instrucciones' => 'Mezclar harina, huevos, leche y sal hasta obtener una masa suave. Freír cucharadas de masa en aceite caliente hasta dorar. Escurrir y bañar con miel de caña o azúcar.',
                'ingredientes'  => [
                    ['nombre' => 'Harina de trigo', 'cantidad' => '2 tazas'],
                    ['nombre' => 'Huevo',           'cantidad' => '3 unidades'],
                    ['nombre' => 'Leche',           'cantidad' => '½ taza'],
                    ['nombre' => 'Azúcar',          'cantidad' => '2 cdas'],
                    ['nombre' => 'Aceite vegetal',  'cantidad' => 'c/n', 'notas' => 'para freír'],
                    ['nombre' => 'Sal',             'cantidad' => '1 pizca'],
                ],
            ],
            // ── BEBIDA ────────────────────────────────────────────
            [
                'titulo'        => 'Api Morado con Pastel',
                'etiqueta'      => 'Bebida',
                'fuente'        => 'Bebida callejera paceña',
                'instrucciones' => 'Disolver harina de maíz morado en agua fría. Llevar al fuego con canela, clavo y cáscara de naranja. Endulzar con azúcar y cocinar removiendo hasta espesar. Servir caliente con pastel de queso.',
                'ingredientes'  => [
                    ['nombre' => 'Maíz mote',   'cantidad' => '200 g', 'notas' => 'harina de maíz morado'],
                    ['nombre' => 'Azúcar',      'cantidad' => '½ taza'],
                    ['nombre' => 'Naranja',     'cantidad' => '1 unidad', 'notas' => 'solo la cáscara'],
                    ['nombre' => 'Leche',       'cantidad' => '1 taza', 'notas' => 'opcional, para el pastel'],
                    ['nombre' => 'Harina de trigo', 'cantidad' => '1 taza', 'notas' => 'para el pastel'],
                    ['nombre' => 'Queso',       'cantidad' => '100 g', 'notas' => 'queso fresco para el pastel'],
                ],
            ],
            // ── DESAYUNO ──────────────────────────────────────────
            [
                'titulo'        => 'Tostadas con Queso y Huevo',
                'etiqueta'      => 'Desayuno',
                'fuente'        => 'Desayuno boliviano clásico',
                'instrucciones' => 'Tostar el pan. Freír los huevos en aceite con sal. Servir con queso fresco y una taza de leche caliente.',
                'ingredientes'  => [
                    ['nombre' => 'Huevo',          'cantidad' => '2 unidades'],
                    ['nombre' => 'Queso',          'cantidad' => '80 g'],
                    ['nombre' => 'Harina de trigo','cantidad' => '2 tazas', 'notas' => 'para el pan (masa)'],
                    ['nombre' => 'Leche',          'cantidad' => '1 taza'],
                    ['nombre' => 'Aceite vegetal', 'cantidad' => '1 cda'],
                    ['nombre' => 'Sal',            'cantidad' => '1 pizca'],
                ],
            ],
            // ── SALTEADO ──────────────────────────────────────────
            [
                'titulo'        => 'Salteado de Quinua con Verduras',
                'etiqueta'      => 'Salteado',
                'fuente'        => 'Fusión andina moderna',
                'instrucciones' => 'Cocer la quinua y reservar. Saltear cebolla, zanahoria y arveja en aceite a fuego alto. Agregar la quinua cocida, condimentar con comino y sal. Añadir huevo batido y mezclar rápido.',
                'ingredientes'  => [
                    ['nombre' => 'Quinua',         'cantidad' => '1 taza', 'notas' => 'cocida'],
                    ['nombre' => 'Zanahoria',      'cantidad' => '1 unidad', 'notas' => 'en cubos pequeños'],
                    ['nombre' => 'Arveja',         'cantidad' => '½ taza'],
                    ['nombre' => 'Cebolla',        'cantidad' => '1 unidad'],
                    ['nombre' => 'Huevo',          'cantidad' => '2 unidades', 'notas' => 'batidos'],
                    ['nombre' => 'Aceite vegetal', 'cantidad' => '2 cdas'],
                    ['nombre' => 'Comino',         'cantidad' => '½ cdita'],
                    ['nombre' => 'Sal',            'cantidad' => 'a gusto'],
                ],
            ],
            // ── SNACK ─────────────────────────────────────────────
            [
                'titulo'        => 'Salteñas de Pollo',
                'etiqueta'      => 'Snack',
                'fuente'        => 'Empanada boliviana por excelencia',
                'instrucciones' => 'Preparar el jigote: sofreír cebolla, ají, papa en cubos, arveja, zanahoria y pollo desmenuzado con caldo. Dejar enfriar hasta gelatinizar. Armar las empanadas con masa de harina y hornear a 220°C por 20 minutos.',
                'ingredientes'  => [
                    ['nombre' => 'Pollo',              'cantidad' => '300 g', 'notas' => 'cocido y desmenuzado'],
                    ['nombre' => 'Papa',               'cantidad' => '2 unidades', 'notas' => 'en cubos pequeños'],
                    ['nombre' => 'Arveja',             'cantidad' => '½ taza'],
                    ['nombre' => 'Cebolla',            'cantidad' => '1 unidad'],
                    ['nombre' => 'Ají amarillo molido', 'cantidad' => '2 cdas'],
                    ['nombre' => 'Harina de trigo',    'cantidad' => '3 tazas', 'notas' => 'para la masa'],
                    ['nombre' => 'Caldo de pollo',     'cantidad' => '1 taza'],
                    ['nombre' => 'Huevo',              'cantidad' => '1 unidad', 'notas' => 'para pintar la masa'],
                    ['nombre' => 'Aceite vegetal',     'cantidad' => '2 cdas'],
                    ['nombre' => 'Sal',                'cantidad' => 'a gusto'],
                ],
            ],
            // ── CENA ──────────────────────────────────────────────
            [
                'titulo'        => 'Charquekan',
                'etiqueta'      => 'Cena',
                'fuente'        => 'Plato típico orureño',
                'instrucciones' => 'Freír el charque hasta dorar y quedar crujiente. Cocer el mote y las papas. Freír los huevos. Servir todo junto en un plato: charque, mote, papa, queso, huevo y locoto.',
                'ingredientes'  => [
                    ['nombre' => 'Charque',        'cantidad' => '400 g'],
                    ['nombre' => 'Maíz mote',      'cantidad' => '1½ tazas', 'notas' => 'cocido'],
                    ['nombre' => 'Papa',           'cantidad' => '3 unidades', 'notas' => 'cocidas con cáscara'],
                    ['nombre' => 'Huevo',          'cantidad' => '2 unidades', 'notas' => 'frito'],
                    ['nombre' => 'Queso',          'cantidad' => '100 g', 'notas' => 'queso fresco en rodajas'],
                    ['nombre' => 'Locoto',         'cantidad' => '1 unidad'],
                    ['nombre' => 'Aceite vegetal', 'cantidad' => 'c/n', 'notas' => 'para freír'],
                    ['nombre' => 'Sal',            'cantidad' => 'a gusto'],
                ],
            ],
            // ── ENSALADA ──────────────────────────────────────────
            [
                'titulo'        => 'Ensalada de Quinua con Naranja',
                'etiqueta'      => 'Ensalada',
                'fuente'        => 'Cocina andina saludable',
                'instrucciones' => 'Cocer la quinua y dejar enfriar. Mezclar con tomate, cebolla morada en juliana, naranja en gajos y zanahoria rallada. Aderezar con aceite, sal y comino.',
                'ingredientes'  => [
                    ['nombre' => 'Quinua',         'cantidad' => '1 taza', 'notas' => 'cocida y fría'],
                    ['nombre' => 'Tomate',         'cantidad' => '2 unidades', 'notas' => 'en cubos'],
                    ['nombre' => 'Cebolla',        'cantidad' => '½ unidad', 'notas' => 'morada, en juliana'],
                    ['nombre' => 'Naranja',        'cantidad' => '1 unidad', 'notas' => 'en gajos'],
                    ['nombre' => 'Zanahoria',      'cantidad' => '1 unidad', 'notas' => 'rallada'],
                    ['nombre' => 'Aceite vegetal', 'cantidad' => '2 cdas'],
                    ['nombre' => 'Comino',         'cantidad' => '¼ cdita'],
                    ['nombre' => 'Sal',            'cantidad' => 'a gusto'],
                ],
            ],
        ];

        foreach ($recetas as $datos) {
            $etiquetaId = $e[$datos['etiqueta']] ?? null;
            if (!$etiquetaId) continue;

            $receta = Receta::firstOrCreate(
                ['titulo' => $datos['titulo']],
                [
                    'etiqueta_id'   => $etiquetaId,
                    'user_id'       => $admin->id,
                    'fuente'        => $datos['fuente'] ?? null,
                    'instrucciones' => $datos['instrucciones'] ?? null,
                ]
            );

            // Solo agregar ingredientes si la receta se acaba de crear
            if ($receta->wasRecentlyCreated) {
                foreach ($datos['ingredientes'] as $ing) {
                    $ingId = $i[$ing['nombre']] ?? null;
                    if (!$ingId) continue;

                    RecetaIngrediente::create([
                        'receta_id'      => $receta->id,
                        'ingrediente_id' => $ingId,
                        'cantidad'       => $ing['cantidad'] ?? null,
                        'notas'          => $ing['notas'] ?? null,
                    ]);
                }
            }
        }
    }
}
