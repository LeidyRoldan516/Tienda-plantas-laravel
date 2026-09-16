<?php

/**
 * Autor: Simon Martinez Gomez
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Planta;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'plantas' => Planta::with('categoria')->orderBy('nombre')->get(),
            'categorias' => Categoria::orderBy('nombre')->get(),
            'totalUnidades' => (int) Planta::sum('stock'),
        ]);
    }
}
