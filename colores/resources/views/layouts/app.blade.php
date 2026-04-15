<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Colorsaurio - Teoría del Color')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .color-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .color-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .color-card-palette {
            transition: all 0.3s ease;
        }
        .color-card-palette:hover {
            transform: scale(1.05);
        }
        .color-code-input {
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
        }
        .copy-btn {
            white-space: nowrap;
        }
        @media (max-width: 768px) {
            .color-card-palette {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navegación -->
    <nav class="gradient-bg text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-wrap items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="text-3xl">🦕</span>
                    <h1 class="text-2xl font-bold">Colorsaurio</h1>
                </div>
                <div class="flex flex-wrap gap-4 mt-3 md:mt-0">
                    <a href="{{ route('home') }}" class="hover:bg-white/20 px-4 py-2 rounded transition {{ request()->routeIs('home') ? 'bg-white/30' : '' }}">
                        🏠 Inicio
                    </a>
                    <a href="{{ route('teoria') }}" class="hover:bg-white/20 px-4 py-2 rounded transition {{ request()->routeIs('teoria') ? 'bg-white/30' : '' }}">
                        📚 Teoría
                    </a>
                    <a href="{{ route('generador.index') }}" class="hover:bg-white/20 px-4 py-2 rounded transition {{ request()->routeIs('generador.*') ? 'bg-white/30' : '' }}">
                        🎨 Paletas
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12 py-6">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} Colorsaurio 🦕 - Teoría del Color Mesosoica</p>
            <p class="text-sm text-gray-400 mt-2">RAAAWWWRRRR</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
