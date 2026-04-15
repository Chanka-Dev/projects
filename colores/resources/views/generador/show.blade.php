@extends('layouts.app')

@section('title', $paleta['nombre'] . ' - Colorsaurio')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg shadow-xl p-8 mb-8">
        <h1 class="text-4xl font-bold mb-2">{{ $paleta['nombre'] }}</h1>
        <p class="text-lg opacity-90">{{ $paleta['descripcion'] }}</p>
    </div>

    <!-- Info de la paleta -->
    <div class="grid md:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">🎭 Emoción que transmite</h3>
            <p class="text-xl text-gray-700">{{ $paleta['emocion'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">💼 Usos recomendados</h3>
            <ul class="text-gray-700 space-y-2">
                @foreach($paleta['usos'] as $uso)
                    <li class="flex items-center">
                        <span class="text-green-600 mr-2">✓</span> {{ $uso }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Paleta de colores -->
    <div class="bg-white rounded-lg shadow-xl p-8 mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Paleta de Colores</h2>
        
        <div class="grid md:grid-cols-5 gap-6">
            @foreach($paleta['colores'] as $color)
                <div class="color-card-palette">
                    <!-- Muestra del color -->
                    <div class="w-full h-48 rounded-t-lg shadow-lg" style="background-color: {{ $color['hex'] }}"></div>
                    
                    <!-- Información del color -->
                    <div class="p-4 bg-gray-50 rounded-b-lg">
                        <p class="font-bold text-gray-800 mb-2 text-center">{{ $color['nombre'] }}</p>
                        
                        <!-- Código HEX -->
                        <div class="mb-3">
                            <label class="text-xs text-gray-600 font-semibold block mb-1">HEX:</label>
                            <div class="flex items-center gap-2">
                                <input type="text" value="{{ $color['hex'] }}" readonly 
                                       class="color-code-input w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:border-indigo-500">
                                <button onclick="copiarCodigo('{{ $color['hex'] }}', this)" 
                                        class="copy-btn px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 transition">
                                    Copiar
                                </button>
                            </div>
                        </div>
                        
                        <!-- Código RGB -->
                        <div>
                            <label class="text-xs text-gray-600 font-semibold block mb-1">RGB:</label>
                            <div class="flex items-center gap-2">
                                <input type="text" value="{{ $color['rgb'] }}" readonly 
                                       class="color-code-input w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:border-indigo-500">
                                <button onclick="copiarCodigo('{{ $color['rgb'] }}', this)" 
                                        class="copy-btn px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 transition">
                                    Copiar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Vista previa en uso -->
    <div class="bg-white rounded-lg shadow-xl p-8 mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Vista Previa</h2>
        <p class="text-center text-gray-600 mb-6">Visualiza cómo se verían estos colores en un diseño web</p>
        
        <div class="border-2 border-gray-300 rounded-lg p-8" style="background-color: {{ $paleta['colores'][3]['hex'] }}20">
            <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-bold mb-4" style="color: {{ $paleta['colores'][0]['hex'] }}">
                    Ejemplo de Título
                </h3>
                <p class="text-gray-700 mb-6">
                    Este es un ejemplo de cómo se vería tu contenido con esta paleta de colores. 
                    Los colores seleccionados crean un impacto visual coherente y profesional.
                </p>
                <div class="flex gap-4">
                    <button class="px-6 py-3 rounded-lg text-white font-semibold shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5" 
                            style="background-color: {{ $paleta['colores'][0]['hex'] }}">
                        Botón Principal
                    </button>
                    <button class="px-6 py-3 rounded-lg text-white font-semibold shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5" 
                            style="background-color: {{ $paleta['colores'][1]['hex'] }}">
                        Botón Secundario
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Navegación -->
    <div class="flex justify-between items-center mb-8">
        <a href="{{ route('generador.index') }}" class="inline-block bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition font-semibold">
            ← Ver todas las paletas
        </a>
        <a href="{{ route('home') }}" class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition font-semibold">
            Volver al Inicio
        </a>
    </div>
</div>

@push('scripts')
<script>
function copiarCodigo(codigo, boton) {
    // Copiar al portapapeles
    navigator.clipboard.writeText(codigo).then(function() {
        // Cambiar texto del botón temporalmente
        const textoOriginal = boton.textContent;
        boton.textContent = '✓ Copiado';
        boton.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
        boton.classList.add('bg-green-600');
        
        // Restaurar después de 2 segundos
        setTimeout(function() {
            boton.textContent = textoOriginal;
            boton.classList.remove('bg-green-600');
            boton.classList.add('bg-green-600', 'hover:bg-green-700');
        }, 2000);
    }).catch(function(err) {
        console.error('Error al copiar: ', err);
        boton.textContent = '✗ Error';
        boton.classList.add('bg-red-600');
        setTimeout(function() {
            boton.textContent = 'Copiar';
            boton.classList.remove('bg-red-600');
            boton.classList.add('bg-green-600');
        }, 2000);
    });
}
</script>
@endpush
@endsection
