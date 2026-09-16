<x-tienda-layout>
    <x-slot name="title">Mi carrito · El Rincón de las Plantas</x-slot>

    <div class="contenedor py-12">
        <div class="mb-10">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-marca-300">Compra</p>
            <h1 class="mt-2 text-3xl font-semibold text-marca-900">Mi carrito</h1>
        </div>

        @if ($carrito->items->isEmpty())
            <div class="rounded-3xl border border-dashed border-marca-200 bg-white px-6 py-16 text-center">
                <p class="text-marca-700/70">Tu carrito está vacío.</p>
                <a href="{{ route('catalogo.index') }}" class="btn-primario mt-6">Ver catálogo</a>
            </div>
        @else
            <div class="overflow-hidden rounded-3xl border border-marca-100 bg-white">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-marca-100 bg-marca-50/60 text-marca-700">
                            <tr>
                                <th class="px-5 py-4 font-medium">Planta</th>
                                <th class="px-5 py-4 font-medium">Precio</th>
                                <th class="px-5 py-4 font-medium">Cantidad</th>
                                <th class="px-5 py-4 font-medium">Subtotal</th>
                                <th class="px-5 py-4 font-medium"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($carrito->items as $item)
                                <tr class="border-b border-marca-50 last:border-0">
                                    <td class="px-5 py-4 font-medium text-marca-900">{{ $item->planta->nombre }}</td>
                                    <td class="px-5 py-4 text-marca-700">
                                        $ {{ number_format($item->precio_unitario, 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <form action="{{ route('carrito.actualizar', $item) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="cantidad"
                                                min="1" max="{{ $item->planta->stock }}"
                                                value="{{ $item->cantidad }}" class="campo w-20">
                                            <button type="submit" class="text-sm font-medium text-marca-500 hover:text-marca-700">
                                                Actualizar
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-5 py-4 font-semibold text-marca-900">
                                        $ {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <form action="{{ route('carrito.eliminar', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-medium text-rose-600 hover:text-rose-700">
                                                Retirar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-xl font-semibold text-marca-900">
                    Total: $ {{ number_format($total, 0, ',', '.') }}
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('catalogo.index') }}" class="btn-secundario">Seguir comprando</a>
                    <form action="{{ route('pedidos.crear') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primario">Confirmar pedido</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-tienda-layout>
