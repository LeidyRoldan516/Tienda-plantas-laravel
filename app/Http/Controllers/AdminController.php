<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Planta;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $viewData = [];
        $viewData["totalPlantas"] = Planta::count();
        $viewData["totalCategorias"] = Categoria::count();
        $viewData["plantas"] = Planta::orderBy('id', 'desc')->get();
        return view('admin.index')->with("viewData", $viewData);
    }
}
