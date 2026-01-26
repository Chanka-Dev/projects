<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\GastoOperativoController;

/*
|--------------------------------------------------------------------------
| API Routes - Sistema Contable Boliviano
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas públicas (sin autenticación - solo para desarrollo)
Route::prefix('v1')->group(function () {
    
    // === VENTAS ===
    Route::apiResource('ventas', VentaController::class);
    Route::post('ventas/{id}/contabilizar', [VentaController::class, 'contabilizar']);
    Route::post('ventas/{id}/anular', [VentaController::class, 'anular']);
    Route::get('ventas/reportes/diario', [VentaController::class, 'reporteDiario']);
    Route::get('ventas/reportes/top-productos', [VentaController::class, 'topProductos']);
    
    // === COMPRAS ===
    Route::apiResource('compras', CompraController::class);
    Route::post('compras/{id}/recibir', [CompraController::class, 'recibir']);
    Route::post('compras/{id}/pagar', [CompraController::class, 'pagar']);
    Route::get('compras/reportes/por-proveedor', [CompraController::class, 'reportePorProveedor']);
    Route::get('compras/reportes/pendientes-pago', [CompraController::class, 'pendientesPago']);
    
    // === PRODUCTOS ===
    Route::apiResource('productos', ProductoController::class);
    
    // === INVENTARIO ===
    Route::get('inventario/stock/{producto}', [InventarioController::class, 'stock']);
    Route::get('inventario/stock-general', [InventarioController::class, 'stockGeneral']);
    Route::get('inventario/bajo-stock', [InventarioController::class, 'bajoStock']);
    Route::get('inventario/vencimientos', [InventarioController::class, 'vencimientos']);
    Route::get('inventario/mermas', [InventarioController::class, 'mermas']);
    Route::post('inventario/lotes/{lote}/merma', [InventarioController::class, 'registrarMerma']);
    Route::post('inventario/lotes/{lote}/ajustar', [InventarioController::class, 'ajustar']);
    Route::get('inventario/movimientos', [InventarioController::class, 'reporteMovimientos']);
    Route::get('inventario/kardex/{producto}', [InventarioController::class, 'kardex']);
    
    // === REPORTES CONTABLES BOLIVIANOS ===
    Route::prefix('reportes')->group(function () {
        Route::get('iva', [ReportesController::class, 'reporteIVA']);
        Route::get('it', [ReportesController::class, 'reporteIT']);
        Route::get('libro-mayor', [ReportesController::class, 'libroMayor']);
        Route::get('libro-diario', [ReportesController::class, 'libroDiario']);
        Route::get('balance-general', [ReportesController::class, 'balanceGeneral']);
        Route::get('estado-resultados', [ReportesController::class, 'estadoResultados']);
    });
    
    // === CLIENTES ===
    Route::apiResource('clientes', ClienteController::class);
    
    // === PROVEEDORES ===
    Route::apiResource('proveedores', ProveedoresController::class);
    
    // === GASTOS OPERATIVOS ===
    Route::apiResource('gastos', GastoOperativoController::class);
    Route::post('gastos/{id}/contabilizar', [GastoOperativoController::class, 'contabilizar']);
});

// Rutas protegidas (requieren autenticación)
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Aquí puedes duplicar las rutas anteriores si necesitas autenticación
    // Por ahora dejamos las rutas públicas para desarrollo
});
