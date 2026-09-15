<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Planta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogoController extends Controller
{
    public function index(Request $request): View
    {
        $datos = $request->validate([
            'buscar' => ['nullable', 'string', 'max:100'],
            'categoria' => ['nullable', 'integer', 'min:1'],
        ]);

        $busqueda = trim($datos['buscar'] ?? '');
        $categoriaId = $datos['categoria'] ?? null;

        $consulta = Planta::query()
            ->with('categoria')
            ->where('stock', '>', 0);

        if ($busqueda !== '') {
            $consulta->where(function ($query) use ($busqueda) {
                $query->where('nombre', 'like', "%{$busqueda}%")
                    ->orWhere('descripcion', 'like', "%{$busqueda}%");
            });
        }

        if ($categoriaId !== null) {
            $consulta->where('categoria_id', $categoriaId);
        }

        return view('catalogo.index', [
            'plantas' => $consulta->orderBy('nombre')->paginate(12)->withQueryString(),
            'categorias' => Categoria::orderBy('nombre')->get(),
            'busqueda' => $busqueda,
            'categoriaId' => $categoriaId,
        ]);
    }

    public function show(Planta $planta): View
    {
        $planta->load('categoria');

        return view('catalogo.show', compact('planta'));
    }
}
