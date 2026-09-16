<?php

namespace App\Http\Controllers;

use App\Models\Planta;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $destacadas = Planta::query()
            ->with('categoria')
            ->where('stock', '>', 0)
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        return view('home', compact('destacadas'));
    }
}
