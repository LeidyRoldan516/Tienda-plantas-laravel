{{-- Autor: Simon Martinez Gomez --}}
<x-tienda-layout>
    <x-slot name="title">{{ __('messages.recomendacion_detalle') }} · El Rincón de las Plantas</x-slot>

    <div class="contenedor py-12">
        <a href="{{ route('recomendaciones.index') }}" class="nav-enlace inline-flex items-center gap-2">
            ← {{ __('messages.recomendaciones_volver') }}
        </a>

        <div class="mt-8 max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-marca-300">
                {{ __('messages.recomendaciones_etiqueta') }}
            </p>
            <h1 class="mt-2 text-3xl font-semibold text-marca-900">
                {{ __('messages.recomendacion_detalle') }}
            </h1>
            <p class="mt-2 text-sm text-marca-700/60">
                {{ $recomendacion->created_at->format('d/m/Y H:i') }}
            </p>
            <p class="mt-6 leading-relaxed text-marca-700/80">
                {{ $recomendacion->explicacion }}
            </p>
        </div>

        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($recomendacion->plantas as $planta)
                <article class="overflow-hidden rounded-3xl border border-marca-100 bg-white">
                    <a href="{{ route('catalogo.show', $planta) }}" class="block overflow-hidden bg-marca-100">
                        @if ($planta->imagen_url)
                            <img src="{{ $planta->imagen_url }}" alt="{{ $planta->nombre }}"
                                class="aspect-[4/3] w-full object-cover transition duration-500 hover:scale-[1.03]">
                        @else
                            <div class="flex aspect-[4/3] w-full items-center justify-center bg-marca-50">
                                <x-application-logo class="h-16 w-auto opacity-40" />
                            </div>
                        @endif
                    </a>
                    <div class="p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-marca-300">
                            {{ $planta->categoria?->nombre ?? __('messages.sin_categoria') }}
                        </p>
                        <h2 class="mt-2 text-lg font-semibold text-marca-900">
                            <a href="{{ route('catalogo.show', $planta) }}" class="hover:text-marca-500">
                                {{ $planta->nombre }}
                            </a>
                        </h2>
                        <p class="mt-2 text-sm text-marca-700/80">
                            {{ $planta->pivot->motivo }}
                        </p>
                        <p class="mt-4 font-semibold text-marca-500">
                            $ {{ number_format($planta->precio, 0, ',', '.') }}
                        </p>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-10 flex flex-wrap gap-3">
            <a href="{{ route('recomendaciones.index') }}" class="btn-secundario">
                {{ __('messages.recomendaciones_ajustar') }}
            </a>
            <a href="{{ route('catalogo.index') }}" class="btn-primario">
                {{ __('messages.ver_catalogo') }}
            </a>
        </div>
    </div>
</x-tienda-layout>
