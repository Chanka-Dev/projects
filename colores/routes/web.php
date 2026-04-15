<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaletaController;

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

// Página principal
Route::get('/', function () {
    return view('home');
})->name('home');

// Sección de teoría del color
Route::get('/teoria', function () {
    return view('teoria');
})->name('teoria');

// Generador de paletas
Route::get('/generador', [PaletaController::class, 'index'])->name('generador.index');
Route::get('/generador/{tipo}', [PaletaController::class, 'generar'])->name('generador.generar');
