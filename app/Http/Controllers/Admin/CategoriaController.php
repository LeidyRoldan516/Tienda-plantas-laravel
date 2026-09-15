<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    public function index(): View
    {
        return view('admin.categorias.index', [
            'categorias' => Categoria::orderBy('nombre')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.categorias.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:categorias,nombre'],
        ]);

        Categoria::create($datos);

        return redirect()->route('admin.categorias.index')
            ->with('mensaje', 'Categoría creada.');
    }

    public function show(Categoria $categoria): View
    {
        $categoria->load('plantas');

        return view('admin.categorias.show', compact('categoria'));
    }

    public function edit(Categoria $categoria): View
    {
        return view('admin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => [
                'required', 'string', 'max:255',
                Rule::unique('categorias', 'nombre')->ignore($categoria->id),
            ],
        ]);

        $categoria->update($datos);

        return redirect()->route('admin.categorias.index')
            ->with('mensaje', 'Categoría actualizada.');
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        if ($categoria->plantas()->exists()) {
            return back()->withErrors([
                'categoria' => 'No se puede eliminar una categoría que tiene plantas.',
            ]);
        }

        $categoria->delete();

        return redirect()->route('admin.categorias.index')
            ->with('mensaje', 'Categoría eliminada.');
    }
}
