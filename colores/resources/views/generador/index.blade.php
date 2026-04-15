@extends('layouts.app')

@section('title', 'Generador de Paletas - Colorsaurio')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg shadow-xl p-8 mb-8 text-center">
        <h1 class="text-4xl font-bold mb-2">🦖 Paletas de colores</h1>
        <p class="text-lg opacity-90">Usa paletas de colores basadas en emociones y propósitos específicos</p>
    </div>

    <!-- Selecciona tu paleta -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Selecciona el tipo de paleta según tu objetivo:</h2>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Urgencia -->
            <a href="{{ route('generador.generar', 'urgencia') }}" class="group">
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1 border-t-4 border-red-600">
                    <div class="text-6xl mb-4">🔴</div>
                    <h3 class="text-2xl font-bold text-red-600 mb-2">Urgencia y Acción</h3>
                    <p class="text-gray-600 mb-4">Paleta para generar urgencia y captar atención inmediata</p>
                    <div class="flex gap-2 mb-3">
                        <div class="w-8 h-8 rounded bg-red-600"></div>
                        <div class="w-8 h-8 rounded bg-red-700"></div>
                        <div class="w-8 h-8 rounded bg-red-500"></div>
                        <div class="w-8 h-8 rounded bg-red-400"></div>
                        <div class="w-8 h-8 rounded bg-red-800"></div>
                    </div>
                    <span class="text-red-600 font-semibold group-hover:underline">Ver Paleta →</span>
                </div>
            </a>

            <!-- Confianza -->
            <a href="{{ route('generador.generar', 'confianza') }}" class="group">
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1 border-t-4 border-blue-600">
                    <div class="text-6xl mb-4">🔵</div>
                    <h3 class="text-2xl font-bold text-blue-600 mb-2">Confianza y Profesionalismo</h3>
                    <p class="text-gray-600 mb-4">Transmite confianza, calma y profesionalismo</p>
                    <div class="flex gap-2 mb-3">
                        <div class="w-8 h-8 rounded bg-blue-600"></div>
                        <div class="w-8 h-8 rounded bg-blue-700"></div>
                        <div class="w-8 h-8 rounded bg-blue-500"></div>
                        <div class="w-8 h-8 rounded bg-blue-400"></div>
                        <div class="w-8 h-8 rounded bg-blue-800"></div>
                    </div>
                    <span class="text-blue-600 font-semibold group-hover:underline">Ver Paleta →</span>
                </div>
            </a>

            <!-- Optimismo -->
            <a href="{{ route('generador.generar', 'optimismo') }}" class="group">
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1 border-t-4 border-yellow-500">
                    <div class="text-6xl mb-4">🟡</div>
                    <h3 class="text-2xl font-bold text-yellow-600 mb-2">Optimismo y Energía</h3>
                    <p class="text-gray-600 mb-4">Evoca optimismo, juventud y creatividad</p>
                    <div class="flex gap-2 mb-3">
                        <div class="w-8 h-8 rounded bg-yellow-500"></div>
                        <div class="w-8 h-8 rounded bg-yellow-600"></div>
                        <div class="w-8 h-8 rounded bg-yellow-400"></div>
                        <div class="w-8 h-8 rounded bg-yellow-300"></div>
                        <div class="w-8 h-8 rounded bg-yellow-700"></div>
                    </div>
                    <span class="text-yellow-600 font-semibold group-hover:underline">Ver Paleta →</span>
                </div>
            </a>

            <!-- Naturaleza -->
            <a href="{{ route('generador.generar', 'naturaleza') }}" class="group">
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1 border-t-4 border-green-600">
                    <div class="text-6xl mb-4">🟢</div>
                    <h3 class="text-2xl font-bold text-green-600 mb-2">Naturaleza y Crecimiento</h3>
                    <p class="text-gray-600 mb-4">Transmite frescura, crecimiento y armonía</p>
                    <div class="flex gap-2 mb-3">
                        <div class="w-8 h-8 rounded bg-green-600"></div>
                        <div class="w-8 h-8 rounded bg-green-700"></div>
                        <div class="w-8 h-8 rounded bg-green-500"></div>
                        <div class="w-8 h-8 rounded bg-green-400"></div>
                        <div class="w-8 h-8 rounded bg-green-800"></div>
                    </div>
                    <span class="text-green-600 font-semibold group-hover:underline">Ver Paleta →</span>
                </div>
            </a>

            <!-- Creatividad -->
            <a href="{{ route('generador.generar', 'creatividad') }}" class="group">
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1 border-t-4 border-purple-600">
                    <div class="text-6xl mb-4">🟣</div>
                    <h3 class="text-2xl font-bold text-purple-600 mb-2">Creatividad y Lujo</h3>
                    <p class="text-gray-600 mb-4">Evoca creatividad, lujo y sofisticación</p>
                    <div class="flex gap-2 mb-3">
                        <div class="w-8 h-8 rounded" style="background-color: #9B59B6"></div>
                        <div class="w-8 h-8 rounded" style="background-color: #8E44AD"></div>
                        <div class="w-8 h-8 rounded" style="background-color: #A569BD"></div>
                        <div class="w-8 h-8 rounded" style="background-color: #BB8FCE"></div>
                        <div class="w-8 h-8 rounded" style="background-color: #6C3483"></div>
                    </div>
                    <span class="text-purple-600 font-semibold group-hover:underline">Ver Paleta →</span>
                </div>
            </a>

            <!-- Elegancia -->
            <a href="{{ route('generador.generar', 'elegancia') }}" class="group">
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition transform hover:-translate-y-1 border-t-4 border-orange-600">
                    <div class="text-6xl mb-4">🟠</div>
                    <h3 class="text-2xl font-bold text-orange-600 mb-2">Energía y Diversión</h3>
                    <p class="text-gray-600 mb-4">Combina energía con calidez y diversión</p>
                    <div class="flex gap-2 mb-3">
                        <div class="w-8 h-8 rounded bg-orange-600"></div>
                        <div class="w-8 h-8 rounded bg-orange-700"></div>
                        <div class="w-8 h-8 rounded bg-orange-500"></div>
                        <div class="w-8 h-8 rounded bg-orange-400"></div>
                        <div class="w-8 h-8 rounded bg-orange-800"></div>
                    </div>
                    <span class="text-orange-600 font-semibold group-hover:underline">Ver Paleta →</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Info adicional -->
    <div class="bg-green-50 rounded-lg p-6 border-l-4 border-green-600">
        <h3 class="text-xl font-bold text-green-900 mb-2">🦕 ¿Cómo usar los colores?</h3>
        <ul class="text-gray-700 space-y-2">
            <li>• Selecciona la paleta que mejor se adapte al objetivo de tu proyecto</li>
            <li>• Visualiza los colores y sus códigos HEX/RGB</li>
            <li>• Copia los códigos para usarlos en tu diseño web, aplicación o material gráfico</li>
            <li>• Aprende cuándo y cómo usar cada paleta de forma efectiva</li>
        </ul>
    </div>

    <!-- Botón de retorno -->
    <div class="text-center mt-8">
        <a href="{{ route('home') }}" class="inline-block bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition font-semibold">
            ← Volver al Inicio
        </a>
    </div>
</div>
@endsection
