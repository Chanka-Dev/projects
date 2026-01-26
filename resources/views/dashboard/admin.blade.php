<x-layouts.admin>
    <x-slot name="header">
        Dashboard Administrador
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Small Box: Productos -->
        <div class="bg-blue-500 rounded-lg shadow-lg overflow-hidden text-white">
            <div class="p-4 flex justify-between items-center">
                <div>
                    <h3 class="text-3xl font-bold">{{ $totalProductos }}</h3>
                    <p class="font-semibold">Productos</p>
                </div>
                <div class="text-5xl opacity-50">
                    <i class="fas fa-box"></i>
                </div>
            </div>
            <a href="{{ route('productos.index') }}" class="block text-center bg-black bg-opacity-20 py-2 hover:bg-opacity-30 transition">
                Ver más <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>

        <!-- Small Box: Clientes -->
        <div class="bg-green-500 rounded-lg shadow-lg overflow-hidden text-white">
            <div class="p-4 flex justify-between items-center">
                <div>
                    <h3 class="text-3xl font-bold">{{ $totalClientes }}</h3>
                    <p class="font-semibold">Clientes</p>
                </div>
                <div class="text-5xl opacity-50">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <a href="{{ route('clientes.index') }}" class="block text-center bg-black bg-opacity-20 py-2 hover:bg-opacity-30 transition">
                Ver más <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>

        <!-- Small Box: Pedidos -->
        <div class="bg-yellow-500 rounded-lg shadow-lg overflow-hidden text-white">
            <div class="p-4 flex justify-between items-center">
                <div>
                    <h3 class="text-3xl font-bold">{{ $totalPedidos }}</h3>
                    <p class="font-semibold">Pedidos</p>
                </div>
                <div class="text-5xl opacity-50">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
            <a href="{{ route('pedidos.admin') }}" class="block text-center bg-black bg-opacity-20 py-2 hover:bg-opacity-30 transition">
                Ver más <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>

        <!-- Small Box: Usuarios -->
        <div class="bg-red-500 rounded-lg shadow-lg overflow-hidden text-white">
            <div class="p-4 flex justify-between items-center">
                <div>
                    <h3 class="text-3xl font-bold">{{ $totalUsuarios }}</h3>
                    <p class="font-semibold">Usuarios del Sistema</p>
                </div>
                <div class="text-5xl opacity-50">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
            <a href="{{ route('usuarios.index') }}" class="block text-center bg-black bg-opacity-20 py-2 hover:bg-opacity-30 transition">
                Ver más <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Gestión del Sistema -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <div class="p-4 border-b dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                <i class="fas fa-chart-line mr-2"></i> Gestión del Sistema
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('productos.create') }}" class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i> Nuevo Producto
                </a>
                <a href="{{ route('usuarios.create') }}" class="flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded hover:bg-green-700 transition">
                    <i class="fas fa-user-plus mr-2"></i> Nuevo Usuario
                </a>
                <a href="{{ route('pedidos.admin') }}" class="flex items-center justify-center px-4 py-3 bg-yellow-600 text-white rounded hover:bg-yellow-700 transition">
                    <i class="fas fa-list mr-2"></i> Ver Pedidos
                </a>
            </div>
        </div>
    </div>
</x-layouts.admin>
