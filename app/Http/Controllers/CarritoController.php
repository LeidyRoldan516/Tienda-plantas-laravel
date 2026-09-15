<?php

namespace App\Http\Controllers;

use App\Models\CarritoCompras;
use App\Models\ItemCarrito;
use App\Models\Planta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarritoController extends Controller
{
    public function index(): View
    {
        $carrito = CarritoCompras::firstOrCreate([
            'usuario_id' => auth()->id(),
        ]);

        $carrito->load('items.planta');

        return view('carrito.index', [
            'carrito' => $carrito,
            'total' => $carrito->items->sum('subtotal'),
        ]);
    }

    public function agregar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'planta_id' => ['required', 'integer', 'exists:plantas,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
        ]);

        $planta = Planta::findOrFail($datos['planta_id']);
        $carrito = CarritoCompras::firstOrCreate([
            'usuario_id' => auth()->id(),
        ]);

        $item = $carrito->items()
            ->where('planta_id', $planta->id)
            ->first();

        $cantidadNueva = $datos['cantidad'] + ($item?->cantidad ?? 0);

        if ($cantidadNueva > $planta->stock) {
            return back()->withErrors([
                'cantidad' => 'No hay suficientes unidades disponibles.',
            ]);
        }

        if ($item) {
            $item->update([
                'cantidad' => $cantidadNueva,
                'precio_unitario' => $planta->precio,
            ]);
        } else {
            $carrito->items()->create([
                'planta_id' => $planta->id,
                'cantidad' => $datos['cantidad'],
                'precio_unitario' => $planta->precio,
            ]);
        }

        return redirect()->route('carrito.index')
            ->with('mensaje', 'Planta agregada al carrito.');
    }

    public function actualizar(Request $request, ItemCarrito $item): RedirectResponse
    {
        abort_unless(
            $item->carrito->usuario_id === auth()->id(),
            403
        );

        $datos = $request->validate([
            'cantidad' => ['required', 'integer', 'min:1'],
        ]);

        if ($datos['cantidad'] > $item->planta->stock) {
            return back()->withErrors([
                'cantidad' => 'No hay suficientes unidades disponibles.',
            ]);
        }

        $item->update(['cantidad' => $datos['cantidad']]);

        return redirect()->route('carrito.index')
            ->with('mensaje', 'Cantidad actualizada.');
    }

    public function eliminar(ItemCarrito $item): RedirectResponse
    {
        abort_unless(
            $item->carrito->usuario_id === auth()->id(),
            403
        );

        $item->delete();

        return redirect()->route('carrito.index')
            ->with('mensaje', 'Planta retirada del carrito.');
    }
}
