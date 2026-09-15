<?php

namespace App\Http\Controllers;

use App\Models\Planta;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $viewData = [];
        $viewData["destacadas"] = Planta::orderBy('id', 'desc')->take(6)->get();
        return view('home')->with("viewData", $viewData);
    }
}
