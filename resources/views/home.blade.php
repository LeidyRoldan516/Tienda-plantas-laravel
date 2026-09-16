<x-tienda-layout>
    <x-slot name="title">El Rincón de las Plantas</x-slot>

    {{-- Hero --}}
    <section class="relative isolate min-h-[88vh] overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <img
                src="{{ asset('images/landpage.gif') }}"
                alt=""
                class="h-full w-full object-cover object-center brightness-50 contrast-110"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-lienzo/95 via-lienzo/65 to-marca-900/45"></div>
            <div class="absolute inset-0 bg-marca-900/45"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-lienzo via-transparent to-transparent"></div>
        </div>

        <div class="contenedor flex min-h-[88vh] flex-col justify-center py-16">
            <div class="max-w-xl animate-[fadeUp_0.8s_ease-out]">
                <x-application-logo class="mb-8 h-24 w-auto sm:h-28" />

                <h1 class="text-4xl font-semibold tracking-tight text-marca-900 sm:text-5xl">
                    Un rincón vivo para tu hogar
                </h1>

                <p class="mt-4 max-w-md text-base leading-relaxed text-marca-700/80 sm:text-lg">
                    Descubre plantas cuidadosamente seleccionadas para acompañar tu día a día.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('catalogo.index') }}" class="btn-primario">Ver catálogo</a>
                    @guest
                        <a href="{{ route('register') }}" class="btn-secundario">Crear cuenta</a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    {{-- Destacadas --}}
    @if ($destacadas->isNotEmpty())
        <section class="contenedor py-20">
            <div class="mb-10 max-w-xl">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-marca-300">Selección</p>
                <h2 class="mt-2 text-3xl font-semibold text-marca-900">Plantas destacadas</h2>
                <p class="mt-2 text-marca-700/70">Algunas de las favoritas de nuestra tienda.</p>
            </div>

            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($destacadas as $planta)
                    <a href="{{ route('catalogo.show', $planta) }}" class="group block">
                        <div class="aspect-[4/5] overflow-hidden rounded-3xl bg-marca-100">
                            @if ($planta->imagen_url)
                                <img src="{{ $planta->imagen_url }}" alt="{{ $planta->nombre }}"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-marca-50">
                                    <x-application-logo class="h-16 w-auto opacity-40" />
                                </div>
                            @endif
                        </div>
                        <div class="mt-4">
                            <p class="text-xs font-medium uppercase tracking-wide text-marca-400">
                                {{ $planta->categoria?->nombre ?? 'Planta' }}
                            </p>
                            <h3 class="mt-1 text-lg font-semibold text-marca-900 group-hover:text-marca-500">
                                {{ $planta->nombre }}
                            </h3>
                            <p class="mt-1 text-sm font-medium text-marca-700">
                                $ {{ number_format($planta->precio, 0, ',', '.') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('catalogo.index') }}" class="btn-secundario">Ver todas las plantas</a>
            </div>
        </section>
    @endif

    {{-- Cierre --}}
    <section class="border-t border-marca-100 bg-white">
        <div class="contenedor py-20 text-center">
            <h2 class="text-3xl font-semibold text-marca-900">Cultiva tu propio rincón</h2>
            <p class="mx-auto mt-3 max-w-lg text-marca-700/70">
                Explora el catálogo y encuentra la planta que transforma tu espacio.
            </p>
            <a href="{{ route('catalogo.index') }}" class="btn-primario mt-8">Ir al catálogo</a>
        </div>
    </section>

    <style>
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-tienda-layout>
