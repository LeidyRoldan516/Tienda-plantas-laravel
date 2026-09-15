<?php

<<<<<<< HEAD
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\PlantaController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\PedidoController;
=======
/**
 * Autor: Simon Martinez Gomez
 */

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Cliente\DashboardController as ClienteDashboardController;
use App\Http\Controllers\ProfileController;
>>>>>>> feature/autenticacion
use Illuminate\Support\Facades\Route;

// Públicas (sin auth): home y futuras rutas de catálogo.
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ClienteDashboardController::class, 'index'])
        ->name('cliente.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

<<<<<<< HEAD
Route::get('/catalogo', [CatalogoController::class, 'index'])
    ->name('catalogo.index');

Route::get('/catalogo/{planta}', [CatalogoController::class, 'show'])
    ->name('catalogo.show');

Route::middleware('auth')->group(function () {
    Route::get('/carrito', [CarritoController::class, 'index'])
        ->name('carrito.index');
    Route::post('/carrito', [CarritoController::class, 'agregar'])
        ->name('carrito.agregar');
    Route::patch('/carrito/{item}', [CarritoController::class, 'actualizar'])
        ->name('carrito.actualizar');
    Route::delete('/carrito/{item}', [CarritoController::class, 'eliminar'])
        ->name('carrito.eliminar');

    Route::get('/pedidos', [PedidoController::class, 'index'])
        ->name('pedidos.index');
    Route::post('/pedidos', [PedidoController::class, 'crear'])
        ->name('pedidos.crear');
    Route::get('/pedidos/{pedido}', [PedidoController::class, 'show'])
        ->name('pedidos.show');
    Route::patch('/pedidos/{pedido}/cancelar', [PedidoController::class, 'cancelar'])
        ->name('pedidos.cancelar');
});
=======
>>>>>>> feature/autenticacion
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
<<<<<<< HEAD
        Route::resource('categorias', CategoriaController::class);
        Route::resource('plantas', PlantaController::class);
    });
=======
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    });

require __DIR__.'/auth.php';
>>>>>>> feature/autenticacion
