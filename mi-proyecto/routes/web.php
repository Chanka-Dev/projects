<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\TrabajadoraController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\CitaServicioController;
use App\Http\Controllers\HistorialServicioController;
use App\Http\Controllers\RecordatorioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PagoTrabajadoraController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('clientes', ClienteController::class);
Route::resource('trabajadoras', TrabajadoraController::class);
Route::resource('servicios', ServicioController::class);
Route::resource('citas', CitaController::class);
Route::resource('pagos', PagoController::class);
Route::post('/pagos/{pago}/completar', [PagoController::class, 'marcarCompletado'])->name('pagos.completar');
Route::resource('citas-servicios', CitaServicioController::class);
Route::resource('historial-servicios', HistorialServicioController::class);
Route::resource('recordatorios', RecordatorioController::class);
Route::resource('pago-trabajadoras', PagoTrabajadoraController::class);

// Rutas adicionales para pagos a trabajadoras
Route::match(['get', 'post'], '/pago-trabajadoras/generar-semanal', [PagoTrabajadoraController::class, 'generarSemanal'])
    ->name('pago-trabajadoras.generar-semanal');
Route::post('/pago-trabajadoras/generar-rapido/{trabajadora}', [PagoTrabajadoraController::class, 'generarPagoRapido'])
    ->name('pago-trabajadoras.generar-rapido');
