<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Planta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlantaController extends Controller
{
    public function index(Request $request): View
    {
        $viewData = [];
        $viewData["categorias"] = Categoria::orderBy('nombre', 'asc')->get();

        $categoriaId = $request->query('categoria_id');
        $consulta = Planta::orderBy('nombre', 'asc');
        if (! is_null($categoriaId) && $categoriaId !== '') {
            $consulta->where('categoria_id', (int) $categoriaId);
        }
        $viewData["plantas"] = $consulta->get();
        $viewData["categoriaSeleccionada"] = $categoriaId;

        return view('plantas.index')->with("viewData", $viewData);
    }

    public function show(int $id): View
    {
        $viewData = [];
        $viewData["planta"] = Planta::findOrFail($id);
        return view('plantas.show')->with("viewData", $viewData);
    }

    public function create(): View
    {
        $viewData = [];
        $viewData["categorias"] = Categoria::orderBy('nombre', 'asc')->get();
        return view('plantas.create')->with("viewData", $viewData);
    }

    public function save(Request $request): RedirectResponse
    {
        $request->validate([
            "nombre" => "required|string|max:255",
            "descripcion" => "required|string",
            "precio" => "required|integer|min:0",
            "stock" => "required|integer|min:0",
            "imagen_url" => "nullable|string|max:500",
            "categoria_id" => "required|integer|exists:categorias,id",
        ]);

        $data = $request->only([
            "nombre",
            "descripcion",
            "precio",
            "stock",
            "imagen_url",
            "categoria_id",
        ]);

        Planta::create($data);

        return back()->with("success", "Elemento creado satisfactoriamente");
    }
}
