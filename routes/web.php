<?php

use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\PedidoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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
