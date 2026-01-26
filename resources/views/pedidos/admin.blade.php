<x-layouts.admin>
    <x-slot name="header">
        Gestión de Pedidos
    </x-slot>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                <i class="fas fa-clipboard-list mr-2"></i> Listado de Pedidos
            </h3>
             <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">{{ $pedidos->count() }} pedidos</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            #
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Fecha
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Cliente
                        </th>
                         <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Contacto
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Productos
                        </th>
                         <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Total
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Estado
                        </th>
                         <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $pedido)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition" x-data="{ showModal: false }">
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300 font-bold">
                                #{{ $pedido->id }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">
                                <small>
                                    <i class="far fa-calendar"></i> {{ $pedido->created_at->format('d/m/Y') }}<br>
                                    <i class="far fa-clock"></i> {{ $pedido->created_at->format('H:i') }}
                                </small>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">
                                @if($pedido->user)
                                    <strong>{{ $pedido->user->name }} {{ $pedido->user->apellido }}</strong>
                                @elseif($pedido->cliente)
                                    <strong>{{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}</strong>
                                @else
                                    <em class="text-gray-500">Cliente no disponible</em>
                                @endif
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">
                                <small>
                                    @if($pedido->user)
                                        <div class="flex items-center gap-1"><i class="fas fa-phone w-4"></i> {{ $pedido->user->telefono }}</div>
                                        <div class="flex items-center gap-1"><i class="fas fa-envelope w-4"></i> {{ $pedido->user->email }}</div>
                                    @elseif($pedido->cliente)
                                        <div class="flex items-center gap-1"><i class="fas fa-phone w-4"></i> {{ $pedido->cliente->telefono }}</div>
                                    @endif
                                </small>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm">
                                <button @click="showModal = true" class="text-blue-600 hover:text-blue-900 border border-blue-600 hover:bg-blue-50 px-3 py-1 rounded transition text-xs">
                                     <i class="fas fa-eye"></i> Ver Detalle
                                </button>
                                
                                <!-- Modal -->
                                <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <div x-show="showModal" @click="showModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                        </div>

                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                        <div x-show="showModal" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 flex justify-between items-center border-b dark:border-gray-600">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                                    Detalle del Pedido #{{ $pedido->id }}
                                                </h3>
                                                <button @click="showModal = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:text-gray-300">
                                                <h6 class="font-bold border-b pb-2 mb-2"><i class="fas fa-user"></i> Información del Cliente</h6>
                                                <p class="text-sm mb-4">
                                                    @if($pedido->user)
                                                        <strong>Nombre:</strong> {{ $pedido->user->name }} {{ $pedido->user->apellido }}<br>
                                                        <strong>Email:</strong> {{ $pedido->user->email }}<br>
                                                        <strong>Teléfono:</strong> {{ $pedido->user->telefono }}
                                                    @elseif($pedido->cliente)
                                                        <strong>Nombre:</strong> {{ $pedido->cliente->nombre }} {{ $pedido->cliente->apellido }}<br>
                                                        <strong>Teléfono:</strong> {{ $pedido->cliente->telefono }}
                                                    @else
                                                        <em class="text-gray-500">Cliente no disponible</em>
                                                    @endif
                                                </p>
                                                
                                                @if($pedido->detalle_pedido && str_contains($pedido->detalle_pedido, 'Dirección:'))
                                                    <p class="text-sm mb-4">
                                                        <strong><i class="fas fa-map-marker-alt"></i> Dirección:</strong><br>
                                                        {{ trim(explode("\n", explode('Dirección:', $pedido->detalle_pedido)[1] ?? '')[0] ?? 'No especificada') }}
                                                    </p>
                                                @endif
                                                
                                                <h6 class="font-bold border-b pb-2 mb-2"><i class="fas fa-shopping-bag"></i> Productos</h6>
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full text-sm">
                                                        <thead>
                                                            <tr class="bg-gray-100 dark:bg-gray-700">
                                                                <th class="px-2 py-1 text-left">Producto</th>
                                                                <th class="px-2 py-1 text-center">Cant.</th>
                                                                <th class="px-2 py-1 text-right">P. Unit.</th>
                                                                <th class="px-2 py-1 text-right">Subtotal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $total = 0;
                                                            @endphp
                                                            @foreach($pedido->productos as $pp)
                                                                @php
                                                                    $subtotal = $pp->cantidad * $pp->precio_unitario;
                                                                    $total += $subtotal;
                                                                @endphp
                                                                <tr class="border-b dark:border-gray-600">
                                                                    <td class="px-2 py-1">{{ $pp->producto->nombre ?? 'Producto eliminado' }}</td>
                                                                    <td class="px-2 py-1 text-center">{{ $pp->cantidad }}</td>
                                                                    <td class="px-2 py-1 text-right">Bs {{ number_format($pp->precio_unitario, 2) }}</td>
                                                                    <td class="px-2 py-1 text-right">Bs {{ number_format($subtotal, 2) }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="font-bold">
                                                                <td colspan="3" class="px-2 py-2 text-right">TOTAL:</td>
                                                                <td class="px-2 py-2 text-right">Bs {{ number_format($total, 2) }}</td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                <button @click="showModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-600 dark:text-white dark:border-gray-500 dark:hover:bg-gray-500">
                                                    Cerrar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">
                                <strong class="text-green-600 dark:text-green-400">Bs {{ number_format($total, 2) }}</strong>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm">
                                @if($pedido->estado === 'pendiente')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                        <i class="fas fa-clock"></i> Pendiente
                                    </span>
                                @elseif($pedido->estado === 'confirmado')
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                        <i class="fas fa-check"></i> Confirmado
                                    </span>
                                @elseif($pedido->estado === 'completado')
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                        <i class="fas fa-check-double"></i> Completado
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                        <i class="fas fa-times"></i> Cancelado
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm">
                                <div class="flex space-x-2">
                                    @if($pedido->estado === 'pendiente')
                                        <form action="{{ route('pedidos.estado', $pedido) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="estado" value="confirmado">
                                            <button class="bg-blue-500 hover:bg-blue-600 text-white p-1 rounded transition" title="Confirmar">
                                                <i class="fas fa-check w-4 h-4 text-center"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('pedidos.estado', $pedido) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="estado" value="cancelado">
                                            <button class="bg-red-500 hover:bg-red-600 text-white p-1 rounded transition" title="Cancelar">
                                                <i class="fas fa-times w-4 h-4 text-center"></i>
                                            </button>
                                        </form>
                                    @elseif($pedido->estado === 'confirmado')
                                        <form action="{{ route('pedidos.estado', $pedido) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="estado" value="completado">
                                            <button class="bg-green-500 hover:bg-green-600 text-white p-1 rounded transition" title="Completar">
                                                <i class="fas fa-check-double w-4 h-4 text-center"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('pedidos.estado', $pedido) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="estado" value="cancelado">
                                            <button class="bg-red-500 hover:bg-red-600 text-white p-1 rounded transition" title="Rechazar/Cancelar" onclick="return confirm('¿Estás seguro? Esto cancelará el pedido.')">
                                                <i class="fas fa-times w-4 h-4 text-center"></i>
                                            </button>
                                        </form>
                                    @elseif($pedido->estado === 'completado')
                                         <form action="{{ route('pedidos.estado', $pedido) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="estado" value="cancelado">
                                            <button class="bg-red-500 hover:bg-red-600 text-white p-1 rounded transition" title="Rechazar/Devolver Stock" onclick="return confirm('¿Estás seguro? Esto revertirá el stock descontado.')">
                                                <i class="fas fa-undo w-4 h-4 text-center"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="{{ route('pedidos.pdf', $pedido) }}" class="bg-gray-500 hover:bg-gray-600 text-white p-1 rounded transition" title="Descargar PDF" target="_blank">
                                        <i class="fas fa-file-pdf w-4 h-4 text-center"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-center text-sm text-gray-500 dark:text-gray-400">
                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                No hay pedidos registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>