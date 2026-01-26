<x-layouts.admin>
    <x-slot name="header">
        Inventario de Productos
    </x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-6 border-b dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                <i class="fas fa-boxes mr-2"></i> Listado de Productos
            </h3>
            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">{{ $productos->count() }} productos</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            ID
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Nombre
                        </th>
                         <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Descripción
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Precio
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Stock
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Estado
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $producto)
                        <tr class="transition {{ $producto->stock <= 5 ? 'bg-yellow-50 dark:bg-yellow-900/20' : 'hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300 font-bold">
                                #{{ $producto->id }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300 font-bold">
                                {{ $producto->nombre }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">
                                {{ Str::limit($producto->descripcion, 50) }}
                            </td>
                             <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm text-green-600 dark:text-green-400 font-bold">
                                Bs {{ number_format($producto->precio, 2) }}
                            </td>
                             <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm text-center">
                                @if($producto->stock > 10)
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">
                                        <i class="fas fa-check-circle"></i> {{ $producto->stock }}
                                    </span>
                                @elseif($producto->stock > 5)
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-yellow-200 dark:text-yellow-900">
                                        <i class="fas fa-exclamation-triangle"></i> {{ $producto->stock }}
                                    </span>
                                @elseif($producto->stock > 0)
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-red-200 dark:text-red-900">
                                        <i class="fas fa-exclamation-circle"></i> {{ $producto->stock }}
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                                        <i class="fas fa-times-circle"></i> {{ $producto->stock }}
                                    </span>
                                @endif
                            </td>
                             <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm text-center">
                                @if($producto->stock > 10)
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">
                                        <i class="fas fa-box"></i> Disponible
                                    </span>
                                @elseif($producto->stock > 0)
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-yellow-200 dark:text-yellow-900">
                                        <i class="fas fa-box-open"></i> Stock Bajo
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-red-200 dark:text-red-900">
                                        <i class="fas fa-ban"></i> Agotado
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-center text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-box-open fa-2x mb-2"></i><br>
                                No hay productos registrados en el inventario
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Info Boxes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-green-500 rounded-lg shadow-md p-4 text-white flex items-center justify-between">
            <div class="text-3xl opacity-50"><i class="fas fa-check-circle"></i></div>
            <div class="text-right">
                <span class="block text-sm font-semibold">Stock Alto</span>
                <span class="block text-2xl font-bold">{{ $productos->where('stock', '>', 10)->count() }}</span>
            </div>
        </div>

        <div class="bg-yellow-500 rounded-lg shadow-md p-4 text-white flex items-center justify-between">
            <div class="text-3xl opacity-50"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="text-right">
                <span class="block text-sm font-semibold">Stock Bajo</span>
                <span class="block text-2xl font-bold">{{ $productos->where('stock', '>', 0)->where('stock', '<=', 10)->count() }}</span>
            </div>
        </div>

        <div class="bg-red-500 rounded-lg shadow-md p-4 text-white flex items-center justify-between">
            <div class="text-3xl opacity-50"><i class="fas fa-times-circle"></i></div>
            <div class="text-right">
                <span class="block text-sm font-semibold">Agotados</span>
                <span class="block text-2xl font-bold">{{ $productos->where('stock', 0)->count() }}</span>
            </div>
        </div>

         <div class="bg-blue-500 rounded-lg shadow-md p-4 text-white flex items-center justify-between">
            <div class="text-3xl opacity-50"><i class="fas fa-boxes"></i></div>
            <div class="text-right">
                <span class="block text-sm font-semibold">Total Productos</span>
                <span class="block text-2xl font-bold">{{ $productos->count() }}</span>
            </div>
        </div>
    </div>

</x-layouts.admin>
