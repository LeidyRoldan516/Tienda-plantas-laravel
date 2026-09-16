{{-- Autor: Simon Martinez Gomez --}}
@php
$enlaces = [
    ['ruta' => 'home', 'texto' => 'Inicio', 'activo' => request()->routeIs('home')],
    ['ruta' => 'catalogo.index', 'texto' => 'Catálogo', 'activo' => request()->routeIs('catalogo.*')],
];

if (auth()->check()) {
    $enlaces[] = ['ruta' => 'recomendaciones.index', 'texto' => __('messages.recomendaciones'), 'activo' => request()->routeIs('recomendaciones.*')];
    $enlaces[] = ['ruta' => 'pedidos.index', 'texto' => 'Mis pedidos', 'activo' => request()->routeIs('pedidos.*')];
}
@endphp

<header x-data="{ abierto: false }" class="sticky top-0 z-40 border-b border-marca-100/80 bg-white/90 backdrop-blur-md">
    <nav class="contenedor">
        <div class="flex h-20 items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center">
                <x-application-logo class="h-14 w-auto sm:h-16" />
            </a>

            <div class="hidden items-center gap-8 md:flex">
                @foreach ($enlaces as $enlace)
                    <a href="{{ route($enlace['ruta']) }}"
                        class="{{ $enlace['activo'] ? 'nav-enlace-activo' : 'nav-enlace' }}">
                        {{ $enlace['texto'] }}
                    </a>
                @endforeach
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                @auth
                    <a href="{{ route('carrito.index') }}"
                        class="nav-enlace hidden sm:inline-flex {{ request()->routeIs('carrito.*') ? 'nav-enlace-activo' : '' }}">
                        Carrito
                    </a>
                    <a href="{{ url(auth()->user()->rutaInicio()) }}" class="nav-enlace hidden sm:inline-flex">
                        Mi cuenta
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                        @csrf
                        <button type="submit" class="nav-enlace">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-enlace hidden sm:inline-flex">Entrar</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primario hidden sm:inline-flex !py-2 !px-4">
                            Registrarse
                        </a>
                    @endif
                @endauth

                <button type="button" @click="abierto = ! abierto"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full text-marca-800 transition hover:bg-marca-50 md:hidden"
                    aria-label="Abrir menú">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path x-show="! abierto" stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        <path x-show="abierto" x-cloak stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="abierto" x-cloak class="border-t border-marca-100 py-4 md:hidden">
            <div class="space-y-1">
                @foreach ($enlaces as $enlace)
                    <a href="{{ route($enlace['ruta']) }}"
                        class="block rounded-xl px-3 py-2.5 text-sm font-medium {{ $enlace['activo'] ? 'bg-marca-50 text-marca-700' : 'text-marca-800 hover:bg-marca-50' }}">
                        {{ $enlace['texto'] }}
                    </a>
                @endforeach

                @auth
                    <a href="{{ route('carrito.index') }}" class="block rounded-xl px-3 py-2.5 text-sm font-medium text-marca-800 hover:bg-marca-50">Carrito</a>
                    <a href="{{ url(auth()->user()->rutaInicio()) }}" class="block rounded-xl px-3 py-2.5 text-sm font-medium text-marca-800 hover:bg-marca-50">Mi cuenta</a>
                    <form method="POST" action="{{ route('logout') }}" class="pt-2">
                        @csrf
                        <button type="submit" class="btn-secundario w-full">Cerrar sesión</button>
                    </form>
                @else
                    <div class="flex flex-col gap-2 pt-2">
                        <a href="{{ route('login') }}" class="btn-secundario w-full">Entrar</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-primario w-full">Registrarse</a>
                        @endif
                    </div>
                @endauth
            </div>
        </div>
    </nav>
</header>
