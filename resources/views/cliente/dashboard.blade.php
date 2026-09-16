{{-- Autor: Simon Martinez Gomez --}}
<x-tienda-layout>
    <x-slot name="title">{{ __('messages.dashboard_cliente') }}</x-slot>

    <div class="contenedor py-12">
        <div class="mb-10 max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-marca-300">Cuenta</p>
            <h1 class="mt-2 text-3xl font-semibold text-marca-900">{{ __('messages.bienvenida_cliente') }}</h1>
            <p class="mt-2 text-marca-700/70">{{ __('messages.dashboard_cliente_descripcion') }}</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <a href="{{ route('catalogo.index') }}" class="rounded-3xl border border-marca-100 bg-white p-6 transition hover:border-marca-300">
                <h2 class="font-semibold text-marca-900">Catálogo</h2>
                <p class="mt-1 text-sm text-marca-700/70">Explorar plantas disponibles</p>
            </a>
            <a href="{{ route('carrito.index') }}" class="rounded-3xl border border-marca-100 bg-white p-6 transition hover:border-marca-300">
                <h2 class="font-semibold text-marca-900">Carrito</h2>
                <p class="mt-1 text-sm text-marca-700/70">Revisar tu compra</p>
            </a>
            <a href="{{ route('pedidos.index') }}" class="rounded-3xl border border-marca-100 bg-white p-6 transition hover:border-marca-300">
                <h2 class="font-semibold text-marca-900">Pedidos</h2>
                <p class="mt-1 text-sm text-marca-700/70">Ver historial de pedidos</p>
            </a>
        </div>
    </div>
</x-tienda-layout>
