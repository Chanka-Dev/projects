<x-layouts.shop>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                    <i class="fas fa-shopping-cart text-yellow-500 mr-2"></i> Mi Carrito de Compras
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Revisa los productos antes de confirmar tu pedido
                </p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow" role="alert">
                    <div class="flex">
                        <div class="py-1"><i class="fas fa-check-circle mr-3"></i></div>
                        <div>
                            <p class="font-bold">¡Éxito!</p>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(empty($carrito))
                <div class="bg-blue-50 dark:bg-gray-800 border-l-4 border-blue-500 p-8 rounded shadow text-center">
                    <div class="text-blue-500 mb-4">
                        <i class="fas fa-shopping-basket fa-4x opacity-50"></i>
                    </div>
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-2">Tu carrito está vacío</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">¿Aún no te decides? Tenemos las mejores cervezas esperando por ti.</p>
                    <a href="{{ route('catalogo.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 transition">
                        <i class="fas fa-arrow-left mr-2"></i> Ir a Comprar
                    </a>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Producto</th>
                                    <th scope="col" class="px-6 py-3 text-center">Cantidad</th>
                                    <th scope="col" class="px-6 py-3 text-right">Precio Unitario</th>
                                    <th scope="col" class="px-6 py-3 text-right">Subtotal</th>
                                    <th scope="col" class="px-6 py-3 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($carrito as $id => $item)
                                    @php $subtotal = $item['precio'] * $item['cantidad']; $total += $subtotal; @endphp
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                            {{ $item['nombre'] }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">
                                                {{ $item['cantidad'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            Bs {{ number_format($item['precio'], 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">
                                            Bs {{ number_format($subtotal, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <form action="{{ route('carrito.eliminar', $id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline p-2 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-full transition" onclick="return confirm('¿Estás seguro de eliminar este producto del carrito?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-50 dark:bg-gray-700 border-t-2 border-gray-200 dark:border-gray-600">
                                    <td colspan="3" class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white uppercase">Total:</td>
                                    <td class="px-6 py-4 text-right font-extrabold text-xl text-yellow-600 dark:text-yellow-400">
                                        Bs {{ number_format($total, 2) }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <a href="{{ route('catalogo.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 w-full sm:w-auto text-center transition">
                            <i class="fas fa-arrow-left mr-2"></i> Seguir Comprando
                        </a>
                        <a href="{{ route('pedido.formulario') }}" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 w-full sm:w-auto text-center shadow-lg transition transform hover:scale-105">
                            <i class="fas fa-check mr-2"></i> Confirmar Pedido
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.shop>