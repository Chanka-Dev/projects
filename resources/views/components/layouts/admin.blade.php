<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Distribuidora') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed z-30 inset-y-0 left-0 w-64 transition-transform duration-300 transform bg-white dark:bg-gray-800 shadow-lg lg:translate-x-0 lg:static lg:inset-0 ease-in-out">
            <div class="flex items-center justify-center h-16 bg-gray-900 dark:bg-gray-900">
                <span class="text-white font-bold uppercase">
                    @if(Auth::user()->isAdministrador())
                        Admin Panel
                    @elseif(Auth::user()->isTrabajador())
                        Trabajador
                    @else
                        Distribuidora
                    @endif
                </span>
            </div>
            
            <nav class="mt-5 px-2">
                {{-- Rutas para ADMINISTRADORES --}}
                @if(Auth::user()->isAdministrador())
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 transition-colors duration-200 transform rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <i class="fas fa-tachometer-alt w-5 h-5"></i>
                        <span class="mx-4 font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('usuarios.index') }}" class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 transition-colors duration-200 transform rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-200 {{ request()->routeIs('usuarios.*') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <i class="fas fa-users-cog w-5 h-5"></i>
                        <span class="mx-4 font-medium">Usuarios</span>
                    </a>

                    <a href="{{ route('productos.index') }}" class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 transition-colors duration-200 transform rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-200 {{ request()->routeIs('productos.*') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <i class="fas fa-boxes w-5 h-5"></i>
                        <span class="mx-4 font-medium">Productos</span>
                    </a>

                    <a href="{{ route('categorias.index') }}" class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 transition-colors duration-200 transform rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-200 {{ request()->routeIs('categorias.*') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <i class="fas fa-tags w-5 h-5"></i>
                        <span class="mx-4 font-medium">Categorías</span>
                    </a>

                    <a href="{{ route('clientes.index') }}" class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 transition-colors duration-200 transform rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-200 {{ request()->routeIs('clientes.index') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <i class="fas fa-users w-5 h-5"></i>
                        <span class="mx-4 font-medium">Clientes</span>
                    </a>

                    <a href="{{ route('pedidos.admin') }}" class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 transition-colors duration-200 transform rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-200 {{ request()->routeIs('pedidos.admin') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <i class="fas fa-clipboard-list w-5 h-5"></i>
                        <span class="mx-4 font-medium">Pedidos</span>
                    </a>
                @endif

                {{-- Rutas para TRABAJADORES --}}
                @if(Auth::user()->isTrabajador())
                    <a href="{{ route('trabajador.dashboard') }}" class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 transition-colors duration-200 transform rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-200 {{ request()->routeIs('trabajador.dashboard') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <i class="fas fa-tachometer-alt w-5 h-5"></i>
                        <span class="mx-4 font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('pedidos.admin') }}" class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 transition-colors duration-200 transform rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-200 {{ request()->routeIs('pedidos.admin') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <i class="fas fa-clipboard-check w-5 h-5"></i>
                        <span class="mx-4 font-medium">Gestionar Pedidos</span>
                    </a>

                    <a href="{{ route('trabajador.inventario') }}" class="flex items-center px-4 py-2 mt-2 text-gray-600 dark:text-gray-300 transition-colors duration-200 transform rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-200 {{ request()->routeIs('trabajador.inventario') ? 'bg-gray-200 dark:bg-gray-700' : '' }}">
                        <i class="fas fa-warehouse w-5 h-5"></i>
                        <span class="mx-4 font-medium">Ver Inventario</span>
                    </a>
                @endif
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
             <!-- Header -->
            <header class="flex justify-between items-center py-4 px-6 bg-white dark:bg-gray-800 border-b dark:border-gray-700 transition-colors duration-300">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    
                    @if (isset($header))
                        <h2 class="ml-4 text-xl font-semibold text-gray-800 dark:text-gray-200">
                            {{ $header }}
                        </h2>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none transition-colors duration-200">
                        <!-- Sun Icon (Show in Dark Mode) -->
                        <svg x-show="darkMode" class="w-6 h-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Moon Icon (Show in Light Mode) -->
                        <svg x-show="!darkMode" class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                     <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" class="relative block h-8 w-8 rounded-full overflow-hidden shadow focus:outline-none">
                             <img class="h-full w-full object-cover" src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" alt="Your avatar">
                        </button>

                        <div x-show="dropdownOpen" @click="dropdownOpen = false" class="fixed inset-0 z-10 w-full h-full" style="display: none;"></div>

                        <div x-show="dropdownOpen" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md overflow-hidden shadow-xl z-20" style="display: none;">
                            <div class="px-4 py-2 text-xs text-gray-500 dark:text-gray-400 border-b dark:border-gray-700">
                                Rol: {{ ucfirst(Auth::user()->role) }}
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-indigo-600 hover:text-white">Perfil</a>
                             <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-indigo-600 hover:text-white">Cerrar Sesión</a>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
                <div class="container mx-auto px-6 py-8">
                     @if (session('info'))
                        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4" role="alert">
                            <p class="font-bold">Info</p>
                            <p>{{ session('info') }}</p>
                        </div>
                    @endif
                    
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>
