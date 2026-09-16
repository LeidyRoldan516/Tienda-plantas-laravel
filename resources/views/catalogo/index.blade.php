<x-tienda-layout>
    <x-slot name="title">Catálogo · El Rincón de las Plantas</x-slot>

    <div class="contenedor py-12">
        <div class="mb-10 max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-marca-300">Catálogo</p>
            <h1 class="mt-2 text-3xl font-semibold text-marca-900 sm:text-4xl">Nuestras plantas</h1>
            <p class="mt-2 text-marca-700/70">Encuentra la planta perfecta para tu espacio.</p>
        </div>

        <form action="{{ route('catalogo.index') }}" method="GET"
            class="mb-10 flex flex-col gap-3 rounded-3xl border border-marca-100 bg-white p-4 sm:flex-row sm:items-end sm:p-5">
            <div class="flex-1">
                <label for="buscar" class="mb-1 block text-sm font-medium text-marca-800">Buscar</label>
                <input type="search" name="buscar" id="buscar" value="{{ $busqueda }}"
                    placeholder="Nombre o palabra clave" class="campo">
            </div>

            <div class="sm:w-56">
                <label for="categoria" class="mb-1 block text-sm font-medium text-marca-800">Categoría</label>
                <select name="categoria" id="categoria" class="campo">
                    <option value="">Todas</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}" @selected((string) $categoriaId === (string) $categoria->id)>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn-primario">Buscar</button>
                <a href="{{ route('catalogo.index') }}" class="btn-secundario">Limpiar</a>
            </div>
        </form>

        @if ($plantas->isEmpty())
            <div class="rounded-3xl border border-dashed border-marca-200 bg-white px-6 py-16 text-center">
                <p class="text-marca-700/70">No encontramos plantas con esos criterios.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($plantas as $planta)
                    <article class="group">
                        <a href="{{ route('catalogo.show', $planta) }}" class="block">
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
                        </a>

                        <div class="mt-4">
                            <p class="text-xs font-medium uppercase tracking-wide text-marca-400">
                                {{ $planta->categoria?->nombre ?? 'Sin categoría' }}
                            </p>
                            <h2 class="mt-1 text-lg font-semibold text-marca-900">
                                <a href="{{ route('catalogo.show', $planta) }}" class="transition hover:text-marca-500">
                                    {{ $planta->nombre }}
                                </a>
                            </h2>
                            <div class="mt-2 flex items-center justify-between gap-3">
                                <span class="font-semibold text-marca-800">
                                    $ {{ number_format($planta->precio, 0, ',', '.') }}
                                </span>
                                <a href="{{ route('catalogo.show', $planta) }}"
                                    class="text-sm font-medium text-marca-500 transition hover:text-marca-700">
                                    Ver detalle
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $plantas->links() }}
            </div>
        @endif
    </div>
</x-tienda-layout>
