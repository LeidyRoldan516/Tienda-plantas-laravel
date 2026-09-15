{{-- Autor: Simon Martinez Gomez --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.panel_cliente')) — {{ config('app.name', 'Tienda de plantas') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --cliente-fondo: #f3f7f2;
            --cliente-acento: #2f6b3a;
            --cliente-texto: #1f2a1c;
        }

        body {
            margin: 0;
            font-family: Georgia, 'Times New Roman', serif;
            background: linear-gradient(160deg, #e8f2e4 0%, var(--cliente-fondo) 45%, #fff 100%);
            color: var(--cliente-texto);
            min-height: 100vh;
        }

        .cliente-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            background: rgba(255, 255, 255, 0.85);
            border-bottom: 2px solid #c5dcc8;
        }

        .cliente-nav a {
            color: var(--cliente-acento);
            text-decoration: none;
            margin-left: 1rem;
            font-size: 0.95rem;
        }

        .cliente-marca {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--cliente-acento);
            margin-left: 0;
        }

        .cliente-contenido {
            max-width: 56rem;
            margin: 2rem auto;
            padding: 0 1.5rem 3rem;
        }
    </style>
</head>
<body>
    <nav class="cliente-nav">
        <a class="cliente-marca" href="{{ route('cliente.dashboard') }}">{{ __('messages.tienda_plantas') }}</a>
        <div>
            <a href="{{ route('cliente.dashboard') }}">{{ __('messages.mi_cuenta') }}</a>
            <a href="{{ route('profile.edit') }}">{{ __('messages.perfil') }}</a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" style="background:none;border:none;color:var(--cliente-acento);cursor:pointer;font:inherit;margin-left:1rem">
                    {{ __('messages.cerrar_sesion') }}
                </button>
            </form>
        </div>
    </nav>

    <main class="cliente-contenido">
        @yield('content')
    </main>
</body>
</html>
