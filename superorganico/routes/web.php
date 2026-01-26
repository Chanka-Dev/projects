<?php

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

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\PlanCuentaController;
use App\Http\Controllers\GastoOperativoController;
use App\Http\Controllers\UsuarioController;

// Rutas de autenticación
Auth::routes(['register' => false]); // Desactivar registro público

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Dashboard - accesible para todos
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // Rutas SOLO para administradores
    Route::middleware('role:administrador')->group(function () {
        Route::resource('productos', ProductoController::class);
        Route::resource('proveedores', ProveedoresController::class);
        Route::post('proveedores/ajax/store', [ProveedoresController::class, 'storeAjax'])->name('proveedores.ajax.store');
        Route::resource('compras', CompraController::class);
        Route::post('compras/{compra}/recibir', [CompraController::class, 'recibir'])->name('compras.recibir');
        Route::resource('gastos-operativos', GastoOperativoController::class);
        Route::resource('plan-cuentas', PlanCuentaController::class);
        Route::resource('usuarios', UsuarioController::class);
        
        // Reportes - solo administrador
        Route::prefix('reportes')->name('reportes.')->group(function () {
            Route::get('libro-diario', [ReportesController::class, 'libroDiario'])->name('libro-diario');
            Route::get('libro-mayor', [ReportesController::class, 'libroMayor'])->name('libro-mayor');
            Route::get('balance', [ReportesController::class, 'balance'])->name('balance');
            Route::get('estado-resultados', [ReportesController::class, 'estadoResultados'])->name('estado-resultados');
            Route::get('iva', [ReportesController::class, 'iva'])->name('iva');
            Route::get('it', [ReportesController::class, 'it'])->name('it');
        });
    });

    // Rutas accesibles para empleados y administradores
    Route::resource('ventas', VentaController::class);
    Route::resource('clientes', ClienteController::class);
    Route::post('clientes/ajax/store', [ClienteController::class, 'storeAjax'])->name('clientes.ajax.store');
    
    // Inventario - solo lectura
    Route::get('inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::get('inventario/alertas', [InventarioController::class, 'alertas'])->name('inventario.alertas');
    Route::get('inventario/kardex', [InventarioController::class, 'kardex'])->name('inventario.kardex');
    Route::get('inventario/kardex/{producto}', [InventarioController::class, 'kardexProducto'])->name('inventario.kardex.producto');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
