<x-tienda-layout>
    <x-slot name="title">{{ __('messages.mis_pedidos') }} · {{ __('messages.marca_nombre') }}</x-slot>

    <div class="contenedor py-12">
        <div class="mb-10">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-marca-300">{{ __('messages.cuenta') }}</p>
            <h1 class="mt-2 text-3xl font-semibold text-marca-900">{{ __('messages.mis_pedidos') }}</h1>
        </div>

        @if ($pedidos->isEmpty())
            <div class="rounded-3xl border border-dashed border-marca-200 bg-white px-6 py-16 text-center">
                <p class="text-marca-700/70">{{ __('messages.pedidos_vacios') }}</p>
                <a href="{{ route('catalogo.index') }}" class="btn-primario mt-6">{{ __('messages.inicio_ir_catalogo') }}</a>
            </div>
        @else
            <div class="overflow-hidden rounded-3xl border border-marca-100 bg-white">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-marca-100 bg-marca-50/60 text-marca-700">
                            <tr>
                                <th class="px-5 py-4 font-medium">{{ __('messages.numero') }}</th>
                                <th class="px-5 py-4 font-medium">{{ __('messages.fecha') }}</th>
                                <th class="px-5 py-4 font-medium">{{ __('messages.estado') }}</th>
                                <th class="px-5 py-4 font-medium">{{ __('messages.total') }}</th>
                                <th class="px-5 py-4 font-medium"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedidos as $pedido)
                                <tr class="border-b border-marca-50 last:border-0">
                                    <td class="px-5 py-4 font-medium text-marca-900">#{{ $pedido->id }}</td>
                                    <td class="px-5 py-4 text-marca-700">{{ $pedido->fecha }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full bg-marca-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-marca-700">
                                            {{ __('messages.estado_'.$pedido->estado) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 font-semibold text-marca-900">
                                        $ {{ number_format($pedido->total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <a href="{{ route('pedidos.show', $pedido) }}"
                                            class="text-sm font-medium text-marca-500 hover:text-marca-700">
                                            {{ __('messages.ver_detalle') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8">{{ $pedidos->links() }}</div>
        @endif
    </div>
</x-tienda-layout>
