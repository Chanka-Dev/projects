<x-layouts.shop>
    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-8">
                <div class="bg-blue-600 dark:bg-blue-800 px-6 py-4 border-b border-blue-500 flex items-center">
                    <i class="fas fa-user-edit text-white text-xl mr-3"></i>
                    <h2 class="text-lg font-medium text-white">Mi Perfil</h2>
                </div>

                <div class="p-6">
                    @if(session('status') === 'profile-updated')
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow" role="alert">
                            <div class="flex">
                                <div class="py-1"><i class="fas fa-check-circle mr-3"></i></div>
                                <div>
                                    <p class="font-bold">Perfil actualizado correctamente</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Nombre -->
                            <div>
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    <i class="fas fa-user mr-1 text-gray-400"></i> Nombre <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" 
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Apellido -->
                            <div>
                                <label for="apellido" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    <i class="fas fa-user mr-1 text-gray-400"></i> Apellido <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="apellido" id="apellido" 
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                                       value="{{ old('apellido', $user->apellido) }}" required>
                                @error('apellido')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    <i class="fas fa-envelope mr-1 text-gray-400"></i> Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" 
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <label for="telefono" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    <i class="fas fa-phone mr-1 text-gray-400"></i> Teléfono/WhatsApp <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="telefono" id="telefono" 
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pl-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                                           value="{{ old('telefono', $user->telefono) }}" 
                                           placeholder="Ej: +591 71234567" required>
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fab fa-whatsapp text-gray-400"></i>
                                    </div>
                                </div>
                                @error('telefono')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 mb-6 rounded-r">
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                <i class="fas fa-info-circle mr-1"></i> Los campos marcados con (*) son obligatorios.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('catalogo.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 w-full sm:w-auto text-center transition">
                                <i class="fas fa-arrow-left mr-2"></i> Volver
                            </a>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 w-full sm:w-auto text-center shadow transition transform hover:scale-105">
                                <i class="fas fa-save mr-2"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sección para cambiar contraseña -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="bg-gray-700 dark:bg-gray-900 px-6 py-4 border-b border-gray-600 flex items-center">
                    <i class="fas fa-key text-white text-xl mr-3"></i>
                    <h2 class="text-lg font-medium text-white">Cambiar Contraseña</h2>
                </div>

                <div class="p-6">
                    @if(session('status') === 'password-updated')
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow" role="alert">
                            <div class="flex">
                                <div class="py-1"><i class="fas fa-check-circle mr-3"></i></div>
                                <div>
                                    <p class="font-bold">Contraseña actualizada correctamente</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label for="current_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contraseña Actual</label>
                            <input type="password" name="current_password" id="current_password" 
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                            @error('current_password', 'updatePassword')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nueva Contraseña</label>
                                <input type="password" name="password" id="password" 
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                                @error('password', 'updatePassword')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirmar Nueva Contraseña</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="text-white bg-gray-600 hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800 w-full sm:w-auto text-center shadow transition transform hover:scale-105">
                                <i class="fas fa-save mr-2"></i> Actualizar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.shop>
