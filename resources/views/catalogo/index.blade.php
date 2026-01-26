<x-layouts.admin>
    <x-slot name="header">
        Catálogo de Productos
    </x-slot>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($productos as $producto)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden flex flex-col h-full hover:shadow-lg transition-shadow duration-300">
                <div class="p-6 flex-1 flex flex-col">
                    <h5 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">{{ $producto->nombre }}</h5>
                    <p class="text-gray-700 dark:text-gray-300 mb-4 flex-1">{{ $producto->descripcion }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Bs {{ number_format($producto->precio, 2) }}</p>
                    
                    <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST" class="flex items-center gap-2 mt-auto">
                        @csrf
                        <input type="number" name="cantidad" value="1" min="1" max="{{ $producto->stock }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-20 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 flex justify-center items-center">
                            Añadir <i class="fas fa-cart-plus ml-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</x-layouts.admin>
