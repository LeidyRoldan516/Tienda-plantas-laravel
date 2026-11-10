<?php

use App\Http\Controllers\Api\PlantasDisponiblesController;
use Illuminate\Support\Facades\Route;

Route::get('/plantas-disponibles', [PlantasDisponiblesController::class, 'index'])
    ->name('api.plantas-disponibles.index');
