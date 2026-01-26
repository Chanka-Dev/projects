<x-layouts.shop>
    <!-- Hero Section -->
    <div class="relative bg-white dark:bg-gray-900 overflow-hidden pt-8 pb-12 transition-colors duration-300 border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center lg:text-left lg:flex lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">No te quedes sin Chela</span>
                        <span class="block text-yellow-500">encuentra los mejores precios</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-500 dark:text-gray-400 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        Marcas nacionales e importadas a los mejores precios en packs de 12 unidades.
                    </p>
                </div>
                <!-- Optional: Hero Image or Icon could go here -->
                <div class="mt-8 lg:mt-0 hidden lg:block text-yellow-500 opacity-20 transform rotate-12">
                   <i class="fas fa-beer fa-10x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded shadow-md" role="alert">
                <p class="font-bold">¡Éxito!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8 rounded shadow-md" role="alert">
                <p class="font-bold">Error</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Search and Filter -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8 border border-gray-200 dark:border-gray-700">
            <form action="{{ route('catalogo.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="md:col-span-2">
                    <label for="search" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Buscar</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-500 dark:text-gray-400"></i>
                        </div>
                        <input type="text" id="search" name="search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Buscar productos...">
                    </div>
                </div>
                <div>
                    <label for="categoria" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Categoría</label>
                    <select id="categoria" name="categoria" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:hover:bg-yellow-700 focus:outline-none dark:focus:ring-yellow-800">
                        <i class="fas fa-filter mr-2"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($productos as $producto)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-yellow-500 transition duration-300 flex flex-col h-full">
                    <!-- Image -->
                    <div class="h-48 overflow-hidden bg-gray-200 dark:bg-gray-700 relative group">
                        @if($producto->image_path)
                            <img src="{{ asset('storage/' . $producto->image_path) }}" alt="{{ $producto->nombre }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-500">
                                <i class="fas fa-image fa-3x"></i>
                            </div>
                        @endif
                        @if($producto->categoria)
                            <span class="absolute top-2 right-2 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded shadow">
                                {{ $producto->categoria->nombre }}
                            </span>
                        @endif
                    </div>

                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $producto->nombre }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4 flex-1 text-sm">{{ Str::limit($producto->descripcion, 100) }}</p>
                        
                        <div class="flex justify-between items-center mb-4 border-t border-gray-100 dark:border-gray-700 pt-4">
                            <span class="text-2xl font-bold text-yellow-600 dark:text-yellow-500">Bs {{ number_format($producto->precio, 2) }}</span>
                            @if($producto->stock > 0)
                                <span class="text-xs text-green-700 bg-green-100 dark:text-green-100 dark:bg-green-800 px-2 py-1 rounded-full font-semibold"><i class="fas fa-box margin-right-xs"></i> Stock: {{ $producto->stock }}</span>
                            @else
                                <span class="text-xs text-red-700 bg-red-100 dark:text-red-100 dark:bg-red-800 px-2 py-1 rounded-full font-semibold"><i class="fas fa-times-circle margin-right-xs"></i> Agotado</span>
                            @endif
                        </div>

                        <div class="mt-auto">
                            @auth
                                @if(auth()->user()->hasRole('cliente'))
                                    <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST" class="flex gap-2">
                                        @csrf
                                        <input type="number" name="cantidad" value="1" min="1" max="{{ $producto->stock }}" class="bg-gray-50 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white dark:bg-gray-700 text-center text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-20 p-2.5">
                                        <button type="submit" class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded transition flex justify-center items-center gap-2">
                                            <i class="fas fa-cart-plus"></i> <span class="hidden sm:inline">Agregar</span>
                                        </button>
                                    </form>
                                @else
                                    <div class="text-center text-gray-500 dark:text-gray-400 text-xs italic border border-gray-200 dark:border-gray-700 p-2 rounded bg-gray-50 dark:bg-gray-800">
                                        Modo Admin
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="block w-full text-center border-2 border-yellow-500 text-yellow-600 dark:text-yellow-500 hover:bg-yellow-500 hover:text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                                    <i class="fas fa-sign-in-alt mt-1"></i> Comprar
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3">
                    <div class="flex flex-col items-center justify-center py-12 text-center bg-white dark:bg-gray-800 rounded-lg shadow-md border-t-4 border-yellow-500">
                        <div class="text-yellow-500 mb-4">
                            <i class="fas fa-search fa-4x opacity-50"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">No se encontraron productos</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Intenta ajustar los filtros o la búsqueda.</p>
                        <a href="{{ route('catalogo.index') }}" class="mt-4 text-yellow-600 hover:text-yellow-700 font-medium">Limpiar filtros</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.shop>