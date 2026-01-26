<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Distribuidora') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-900 text-white selection:bg-red-500 selection:text-white">
    <!-- Navbar -->
    <nav class="bg-gray-800 border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-yellow-500 flex items-center gap-2">
                        <i class="fas fa-box-open"></i> Distribuidora
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-2 rounded-md text-sm font-medium transition">
                                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-300 hover:text-white hover:bg-gray-700 px-3 py-2 rounded-md text-sm font-medium transition">
                                <i class="fas fa-sign-in-alt mr-1"></i> Iniciar Sesión
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                                    <i class="fas fa-user-plus mr-1"></i> Registrarse
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-gray-900 overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-gray-900 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Catálogo de Productos</span>
                            <span class="block text-yellow-500">encuentra lo mejor al mejor precio</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-400 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Explora nuestra variedad de productos disponibles para ti. Regístrate para realizar pedidos y gestionar tus compras de manera fácil y rápida.
                        </p>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- Product Grid Placeholder (Optional preview) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Example Card 1 -->
            <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-700 hover:border-yellow-500 transition duration-300">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-white mb-2">GOLDEN 440cc</h3>
                    <p class="text-gray-400 mb-4">12 pack</p>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-yellow-500">Bs 100.00</span>
                        <span class="text-xs text-gray-400 bg-gray-700 px-2 py-1 rounded"><i class="fas fa-box"></i> Stock: 18</span>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="block w-full text-center border border-yellow-500 text-yellow-500 hover:bg-yellow-500 hover:text-white font-bold py-2 px-4 rounded transition">
                            <i class="fas fa-sign-in-alt mt-1"></i> Inicia sesión para comprar
                        </a>
                    </div>
                </div>
            </div>

             <!-- Example Card 2 -->
             <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-700 hover:border-yellow-500 transition duration-300">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-white mb-2">HUARI 440cc</h3>
                    <p class="text-gray-400 mb-4">12 pack</p>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-yellow-500">Bs 140.00</span>
                        <span class="text-xs text-gray-400 bg-gray-700 px-2 py-1 rounded"><i class="fas fa-box"></i> Stock: 35</span>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="block w-full text-center border border-yellow-500 text-yellow-500 hover:bg-yellow-500 hover:text-white font-bold py-2 px-4 rounded transition">
                            <i class="fas fa-sign-in-alt mt-1"></i> Inicia sesión para comprar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-gray-800 border-t border-gray-700 mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-400 text-sm">
                &copy; {{ date('Y') }} {{ config('app.name', 'Distribuidora') }}. Todos los derechos reservados.
            </p>
        </div>
    </footer>
</body>
</html>
