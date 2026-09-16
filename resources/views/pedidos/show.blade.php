<x-tienda-layout>
    <x-slot name="title">Pedido #{{ $pedido->id }} · El Rincón de las Plantas</x-slot>

    <div class="contenedor py-12">
        <a href="{{ route('pedidos.index') }}" class="nav-enlace inline-flex items-center gap-2">
            ← Mis pedidos
        </a>

        <div class="mt-8 mb-10">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-marca-300">Pedido</p>
            <h1 class="mt-2 text-3xl font-semibold text-marca-900">Pedido #{{ $pedido->id }}</h1>
            <div class="mt-4 flex flex-wrap gap-4 text-sm text-marca-700/80">
                <p>Fecha: <span class="font-medium text-marca-900">{{ $pedido->fecha }}</span></p>
                <p>
                    Estado:
                    <span class="inline-flex rounded-full bg-marca-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-marca-700">
                        {{ $pedido->estado }}
                    </span>
                </p>
            </div>
        </div>

        <div class="overflow-hidden rounded-3xl border border-marca-100 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-marca-100 bg-marca-50/60 text-marca-700">
                        <tr>
                            <th class="px-5 py-4 font-medium">Planta</th>
                            <th class="px-5 py-4 font-medium">Cantidad</th>
                            <th class="px-5 py-4 font-medium">Precio unitario</th>
                            <th class="px-5 py-4 font-medium">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedido->items as $item)
                            <tr class="border-b border-marca-50 last:border-0">
                                <td class="px-5 py-4 font-medium text-marca-900">
                                    {{ $item->planta?->nombre ?? 'Planta no disponible' }}
                                </td>
                                <td class="px-5 py-4 text-marca-700">{{ $item->cantidad }}</td>
                                <td class="px-5 py-4 text-marca-700">
                                    $ {{ number_format($item->precio_unitario, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4 font-semibold text-marca-900">
                                    $ {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-xl font-semibold text-marca-900">
                Total: $ {{ number_format($pedido->total, 0, ',', '.') }}
            </p>

            @if ($pedido->estado === 'pendiente')
                <form action="{{ route('pedidos.cancelar', $pedido) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-secundario !border-rose-200 !text-rose-700 hover:!bg-rose-50">
                        Cancelar pedido
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-tienda-layout>
