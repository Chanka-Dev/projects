<x-layouts.admin>
    <x-slot name="header">
        Clientes Registrados
    </x-slot>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                <i class="fas fa-users mr-2"></i> Listado de Clientes
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            ID
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Nombre Completo
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Email
                        </th>
                         <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            WhatsApp
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Total Pedidos
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Último Pedido
                        </th>
                         <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                           Registrado
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">
                                {{ $cliente->id }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">
                                {{ $cliente->name }} {{ $cliente->apellido }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">
                                {{ $cliente->email }}
                            </td>
                             <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">
                                @if($cliente->telefono)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $cliente->telefono) }}" 
                                       target="_blank" class="text-green-500 hover:text-green-700">
                                        <i class="fab fa-whatsapp"></i> {{ $cliente->telefono }}
                                    </a>
                                @else
                                    <span class="text-gray-400 italic">No registrado</span>
                                @endif
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm text-center">
                                <span class="{{ $cliente->pedidos->count() > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }} text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    {{ $cliente->pedidos->count() }}
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">
                                @if($cliente->pedidos->count() > 0)
                                    @php
                                        $ultimoPedido = $cliente->pedidos->sortByDesc('created_at')->first();
                                        $estadoClasses = match($ultimoPedido->estado) {
                                            'completado' => 'bg-green-100 text-green-800',
                                            'pendiente' => 'bg-yellow-100 text-yellow-800',
                                            'confirmado' => 'bg-blue-100 text-blue-800',
                                            'cancelado' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <div class="flex flex-col">
                                        <span class="font-bold text-xs">#{{ $ultimoPedido->id }}</span>
                                        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded text-xs font-medium {{ $estadoClasses }} mt-1 w-max">
                                            {{ ucfirst($ultimoPedido->estado) }}
                                        </span>
                                        <span class="text-xs text-gray-500 mt-1">{{ $ultimoPedido->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">Sin pedidos</span>
                                @endif
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm dark:text-gray-300">
                                <small>{{ $cliente->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-center text-sm text-gray-500 dark:text-gray-400">
                                No hay clientes registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 text-sm text-gray-600 dark:text-gray-300">
             Total de clientes: <strong>{{ $clientes->count() }}</strong> | 
             Clientes con pedidos: <strong>{{ $clientes->filter(fn($c) => $c->pedidos->count() > 0)->count() }}</strong>
        </div>
    </div>
</x-layouts.admin>