<footer class="mt-auto border-t border-marca-100 bg-white">
    <div class="contenedor flex flex-col items-center gap-4 py-10 text-center sm:flex-row sm:justify-between sm:text-left">
        <div class="flex flex-col items-center gap-3 sm:items-start">
            <x-application-logo class="h-12 w-auto" />
            <p class="max-w-xs text-sm text-marca-700/70">
                Plantas seleccionadas para llenar tu espacio de vida y calma.
            </p>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-6 text-sm font-medium text-marca-700">
            <a href="{{ route('home') }}" class="transition hover:text-marca-500">Inicio</a>
            <a href="{{ route('catalogo.index') }}" class="transition hover:text-marca-500">Catálogo</a>
            @auth
                <a href="{{ route('carrito.index') }}" class="transition hover:text-marca-500">Carrito</a>
            @else
                <a href="{{ route('login') }}" class="transition hover:text-marca-500">Entrar</a>
            @endauth
        </div>
    </div>

    <div class="border-t border-marca-50 py-4 text-center text-xs text-marca-700/50">
        © {{ date('Y') }} El Rincón de las Plantas
    </div>
</footer>
