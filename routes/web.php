<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlantaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/plantas', [PlantaController::class, 'index'])->name('plantas.index');
Route::get('/plantas/{id}', [PlantaController::class, 'show'])->name('plantas.show');

Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::post('/carrito/eliminar', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::post('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');

Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

Route::get('/admin/plantas/registrar', [PlantaController::class, 'create'])->name('plantas.create');
Route::post('/admin/plantas/save', [PlantaController::class, 'save'])->name('plantas.save');

Route::get('/admin/categorias/registrar', [CategoriaController::class, 'create'])->name('categorias.create');
Route::post('/admin/categorias/save', [CategoriaController::class, 'save'])->name('categorias.save');
