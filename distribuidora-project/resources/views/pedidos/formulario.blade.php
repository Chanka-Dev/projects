<x-layouts.shop>
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                    <i class="fas fa-file-invoice text-yellow-500 mr-2"></i> Confirmar Pedido
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Verifica tus datos y completa la dirección de entrega
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="bg-gray-900 px-6 py-4 border-b border-gray-800 flex items-center">
                    <i class="fas fa-user-circle text-yellow-500 text-xl mr-3"></i>
                    <h2 class="text-lg font-medium text-white">Datos del Cliente</h2>
                </div>

                <div class="p-6">
                    <!-- User Info Alert -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 mb-6 rounded-r">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    <span class="font-bold">Cliente:</span> {{ auth()->user()->name }} {{ auth()->user()->apellido }} <br>
                                    <span class="font-bold">Email:</span> {{ auth()->user()->email }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('pedido.guardar') }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="telefono" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                <i class="fas fa-phone mr-1"></i> Celular/WhatsApp <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="telefono" id="telefono" 
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block w-full p-2.5 pl-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                                       value="{{ old('telefono', auth()->user()->telefono) }}" 
                                       placeholder="Ej: +591 71234567" required>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fab fa-whatsapp text-gray-400"></i>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Si deseas recibir el pedido en otro número, puedes modificarlo aquí.
                            </p>
                            @error('telefono')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="direccion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                <i class="fas fa-map-marker-alt mr-1"></i> Dirección de Entrega <span class="text-red-500">*</span>
                            </label>
                            <textarea name="direccion" id="direccion" 
                                      rows="4" 
                                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                                      placeholder="Ingresa tu dirección completa (calle, número, referencia, ciudad)..." 
                                      required>{{ old('direccion') }}</textarea>
                            @error('direccion')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 p-4 mb-8 rounded-r">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                        Asegúrate de que tu dirección y teléfono sean correctos para garantizar la entrega rápida de tus bebidas.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <a href="{{ route('carrito.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 w-full sm:w-auto text-center">
                                <i class="fas fa-arrow-left mr-2"></i> Volver al Carrito
                            </a>
                            <button type="submit" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 w-full sm:w-auto transform transition hover:scale-105">
                                <i class="fas fa-check-circle mr-2"></i> Confirmar Pedido
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.shop>