<x-layouts.admin>
    <x-slot name="header">
        Dashboard Trabajador
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Small Box: Pendientes -->
        <div class="bg-yellow-500 rounded-lg shadow-lg overflow-hidden text-white">
            <div class="p-4 flex justify-between items-center">
                <div>
                    <h3 class="text-3xl font-bold">{{ $pedidosPendientes }}</h3>
                    <p class="font-semibold">Pedidos Pendientes</p>
                </div>
                <div class="text-5xl opacity-50">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <a href="{{ route('pedidos.admin') }}?estado=pendiente" class="block text-center bg-black bg-opacity-20 py-2 hover:bg-opacity-30 transition">
                Ver más <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>

        <!-- Small Box: Confirmados -->
        <div class="bg-blue-400 rounded-lg shadow-lg overflow-hidden text-white">
            <div class="p-4 flex justify-between items-center">
                <div>
                    <h3 class="text-3xl font-bold">{{ $pedidosConfirmados }}</h3>
                    <p class="font-semibold">Pedidos Confirmados</p>
                </div>
                <div class="text-5xl opacity-50">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <a href="{{ route('pedidos.admin') }}?estado=confirmado" class="block text-center bg-black bg-opacity-20 py-2 hover:bg-opacity-30 transition">
                Ver más <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>

        <!-- Small Box: Completados -->
        <div class="bg-green-500 rounded-lg shadow-lg overflow-hidden text-white">
            <div class="p-4 flex justify-between items-center">
                <div>
                    <h3 class="text-3xl font-bold">{{ $pedidosCompletados }}</h3>
                    <p class="font-semibold">Pedidos Completados</p>
                </div>
                <div class="text-5xl opacity-50">
                    <i class="fas fa-check-double"></i>
                </div>
            </div>
            <a href="{{ route('pedidos.admin') }}?estado=completado" class="block text-center bg-black bg-opacity-20 py-2 hover:bg-opacity-30 transition">
                Ver más <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Gestion de Pedidos -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md mb-8">
        <div class="p-4 border-b dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                <i class="fas fa-tasks mr-2"></i> Gestión de Pedidos
            </h3>
        </div>
        <div class="p-6">
            <a href="{{ route('pedidos.admin') }}" class="block width-full text-center px-4 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                <i class="fas fa-list mr-2"></i> Ver Todos los Pedidos
            </a>
        </div>
    </div>

    <!-- Informacion -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
         <div class="p-4 border-b dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                <i class="fas fa-info-circle mr-2"></i> Información
            </h3>
        </div>
        <div class="p-6 text-gray-700 dark:text-gray-300">
             <p class="mb-2">
                <i class="fas fa-user w-6 text-center"></i> <strong>Usuario:</strong> {{ Auth::user()->name }}
            </p>
            <p class="mb-2">
                <i class="fas fa-envelope w-6 text-center"></i> <strong>Email:</strong> {{ Auth::user()->email }}
            </p>
            <p>
                <i class="fas fa-id-badge w-6 text-center"></i> <strong>Rol:</strong> Trabajador
            </p>
        </div>
    </div>
</x-layouts.admin>
