<?php

/**
 * Autor: Simon Martinez Gomez
 */

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('cliente.dashboard');
    }
}
