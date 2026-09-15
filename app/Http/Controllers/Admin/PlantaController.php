<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Planta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlantaController extends Controller
{
    public function index(): View
    {
        return view('admin.plantas.index', [
            'plantas' => Planta::with('categoria')->orderBy('id')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.plantas.create', [
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Planta::create($this->datosValidados($request));

        return redirect()->route('admin.plantas.index')
            ->with('success', 'Planta creada correctamente.');
    }

    public function show(Planta $planta): View
    {
        return view('admin.plantas.show', [
            'planta' => $planta->load('categoria'),
        ]);
    }

    public function edit(Planta $planta): View
    {
        return view('admin.plantas.edit', [
            'planta' => $planta,
            'categorias' => Categoria::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Planta $planta): RedirectResponse
    {
        $planta->update($this->datosValidados($request));

        return redirect()->route('admin.plantas.index')
            ->with('success', 'Planta actualizada correctamente.');
    }

    public function destroy(Planta $planta): RedirectResponse
    {
        if ($planta->itemsPedido()->exists() || $planta->itemsCarrito()->exists()) {
            return redirect()->route('admin.plantas.index')
                ->with('error', 'No se puede eliminar una planta presente en pedidos o carritos.');
        }

        $planta->delete();

        return redirect()->route('admin.plantas.index')
            ->with('success', 'Planta eliminada correctamente.');
    }

    private function datosValidados(Request $request): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'precio' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'imagen_url' => ['nullable', 'url', 'max:2048'],
            'categoria_id' => ['required', 'exists:categorias,id'],
        ]);
    }
}
