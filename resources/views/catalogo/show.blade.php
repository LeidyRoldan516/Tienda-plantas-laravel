<x-tienda-layout>
    <x-slot name="title">{{ $planta->nombre }} · El Rincón de las Plantas</x-slot>

    <div class="contenedor py-12">
        <a href="{{ route('catalogo.index') }}" class="nav-enlace inline-flex items-center gap-2">
            ← Volver al catálogo
        </a>

        <div class="mt-8 grid gap-10 lg:grid-cols-2 lg:items-start">
            <div class="overflow-hidden rounded-3xl bg-marca-100">
                @if ($planta->imagen_url)
                    <img src="{{ $planta->imagen_url }}" alt="{{ $planta->nombre }}"
                        class="aspect-square w-full object-cover">
                @else
                    <div class="flex aspect-square w-full items-center justify-center bg-marca-50">
                        <x-application-logo class="h-24 w-auto opacity-40" />
                    </div>
                @endif
            </div>

            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-marca-300">
                    {{ $planta->categoria?->nombre ?? 'Sin categoría' }}
                </p>
                <h1 class="mt-2 text-3xl font-semibold text-marca-900 sm:text-4xl">{{ $planta->nombre }}</h1>
                <p class="mt-4 text-2xl font-semibold text-marca-500">
                    $ {{ number_format($planta->precio, 0, ',', '.') }}
                </p>
                <p class="mt-6 leading-relaxed text-marca-700/80">{{ $planta->descripcion }}</p>
                <p class="mt-4 text-sm text-marca-700/70">
                    Disponibles: <span class="font-semibold text-marca-900">{{ $planta->stock }}</span>
                </p>

                <div class="mt-8">
                    @if ($planta->stock > 0)
                        @auth
                            <form action="{{ route('carrito.agregar') }}" method="POST" class="flex flex-wrap items-end gap-3">
                                @csrf
                                <input type="hidden" name="planta_id" value="{{ $planta->id }}">
                                <div>
                                    <label for="cantidad" class="mb-1 block text-sm font-medium text-marca-800">Cantidad</label>
                                    <input id="cantidad" type="number" name="cantidad"
                                        min="1" max="{{ $planta->stock }}" value="1" required
                                        class="campo w-28">
                                </div>
                                <button type="submit" class="btn-primario">Agregar al carrito</button>
                            </form>
                            <a href="{{ route('carrito.index') }}" class="nav-enlace mt-4 inline-block">Ver mi carrito</a>
                        @else
                            <p class="mb-4 text-sm text-marca-700/80">Inicia sesión para agregar esta planta al carrito.</p>
                            <a href="{{ route('login') }}" class="btn-primario">Iniciar sesión</a>
                        @endauth
                    @else
                        <p class="rounded-2xl bg-marca-50 px-4 py-3 text-sm text-marca-700">
                            Esta planta no está disponible por ahora.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-tienda-layout>
