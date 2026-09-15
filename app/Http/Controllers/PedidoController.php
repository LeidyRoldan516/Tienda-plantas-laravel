<?php

namespace App\Http\Controllers;

use App\Models\CarritoCompras;
use App\Models\Pedido;
use App\Models\Planta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PedidoController extends Controller
{
    public function index(): View
    {
        $pedidos = Pedido::where('usuario_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('pedidos.index', compact('pedidos'));
    }

    public function show(Pedido $pedido): View
    {
        abort_unless($pedido->usuario_id === auth()->id(), 403);

        $pedido->load('items.planta');

        return view('pedidos.show', compact('pedido'));
    }

    public function crear(): RedirectResponse
    {
        $pedido = DB::transaction(function () {
            $carrito = CarritoCompras::where('usuario_id', auth()->id())
                ->with('items')
                ->first();

            if (! $carrito || $carrito->items->isEmpty()) {
                throw ValidationException::withMessages([
                    'carrito' => 'No puedes confirmar un carrito vacío.',
                ]);
            }

            $lineas = [];
            $total = 0;

            foreach ($carrito->items as $item) {
                $planta = Planta::whereKey($item->planta_id)
                    ->lockForUpdate()
                    ->first();

                if (! $planta || $planta->stock < $item->cantidad) {
                    throw ValidationException::withMessages([
                        'stock' => 'Una planta del carrito ya no tiene suficiente disponibilidad.',
                    ]);
                }

                $subtotal = $item->cantidad * $planta->precio;
                $total += $subtotal;

                $lineas[] = [
                    'planta' => $planta,
                    'cantidad' => $item->cantidad,
                    'precio_unitario' => $planta->precio,
                    'subtotal' => $subtotal,
                ];
            }

            $pedido = Pedido::create([
                'usuario_id' => auth()->id(),
                'fecha' => now()->toDateString(),
                'estado' => 'pendiente',
                'total' => $total,
            ]);

            foreach ($lineas as $linea) {
                $pedido->items()->create([
                    'planta_id' => $linea['planta']->id,
                    'cantidad' => $linea['cantidad'],
                    'precio_unitario' => $linea['precio_unitario'],
                    'subtotal' => $linea['subtotal'],
                ]);

                $linea['planta']->decrement('stock', $linea['cantidad']);
            }

            $carrito->items()->delete();

            return $pedido;
        });

        return redirect()->route('pedidos.show', $pedido)
            ->with('mensaje', 'Pedido creado correctamente.');
    }

    public function cancelar(Pedido $pedido): RedirectResponse
    {
        abort_unless($pedido->usuario_id === auth()->id(), 403);

        if ($pedido->estado !== 'pendiente') {
            throw ValidationException::withMessages([
                'pedido' => 'Solo puedes cancelar pedidos pendientes.',
            ]);
        }

        $pedido->update(['estado' => 'cancelado']);

        return redirect()->route('pedidos.show', $pedido)
            ->with('mensaje', 'Pedido cancelado.');
    }
}
