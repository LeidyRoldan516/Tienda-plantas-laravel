<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    public function create(): View
    {
        $viewData = [];
        $viewData["categorias"] = Categoria::orderBy('nombre', 'asc')->get();
        return view('categorias.create')->with("viewData", $viewData);
    }

    public function save(Request $request): RedirectResponse
    {
        $request->validate([
            "nombre" => "required|string|max:255|unique:categorias,nombre",
        ]);

        $data = $request->only([
            "nombre",
        ]);

        Categoria::create($data);

        return back()->with("success", "Elemento creado satisfactoriamente");
    }
}
