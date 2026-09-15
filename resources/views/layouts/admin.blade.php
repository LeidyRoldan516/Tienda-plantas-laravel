{{-- Autor: Simon Martinez Gomez --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.panel_admin')) — {{ config('app.name', 'Tienda de plantas') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --admin-fondo: #1c2430;
            --admin-panel: #243041;
            --admin-acento: #6db3a8;
            --admin-texto: #e8eef5;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: var(--admin-fondo);
            color: var(--admin-texto);
            min-height: 100vh;
        }

        .admin-shell {
            display: grid;
            grid-template-columns: 14rem 1fr;
            min-height: 100vh;
        }

        .admin-sidebar {
            background: var(--admin-panel);
            padding: 1.5rem 1rem;
            border-right: 1px solid #314055;
        }

        .admin-sidebar a {
            display: block;
            color: var(--admin-texto);
            text-decoration: none;
            padding: 0.55rem 0.75rem;
            margin-bottom: 0.35rem;
            border-radius: 0.35rem;
        }

        .admin-sidebar a:hover {
            background: #2f4056;
        }

        .admin-marca {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--admin-acento);
            margin-bottom: 1.5rem;
            display: block;
        }

        .admin-contenido {
            padding: 2rem;
        }

        .admin-logout {
            margin-top: 2rem;
            background: none;
            border: 1px solid #4a607a;
            color: var(--admin-texto);
            padding: 0.45rem 0.75rem;
            border-radius: 0.35rem;
            cursor: pointer;
            width: 100%;
        }

        @media (max-width: 720px) {
            .admin-shell {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a class="admin-marca" href="{{ route('admin.dashboard') }}">{{ __('messages.panel_admin') }}</a>
            <a href="{{ route('admin.dashboard') }}">{{ __('messages.dashboard') }}</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="admin-logout">{{ __('messages.cerrar_sesion') }}</button>
            </form>
        </aside>
        <main class="admin-contenido">
            @yield('content')
        </main>
    </div>
</body>
</html>
