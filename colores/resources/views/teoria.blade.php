@extends('layouts.app')

@section('title', 'Teoría del Color - Colorsaurio')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="gradient-bg text-white rounded-lg shadow-2xl p-12 mb-12 text-center">
        <h1 class="text-4xl font-bold mb-2">Psicología del Color</h1>
        <p class="text-lg opacity-90">Impacto visual y emocional en diseño y marketing</p>
    </div>

    <!-- Colores Primarios - Intro -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8 border-2 border-green-500">
        <h2 class="text-3xl font-bold text-green-600 mb-6 pb-3 border-b-2 border-green-500">🦕 Colores Primarios</h2>
        
        <h3 class="text-2xl font-semibold text-gray-700 mb-4">Notación Hexadecimal</h3>
        <div class="grid md:grid-cols-3 gap-4 mb-6">
            <div class="bg-red-600 text-white text-center py-8 rounded-lg font-bold shadow-md">
                Rojo Puro<br>#FF0000
            </div>
            <div class="bg-blue-600 text-white text-center py-8 rounded-lg font-bold shadow-md">
                Azul Puro<br>#0000FF
            </div>
            <div class="bg-yellow-400 text-gray-800 text-center py-8 rounded-lg font-bold shadow-md">
                Amarillo Puro<br>#FFFF00
            </div>
        </div>
        
        <h3 class="text-2xl font-semibold text-gray-700 mb-4 mt-8">Notación RGB</h3>
        <div class="grid md:grid-cols-3 gap-4 mb-6">
            <div class="bg-red-600 text-white text-center py-8 rounded-lg font-bold shadow-md">
                Rojo<br>rgb(255, 0, 0)
            </div>
            <div class="bg-blue-600 text-white text-center py-8 rounded-lg font-bold shadow-md">
                Azul<br>rgb(0, 0, 255)
            </div>
            <div class="bg-yellow-400 text-gray-800 text-center py-8 rounded-lg font-bold shadow-md">
                Amarillo<br>rgb(255, 255, 0)
            </div>
        </div>

        <h3 class="text-2xl font-semibold text-gray-700 mb-4 mt-8">Impacto Psicológico Inicial</h3>
        
        <div class="mb-4">
            <p class="font-semibold text-red-600 mb-2">Color Rojo:</p>
            <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
                <li>Aumenta el ritmo cardiaco en un 5-10%</li>
                <li>Genera sensación de urgencia o atención inmediata</li>
                <li>Estimula el apetito (utilizado por 75% de comida rápida)</li>
            </ul>
        </div>
        
        <div class="mb-4">
            <p class="font-semibold text-blue-600 mb-2">Color Azul:</p>
            <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
                <li>Reduce el estrés y la ansiedad</li>
                <li>Asociado con profesionalismo y fiabilidad</li>
                <li>Color preferido en un 57% de los hombres y 35% de las mujeres</li>
            </ul>
        </div>
        
        <div class="mb-4">
            <p class="font-semibold text-yellow-600 mb-2">Color Amarillo:</p>
            <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
                <li>Estimula el lóbulo frontal izquierdo, centro de procesamiento lógico</li>
                <li>Aumenta la producción de serotonina (Hormona del bienestar)</li>
                <li>Genera respuesta visual más rápida (200ms vs 250ms promedio)</li>
            </ul>
        </div>
    </div>

    <!-- SECCIÓN ROJO -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8 border-t-4 border-red-600">
        <h2 class="text-3xl font-bold text-red-600 mb-6 pb-3 border-b-2 border-red-600">🔴 Color Rojo</h2>
        
        <h3 class="text-2xl font-semibold text-gray-700 mb-4">Percepción Psicológica</h3>
        
        <div class="mb-6">
            <p class="font-semibold text-gray-800 mb-2">Urgencia:</p>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li>El color rojo a menudo se asocia con la urgencia, lo que lo convierte en una opción común para ventas de liquidación y promociones</li>
                <li>El rojo en elementos clave como los botones de llamada a la acción indica la acción deseada, como hacer clic en un formulario de captura de clientes potenciales</li>
                <li>La sensación de urgencia y entusiasmo que evoca el rojo lo convierte en una herramienta poderosa para impulsar las ventas</li>
            </ul>
            <p class="text-sm text-gray-500 mt-2">Fuente: <a href="https://parachutedesign.ca/blog/consumer-behaviour-colour-theory/#Red_Urgency_and_Excitement" class="text-green-600 hover:underline">Parachute Design</a></p>
        </div>
        
        <div class="mb-6">
            <p class="font-semibold text-gray-800 mb-2">Pasión:</p>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li>Este tono ardiente tiene más asociaciones emocionales opuestas que cualquier otro color: el rojo está vinculado a la pasión y al amor</li>
                <li>Estas asociaciones podrían explicar por qué las personas que visten de rojo son constantemente calificadas como más atractivas por el sexo opuesto</li>
            </ul>
            <p class="text-sm text-gray-500 mt-2">Fuente: <a href="https://www.verywellmind.com/the-color-psychology-of-red-2795821" class="text-green-600 hover:underline">Verywell Mind</a></p>
        </div>
        
        <div class="mb-6">
            <p class="font-semibold text-gray-800 mb-2">Energía:</p>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li>Los estudios muestran que estar expuesto o usar rojo puede causar algunos cambios físicos</li>
                <li>Presión arterial elevada</li>
                <li>Metabolismo mejorado</li>
                <li>Aumento de la frecuencia cardíaca</li>
            </ul>
            <p class="text-sm text-gray-500 mt-2">Fuente: <a href="https://www.verywellmind.com/the-color-psychology-of-red-2795821" class="text-green-600 hover:underline">Verywell Mind</a></p>
        </div>

        <h3 class="text-2xl font-semibold text-gray-700 mb-4 mt-8">Aplicación Práctica</h3>
        
        <div class="mb-6">
            <p class="font-semibold text-gray-800 mb-2">Cuándo utilizar:</p>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li>Anuncios de peligro y alertas de seguridad</li>
                <li>Marcas de comida rápida para estimular el apetito</li>
                <li>Botones de llamada a la acción en sitios web</li>
            </ul>
            <p class="text-sm text-gray-500 mt-2">Fuente: <a href="https://ebac.mx/blog/color-rojo" class="text-green-600 hover:underline">EBAC</a></p>
        </div>
        
        <div class="mb-6 bg-red-600 text-white p-6 rounded-lg border-l-4 border-red-800">
            <p class="font-semibold mb-2">En Marketing:</p>
            <ul class="list-disc list-inside space-y-2 ml-4">
                <li>Botones "comprar ahora" o "oferta limitada"</li>
                <li>Elementos que requieren atención inmediata</li>
                <li>Logotipos de comida rápida (McDonald's, KFC)</li>
            </ul>
        </div>
        
        <div class="mb-6">
            <p class="font-semibold text-gray-800 mb-2">En Diseño Web:</p>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li>Banners de promoción temporales</li>
                <li>Iconos de alerta para errores del sistema</li>
                <li>Notificaciones de error en formularios</li>
            </ul>
        </div>
        
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
            <p class="font-semibold text-yellow-800 mb-2">⚠️ Evitar:</p>
            <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
                <li>Fondo completo de páginas largas (satura la percepción y genera fatiga visual)</li>
                <li>Texto largo sobre fondo rojo (dificulta la lectura)</li>
            </ul>
        </div>
    </div>

    <!-- SECCIÓN AZUL -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8 border-t-4 border-blue-600">
        <h2 class="text-3xl font-bold text-blue-600 mb-6 pb-3 border-b-2 border-blue-600">🔵 Color Azul</h2>
        
        <h3 class="text-2xl font-semibold text-gray-700 mb-4">Percepción Psicológica</h3>
        
        <div class="mb-6">
            <p class="font-semibold text-gray-800 mb-2">Confianza:</p>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li>Se vincula a la credibilidad y confiabilidad</li>
                <li>Al cuidado de los demás y responsabilidad</li>
                <li>Entendimiento y protección en marcas corporativas</li>
            </ul>
            <p class="text-sm text-gray-500 mt-2">Fuente: <a href="https://psicologiaymente.com/psicologia/que-significa-el-azul" class="text-green-600 hover:underline">Psicología y Mente</a></p>
        </div>
        
        <div class="mb-6">
            <p class="font-semibold text-gray-800 mb-2">Calma:</p>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li>Tonos más claros son frecuentemente asociados a la inocencia y a la generosidad</li>
                <li>Su vinculación más conocida es con la idea de serenidad y calma</li>
                <li>Se trata de uno de los colores más relacionados con la tranquilidad y el control de la situación</li>
            </ul>
            <p class="text-sm text-gray-500 mt-2">Fuente: <a href="https://psicologiaymente.com/psicologia/que-significa-el-azul" class="text-green-600 hover:underline">Psicología y Mente</a></p>
        </div>
        
        <div class="mb-6">
            <p class="font-semibold text-gray-800 mb-2">Profesionalismo:</p>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li>Los tonos más oscuros se relacionan con la inteligencia, el poder y el saber estar</li>
                <li>Inspira liderazgo y autoridad en entornos corporativos</li>
                <li>Precisión y frialdad para temas de ingeniería o desarrollo tecnológico</li>
            </ul>
            <p class="text-sm text-gray-500 mt-2">Fuente: <a href="https://psicologiaymente.com/psicologia/que-significa-el-azul" class="text-green-600 hover:underline">Psicología y Mente</a></p>
        </div>

        <h3 class="text-2xl font-semibold text-gray-700 mb-4 mt-8">Aplicación Práctica</h3>
        
        <div class="mb-6">
            <p class="font-semibold text-gray-800 mb-2">Cuándo utilizar:</p>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li>Formularios de registro y autenticación</li>
                <li>Botones "enviar" o "más información"</li>
                <li>Acciones que requieren confianza (datos personales, pagos)</li>
            </ul>
        </div>
        
        <div class="mb-6">
            <p class="font-semibold text-gray-800 mb-2">En Marketing:</p>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li>Productos tecnológicos y software (Microsoft, Facebook, IBM)</li>
                <li>Servicios financieros y bancarios (PayPal, Visa)</li>
                <li>Marcas de salud y bienestar (Oral-B, Nivea)</li>
            </ul>
            <p class="text-sm text-gray-500 mt-2">Fuente: <a href="https://www.verywellmind.com/the-color-psychology-of-blue-2795815" class="text-green-600 hover:underline">Verywell Mind - Blue Psychology</a></p>
        </div>
        
        <div class="mb-6 bg-blue-600 text-white p-6 rounded-lg border-l-4 border-blue-800">
            <p class="font-semibold mb-2">En Diseño Web:</p>
            <ul class="list-disc list-inside space-y-2 ml-4">
                <li>Tonos óptimos de uso: Azul royal #2563EB</li>
                <li>Brillo: 60-70% para mejor legibilidad</li>
                <li>Texto contrastante: #FFFFFF para accesibilidad</li>
            </ul>
        </div>
        
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
            <p class="font-semibold text-yellow-800 mb-2">⚠️ Evitar:</p>
            <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
                <li>En textos largos que tengan bajo contraste con el fondo</li>
                <li>Indicadores de alerta o error (reservar para rojo/amarillo)</li>
            </ul>
        </div>
    </div>

    <!-- SECCIÓN AMARILLO -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8 border-t-4 border-yellow-400">
        <h2 class="text-3xl font-bold text-yellow-600 mb-6 pb-3 border-b-2 border-yellow-400">🟡 Color Amarillo</h2>
        
        <h3 class="text-2xl font-semibold text-gray-700 mb-4">Percepción Psicológica</h3>
        
        <div class="mb-6">
            <p class="font-semibold text-gray-800 mb-2">Optimismo: 87% de asociación</p>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li>Relacionado con la energía solar y vitalidad</li>
                <li>Estimula pensamientos positivos y creatividad</li>
                <li>Reduce la percepción de riesgo en toma de decisiones</li>
            </ul>
        </div>
        
        <div class="mb-6">
            <p class="font-semibold text-gray-800 mb-2">Juventud: 92% de asociación</p>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li>Conectado con juegos infantiles y adolescentes</li>
                <li>Sugiere innovación y frescura en productos</li>
                <li>Atrae audiencia entre 18-35 años principalmente</li>
            </ul>
        </div>
        
        <div class="mb-6">
            <p class="font-semibold text-gray-800 mb-2">Atención: +65% de efectividad</p>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li>Segundo color más visible después del rojo</li>
                <li>Usado en señales de advertencia mundialmente</li>
                <li>Captura atención periférica efectivamente</li>
            </ul>
        </div>

        <h3 class="text-2xl font-semibold text-gray-700 mb-4 mt-8">Aplicación Práctica</h3>
        
        <div class="mb-6">
            <p class="font-semibold text-gray-800 mb-2">Cuándo utilizar:</p>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li>Señales de precaución y advertencia (zonas de construcción)</li>
                <li>Promociones y ofertas especiales para captar atención</li>
                <li>Productos infantiles y marcas juveniles</li>
            </ul>
            <p class="text-sm text-gray-500 mt-2">Fuente: <a href="https://www.verywellmind.com/the-color-psychology-of-yellow-2795823" class="text-green-600 hover:underline">Verywell Mind - Yellow Psychology</a></p>
        </div>
        
        <div class="mb-6">
            <p class="font-semibold text-gray-800 mb-2">En Marketing:</p>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li>Marcas de comida rápida combinado con rojo (McDonald's, Subway)</li>
                <li>Productos de limpieza y optimismo del hogar</li>
                <li>Destacar descuentos y ofertas limitadas</li>
            </ul>
            <p class="text-sm text-gray-500 mt-2">Fuente: <a href="https://www.color-meanings.com/yellow-color-meaning-the-color-yellow/" class="text-green-600 hover:underline">Color Meanings - Yellow in Marketing</a></p>
        </div>
        
        <div class="mb-6 bg-yellow-400 text-gray-800 p-6 rounded-lg border-l-4 border-yellow-600">
            <p class="font-semibold mb-2">En Diseño Web:</p>
            <ul class="list-disc list-inside space-y-2 ml-4">
                <li>Botones de acción secundaria o información adicional</li>
                <li>Banners de advertencia o notas importantes</li>
                <li>Elementos que requieren atención sin ser críticos</li>
            </ul>
            <p class="text-sm mt-2">Fuente: <a href="https://www.smashingmagazine.com/2010/01/color-theory-for-designers-part-1-the-meaning-of-color/" class="text-gray-700 hover:underline">Smashing Magazine - Color Theory</a></p>
        </div>
        
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
            <p class="font-semibold text-yellow-800 mb-2">⚠️ Evitar:</p>
            <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
                <li>Fondos amarillos brillantes en textos largos (cansa la vista)</li>
                <li>Uso excesivo que puede generar ansiedad o nerviosismo</li>
            </ul>
            <p class="text-sm text-gray-500 mt-2">Fuente: <a href="https://www.verywellmind.com/the-color-psychology-of-yellow-2795823" class="text-gray-700 hover:underline">Verywell Mind - Yellow Psychology</a></p>
        </div>
    </div>

    <!-- Botón de retorno -->
    <div class="text-center mb-8">
        <a href="{{ route('home') }}" class="inline-block bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition font-semibold">
            ← Volver al Inicio
        </a>
    </div>
</div>
@endsection
