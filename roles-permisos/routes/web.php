<?php
  
use Illuminate\Support\Facades\Route;
  
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EtiquetaController;
use App\Http\Controllers\IngredienteController;
use App\Http\Controllers\RecetaController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('etiquetas', EtiquetaController::class);
    Route::resource('ingredientes', IngredienteController::class);
    Route::resource('recetas', RecetaController::class);
});