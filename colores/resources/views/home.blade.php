@extends('layouts.app')

@section('title', 'Inicio - Colorsaurio')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Hero Section -->
    <div class="gradient-bg text-white rounded-lg shadow-2xl p-12 mb-12 text-center">
        <h1 class="text-5xl font-bold mb-4">🦕 Bienvenido a Colorsaurio</h1>
        <p class="text-xl mb-6">Descubre el poder de la psicología del color en el diseño web</p>
        <p class="text-lg opacity-90">Una herramienta completa para entender y aplicar la teoría del color</p>
    </div>

    <!-- Cards de Características -->
    <div class="grid md:grid-cols-3 gap-8 mb-12">
        <!-- Teoría -->
        <div class="color-card bg-white rounded-lg shadow-lg p-6 text-center">
            <div class="text-6xl mb-4">🦴</div>
            <h3 class="text-2xl font-bold mb-3 text-gray-800">Teoría del Color</h3>
            <p class="text-gray-600 mb-4">
                Explora los fundamentos de colores primarios, secundarios, terciarios y su impacto psicológico
            </p>
            <a href="{{ route('teoria') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                Ver Teoría
            </a>
        </div>

        <!-- Paletas -->
        <div class="color-card bg-white rounded-lg shadow-lg p-6 text-center">
            <div class="text-6xl mb-4">🦖</div>
            <h3 class="text-2xl font-bold mb-3 text-gray-800">Paletas de Colores</h3>
            <p class="text-gray-600 mb-4">
                Checa las paletas de colores basadas en emociones y propósitos específicos
            </p>
            <a href="{{ route('generador.index') }}" class="inline-block bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700 transition">
                Ver Paletas
            </a>
        </div>

        <!-- Círculo Cromático -->
        <div class="color-card bg-white rounded-lg shadow-lg p-6 text-center">
            <div class="text-6xl mb-4">🌿</div>
            <h3 class="text-2xl font-bold mb-3 text-gray-800">Combinaciones</h3>
            <p class="text-gray-600 mb-4">
                Aprende sobre combinaciones complementarias, análogas y tríadas de color
            </p>
            <a href="{{ route('teoria') }}#combinaciones" class="inline-block bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">
                Ver Combinaciones
            </a>
        </div>
    </div>

    <!-- Sección de Colores Primarios -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-12">
        <h2 class="text-3xl font-bold mb-6 text-center text-gray-800">Colores Primarios</h2>
        <p class="text-center text-gray-600 mb-8">
            Los tres colores fundamentales que no pueden obtenerse mediante mezcla
        </p>
        
        <div class="grid md:grid-cols-3 gap-6">
            <!-- Rojo -->
            <div class="border-4 border-red-500 rounded-lg p-6">
                <div class="w-full h-32 bg-red-600 rounded mb-4 flex items-center justify-center">
                    <span class="text-white text-2xl font-bold">🔴 ROJO</span>
                </div>
                <h4 class="font-bold text-lg mb-2 text-red-600">Urgencia y Pasión</h4>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>• Estimula el apetito</li>
                    <li>• Aumenta ritmo cardíaco</li>
                    <li>• Genera acción inmediata</li>
                </ul>
            </div>

            <!-- Azul -->
            <div class="border-4 border-blue-500 rounded-lg p-6">
                <div class="w-full h-32 bg-blue-600 rounded mb-4 flex items-center justify-center">
                    <span class="text-white text-2xl font-bold">🔵 AZUL</span>
                </div>
                <h4 class="font-bold text-lg mb-2 text-blue-600">Confianza y Calma</h4>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>• Reduce estrés</li>
                    <li>• Profesionalismo</li>
                    <li>• Color preferido 57% hombres</li>
                </ul>
            </div>

            <!-- Amarillo -->
            <div class="border-4 border-yellow-500 rounded-lg p-6">
                <div class="w-full h-32 bg-yellow-400 rounded mb-4 flex items-center justify-center">
                    <span class="text-gray-800 text-2xl font-bold">🟡 AMARILLO</span>
                </div> 
                <h4 class="font-bold text-lg mb-2 text-yellow-600">Optimismo y Energía</h4>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>• Aumenta serotonina</li>
                    <li>• Respuesta visual rápida</li>
                    <li>• Estimula creatividad</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="gradient-bg text-white rounded-lg shadow-2xl p-12 mb-12 text-center">
        <h2 class="text-3xl font-bold mb-4">¿Listo para ver las paletas de colores?</h2>
        <p class="text-lg mb-6">Usa nuestras paletas sugeridas para tus propósitos creativos</p>
        <a href="{{ route('generador.index') }}" class="inline-block bg-white text-purple-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition">
            Comenzar Ahora →
        </a>
    </div>
</div>
@endsection
