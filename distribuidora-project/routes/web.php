<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Trabajador\TrabajadorDashboardController;
use App\Http\Controllers\Admin\UsuarioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rutas públicas - Catálogo (trabajadores/admins no pueden acceder)
Route::get('/', [CatalogoController::class, 'index'])->middleware('redirect.staff')->name('catalogo.index');

// Dashboard principal - redirige según rol
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas para CLIENTES autenticados
Route::middleware(['auth', 'role:cliente'])->group(function () {
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/agregar/{id}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::delete('/carrito/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::get('/pedido/confirmar', [PedidoController::class, 'formulario'])->name('pedido.formulario');
    Route::post('/pedido/guardar', [PedidoController::class, 'guardar'])->name('pedido.guardar');
});

// Rutas para TRABAJADORES (solo trabajadores, NO administradores)
Route::middleware(['auth', 'role:trabajador'])->prefix('trabajador')->group(function () {
    Route::get('/dashboard', [TrabajadorDashboardController::class, 'index'])->name('trabajador.dashboard');
    Route::get('/inventario', [\App\Http\Controllers\Trabajador\InventarioController::class, 'index'])->name('trabajador.inventario');
});

// Rutas compartidas: Pedidos (trabajador y administrador)
Route::middleware(['auth', 'role:trabajador,administrador'])->group(function () {
    Route::get('/pedidos/admin', [PedidoController::class, 'admin'])->name('pedidos.admin');
    Route::post('/pedidos/{pedido}/estado', [PedidoController::class, 'cambiarEstado'])->name('pedidos.estado');
    Route::get('/pedidos/{pedido}/pdf', [\App\Http\Controllers\OrderPdfController::class, 'generate'])->name('pedidos.pdf');
});

// Rutas para ADMINISTRADORES
Route::middleware(['auth', 'role:administrador'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Gestión de productos
    Route::resource('productos', ProductoController::class);

    // Gestión de categorías
    Route::resource('categorias', \App\Http\Controllers\Admin\CategoryController::class);
    
    // Gestión de clientes
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    
    // Gestión de usuarios (admin y trabajadores)
    Route::resource('usuarios', UsuarioController::class);
});

require __DIR__.'/auth.php';
