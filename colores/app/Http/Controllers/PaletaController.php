<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaletaController extends Controller
{
    private $paletas = [
        'urgencia' => [
            'nombre' => 'Urgencia y Acción',
            'descripcion' => 'Paleta basada en rojos para generar urgencia y captar atención inmediata. Ideal para promociones, ofertas limitadas y llamadas a la acción.',
            'emocion' => 'Urgencia, Pasión, Energía',
            'usos' => ['Botones CTA', 'Ofertas limitadas', 'Alertas importantes', 'Comida rápida'],
            'colores' => [
                ['hex' => '#FF0000', 'rgb' => 'rgb(255, 0, 0)', 'nombre' => 'Rojo Puro'],
                ['hex' => '#CC0000', 'rgb' => 'rgb(204, 0, 0)', 'nombre' => 'Rojo Oscuro'],
                ['hex' => '#FF3333', 'rgb' => 'rgb(255, 51, 51)', 'nombre' => 'Rojo Claro'],
                ['hex' => '#FF6666', 'rgb' => 'rgb(255, 102, 102)', 'nombre' => 'Rojo Suave'],
                ['hex' => '#990000', 'rgb' => 'rgb(153, 0, 0)', 'nombre' => 'Rojo Profundo']
            ]
        ],
        'confianza' => [
            'nombre' => 'Confianza y Profesionalismo',
            'descripcion' => 'Paleta basada en azules que transmite confianza, calma y profesionalismo. Perfecta para tecnología, finanzas y servicios corporativos.',
            'emocion' => 'Confianza, Calma, Seguridad',
            'usos' => ['Plataformas tech', 'Banca y finanzas', 'Salud', 'Corporativo'],
            'colores' => [
                ['hex' => '#0000FF', 'rgb' => 'rgb(0, 0, 255)', 'nombre' => 'Azul Puro'],
                ['hex' => '#0000CC', 'rgb' => 'rgb(0, 0, 204)', 'nombre' => 'Azul Oscuro'],
                ['hex' => '#3333FF', 'rgb' => 'rgb(51, 51, 255)', 'nombre' => 'Azul Claro'],
                ['hex' => '#6666FF', 'rgb' => 'rgb(102, 102, 255)', 'nombre' => 'Azul Suave'],
                ['hex' => '#000099', 'rgb' => 'rgb(0, 0, 153)', 'nombre' => 'Azul Profundo']
            ]
        ],
        'optimismo' => [
            'nombre' => 'Optimismo y Energía Positiva',
            'descripcion' => 'Paleta basada en amarillos que evoca optimismo, juventud y creatividad. Ideal para marcas juveniles, productos de limpieza y destacar información.',
            'emocion' => 'Optimismo, Alegría, Creatividad',
            'usos' => ['Productos infantiles', 'Promociones', 'Advertencias', 'Marcas juveniles'],
            'colores' => [
                ['hex' => '#FFD700', 'rgb' => 'rgb(255, 215, 0)', 'nombre' => 'Dorado'],
                ['hex' => '#FFC700', 'rgb' => 'rgb(255, 199, 0)', 'nombre' => 'Amarillo Dorado'],
                ['hex' => '#FFE44D', 'rgb' => 'rgb(255, 228, 77)', 'nombre' => 'Amarillo Claro'],
                ['hex' => '#FFF280', 'rgb' => 'rgb(255, 242, 128)', 'nombre' => 'Amarillo Suave'],
                ['hex' => '#D4A000', 'rgb' => 'rgb(212, 160, 0)', 'nombre' => 'Amarillo Oscuro']
            ]
        ],
        'naturaleza' => [
            'nombre' => 'Naturaleza y Crecimiento',
            'descripcion' => 'Paleta basada en verdes que transmite frescura, crecimiento y armonía. Perfecta para marcas ecológicas, salud y bienestar.',
            'emocion' => 'Calma, Frescura, Crecimiento',
            'usos' => ['Productos ecológicos', 'Salud natural', 'Alimentos orgánicos', 'Finanzas'],
            'colores' => [
                ['hex' => '#00FF00', 'rgb' => 'rgb(0, 255, 0)', 'nombre' => 'Verde Puro'],
                ['hex' => '#00CC00', 'rgb' => 'rgb(0, 204, 0)', 'nombre' => 'Verde Oscuro'],
                ['hex' => '#33FF33', 'rgb' => 'rgb(51, 255, 51)', 'nombre' => 'Verde Claro'],
                ['hex' => '#66FF66', 'rgb' => 'rgb(102, 255, 102)', 'nombre' => 'Verde Suave'],
                ['hex' => '#009900', 'rgb' => 'rgb(0, 153, 0)', 'nombre' => 'Verde Profundo']
            ]
        ],
        'creatividad' => [
            'nombre' => 'Creatividad y Lujo',
            'descripcion' => 'Paleta basada en púrpuras que evoca creatividad, lujo y sofisticación. Ideal para marcas premium, belleza y arte.',
            'emocion' => 'Creatividad, Lujo, Misterio',
            'usos' => ['Marcas de lujo', 'Belleza', 'Arte', 'Tecnología innovadora'],
            'colores' => [
                ['hex' => '#9B59B6', 'rgb' => 'rgb(155, 89, 182)', 'nombre' => 'Púrpura'],
                ['hex' => '#8E44AD', 'rgb' => 'rgb(142, 68, 173)', 'nombre' => 'Púrpura Oscuro'],
                ['hex' => '#A569BD', 'rgb' => 'rgb(165, 105, 189)', 'nombre' => 'Púrpura Claro'],
                ['hex' => '#BB8FCE', 'rgb' => 'rgb(187, 143, 206)', 'nombre' => 'Lavanda'],
                ['hex' => '#6C3483', 'rgb' => 'rgb(108, 52, 131)', 'nombre' => 'Púrpura Profundo']
            ]
        ],
        'elegancia' => [
            'nombre' => 'Elegancia y Sofisticación',
            'descripcion' => 'Paleta basada en naranja que combina energía con calidez. Perfecta para marcas dinámicas, deportes y entretenimiento.',
            'emocion' => 'Energía, Calidez, Diversión',
            'usos' => ['Deportes', 'Entretenimiento', 'Comida', 'Tecnología moderna'],
            'colores' => [
                ['hex' => '#FF8C00', 'rgb' => 'rgb(255, 140, 0)', 'nombre' => 'Naranja Oscuro'],
                ['hex' => '#FFA500', 'rgb' => 'rgb(255, 165, 0)', 'nombre' => 'Naranja'],
                ['hex' => '#FFB74D', 'rgb' => 'rgb(255, 183, 77)', 'nombre' => 'Naranja Claro'],
                ['hex' => '#FFCC80', 'rgb' => 'rgb(255, 204, 128)', 'nombre' => 'Naranja Suave'],
                ['hex' => '#E65100', 'rgb' => 'rgb(230, 81, 0)', 'nombre' => 'Naranja Profundo']
            ]
        ]
    ];

    public function index()
    {
        return view('generador.index', [
            'tipos' => array_keys($this->paletas)
        ]);
    }

    public function generar($tipo)
    {
        if (!isset($this->paletas[$tipo])) {
            return redirect()->route('generador.index')->with('error', 'Tipo de paleta no encontrado');
        }

        $paleta = $this->paletas[$tipo];
        
        return view('generador.show', [
            'paleta' => $paleta,
            'tipo' => $tipo,
            'tipos' => array_keys($this->paletas)
        ]);
    }
}
