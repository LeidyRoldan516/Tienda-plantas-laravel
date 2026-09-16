<?php

namespace Database\Seeders;

use App\Models\CarritoCompras;
use App\Models\ItemCarrito;
use App\Models\ItemPedido;
use App\Models\Pedido;
use App\Models\Planta;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoComprasSeeder extends Seeder
{
    public function run(): void
    {
        $cliente = User::where('email', 'cliente@tienda.com')->first();

        if (! $cliente) {
            return;
        }

        $monstera = Planta::where('nombre', 'Monstera deliciosa')->first();
        $poto = Planta::where('nombre', 'Poto dorado')->first();
        $lavanda = Planta::where('nombre', 'Lavanda')->first();
        $echeveria = Planta::where('nombre', 'Echeveria')->first();

        if (! $monstera || ! $poto || ! $lavanda || ! $echeveria) {
            return;
        }

        // Carrito con 2 ítems para probar checkout.
        $carrito = CarritoCompras::firstOrCreate(['usuario_id' => $cliente->id]);

        ItemCarrito::updateOrCreate(
            [
                'carrito_compras_id' => $carrito->id,
                'planta_id' => $poto->id,
            ],
            [
                'cantidad' => 2,
                'precio_unitario' => $poto->precio,
            ]
        );

        ItemCarrito::updateOrCreate(
            [
                'carrito_compras_id' => $carrito->id,
                'planta_id' => $echeveria->id,
            ],
            [
                'cantidad' => 1,
                'precio_unitario' => $echeveria->precio,
            ]
        );

        // Pedido pendiente.
        $pedidoPendiente = Pedido::updateOrCreate(
            [
                'usuario_id' => $cliente->id,
                'fecha' => now()->subDays(2)->toDateString(),
                'estado' => 'pendiente',
            ],
            [
                'total' => ($monstera->precio * 1) + ($lavanda->precio * 2),
            ]
        );

        ItemPedido::updateOrCreate(
            [
                'pedido_id' => $pedidoPendiente->id,
                'planta_id' => $monstera->id,
            ],
            [
                'cantidad' => 1,
                'precio_unitario' => $monstera->precio,
                'subtotal' => $monstera->precio,
            ]
        );

        ItemPedido::updateOrCreate(
            [
                'pedido_id' => $pedidoPendiente->id,
                'planta_id' => $lavanda->id,
            ],
            [
                'cantidad' => 2,
                'precio_unitario' => $lavanda->precio,
                'subtotal' => $lavanda->precio * 2,
            ]
        );

        // Pedido entregado (historial).
        $pedidoEntregado = Pedido::updateOrCreate(
            [
                'usuario_id' => $cliente->id,
                'fecha' => now()->subDays(12)->toDateString(),
                'estado' => 'entregado',
            ],
            [
                'total' => $poto->precio * 1,
            ]
        );

        ItemPedido::updateOrCreate(
            [
                'pedido_id' => $pedidoEntregado->id,
                'planta_id' => $poto->id,
            ],
            [
                'cantidad' => 1,
                'precio_unitario' => $poto->precio,
                'subtotal' => $poto->precio,
            ]
        );
    }
}
