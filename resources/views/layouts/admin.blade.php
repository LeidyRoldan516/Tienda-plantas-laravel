{{-- Autor: Simon Martinez Gomez --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.panel_admin')) — El Rincón de las Plantas</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --fondo: #F4F8F6;
            --panel: #ffffff;
            --borde: #D7E8E0;
            --texto: #0B2421;
            --muted: #5F7A72;
            --acento: #239B6D;
            --acento-suave: #E4F6EE;
            --menta: #82D1B1;
            --peligro: #C44646;
            --peligro-suave: #FCECEC;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Outfit, system-ui, sans-serif;
            background:
                radial-gradient(circle at top right, rgba(130, 209, 177, 0.22), transparent 34%),
                radial-gradient(circle at 10% 20%, rgba(35, 155, 109, 0.08), transparent 28%),
                var(--fondo);
            color: var(--texto);
            min-height: 100vh;
        }

        .admin-shell {
            display: grid;
            grid-template-columns: 16.5rem 1fr;
            min-height: 100vh;
        }

        .admin-sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            padding: 1.5rem 1.1rem;
            background: rgba(255, 255, 255, 0.88);
            border-right: 1px solid var(--borde);
            backdrop-filter: blur(10px);
        }

        .admin-marca {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--texto);
            padding: 0.35rem 0.5rem;
        }

        .admin-marca img {
            height: 3.2rem;
            width: auto;
            object-fit: contain;
        }

        .admin-marca-texto {
            display: flex;
            flex-direction: column;
            gap: 0.1rem;
        }

        .admin-marca-texto strong {
            font-size: 0.95rem;
            line-height: 1.2;
        }

        .admin-marca-texto span {
            font-size: 0.72rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--acento);
            font-weight: 600;
        }

        .admin-nav {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            flex: 1;
        }

        .admin-nav a {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.75rem 0.9rem;
            border-radius: 0.9rem;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: 0.15s ease;
        }

        .admin-nav a:hover {
            background: var(--acento-suave);
            color: var(--texto);
        }

        .admin-nav a.activo {
            background: var(--acento);
            color: white;
            box-shadow: 0 10px 24px -14px rgba(35, 155, 109, 0.9);
        }

        .admin-nav-dot {
            width: 0.45rem;
            height: 0.45rem;
            border-radius: 999px;
            background: currentColor;
            opacity: 0.7;
        }

        .admin-sidebar-foot {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            padding-top: 0.5rem;
            border-top: 1px solid var(--borde);
        }

        .admin-logout {
            width: 100%;
            border: 1px solid var(--borde);
            background: white;
            color: var(--muted);
            border-radius: 0.9rem;
            padding: 0.7rem 0.9rem;
            font: inherit;
            font-weight: 600;
            cursor: pointer;
        }

        .admin-logout:hover {
            color: var(--peligro);
            border-color: #f0bcbc;
            background: var(--peligro-suave);
        }

        .admin-contenido {
            padding: 2rem;
            max-width: 1100px;
        }

        .admin-cabecera {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .admin-cabecera h1 {
            margin: 0 0 0.3rem;
            font-size: 1.9rem;
            letter-spacing: -0.02em;
        }

        .admin-cabecera p,
        .admin-subtitulo,
        .admin-aviso {
            margin: 0;
            color: var(--muted);
        }

        .admin-eyebrow {
            margin: 0 0 0.35rem;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--menta);
        }

        .admin-resumen {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .admin-stat {
            min-width: 8.5rem;
            padding: 1rem 1.15rem;
            border-radius: 1.1rem;
            background: var(--panel);
            border: 1px solid var(--borde);
            box-shadow: 0 12px 30px -24px rgba(11, 36, 33, 0.35);
        }

        .admin-stat-valor {
            display: block;
            font-size: 1.7rem;
            font-weight: 700;
            color: var(--acento);
            line-height: 1;
        }

        .admin-stat-etiqueta {
            display: block;
            margin-top: 0.35rem;
            font-size: 0.8rem;
            color: var(--muted);
        }

        .admin-panel {
            background: var(--panel);
            border: 1px solid var(--borde);
            border-radius: 1.25rem;
            padding: 1.4rem 1.5rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 18px 40px -34px rgba(11, 36, 33, 0.45);
        }

        .admin-panel h2 {
            margin: 0 0 0.35rem;
            font-size: 1.15rem;
        }

        .admin-panel-head {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 0.75rem;
            align-items: center;
            margin-bottom: 1rem;
        }

        .admin-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1.15rem;
        }

        .admin-campo {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .admin-campo-completo { grid-column: 1 / -1; }

        .admin-campo input,
        .admin-campo select,
        .admin-campo textarea {
            width: 100%;
            border: 1px solid var(--borde);
            background: #FBFDFA;
            color: var(--texto);
            border-radius: 0.85rem;
            padding: 0.7rem 0.85rem;
            font: inherit;
            font-weight: 400;
            outline: none;
            transition: 0.15s ease;
        }

        .admin-campo input:focus,
        .admin-campo select:focus,
        .admin-campo textarea:focus {
            border-color: var(--acento);
            box-shadow: 0 0 0 3px rgba(35, 155, 109, 0.15);
            background: white;
        }

        .admin-acciones-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
            align-items: center;
        }

        .admin-btn,
        .admin-btn-secundario,
        .admin-btn-peligro {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            border-radius: 999px;
            padding: 0.65rem 1.15rem;
            font: inherit;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: 0.15s ease;
        }

        .admin-btn {
            background: var(--acento);
            color: white;
        }

        .admin-btn:hover { background: #1C7F59; }

        .admin-btn-secundario {
            background: white;
            color: var(--texto);
            border: 1px solid var(--borde);
        }

        .admin-btn-secundario:hover {
            background: var(--acento-suave);
            border-color: var(--menta);
        }

        .admin-btn-peligro {
            background: transparent;
            color: var(--peligro);
            border: 1px solid #f0bcbc;
            padding: 0.4rem 0.8rem;
            font-size: 0.82rem;
        }

        .admin-btn-peligro:hover {
            background: var(--peligro-suave);
        }

        .admin-enlace {
            color: var(--acento);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .admin-enlace:hover { text-decoration: underline; }

        .admin-alerta {
            border-radius: 1rem;
            padding: 0.9rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.92rem;
        }

        .admin-alerta-ok {
            background: var(--acento-suave);
            border: 1px solid #b8e4d1;
            color: #166449;
        }

        .admin-alerta-error {
            background: var(--peligro-suave);
            border: 1px solid #f0bcbc;
            color: var(--peligro);
        }

        .admin-alerta ul {
            margin: 0;
            padding-left: 1.1rem;
        }

        .admin-tabla-wrap { overflow-x: auto; }

        .admin-tabla {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.92rem;
        }

        .admin-tabla th,
        .admin-tabla td {
            text-align: left;
            padding: 0.95rem 0.7rem;
            border-bottom: 1px solid var(--borde);
            vertical-align: middle;
        }

        .admin-tabla th {
            color: var(--muted);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .admin-tabla tr:last-child td { border-bottom: none; }

        .admin-planta-cell {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .admin-thumb {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 0.8rem;
            object-fit: cover;
            background: var(--acento-suave);
            flex-shrink: 0;
        }

        .admin-thumb-empty {
            display: grid;
            place-items: center;
            color: var(--menta);
            font-size: 0.7rem;
            font-weight: 700;
        }

        .admin-stock {
            display: inline-flex;
            min-width: 2.5rem;
            justify-content: center;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .admin-stock.ok { background: var(--acento-suave); color: #166449; }
        .admin-stock.bajo { background: #FFF4D6; color: #9A6B00; }
        .admin-stock.agotado { background: var(--peligro-suave); color: var(--peligro); }

        .admin-badge {
            display: inline-flex;
            padding: 0.2rem 0.65rem;
            border-radius: 999px;
            background: var(--acento-suave);
            color: #166449;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .admin-acciones {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .admin-acciones form { margin: 0; }

        .admin-vacio {
            text-align: center;
            padding: 2.5rem 1rem;
            color: var(--muted);
        }

        .admin-detalle-grid {
            display: grid;
            grid-template-columns: 220px 1fr;
            gap: 1.5rem;
            align-items: start;
        }

        .admin-detalle-img {
            width: 100%;
            aspect-ratio: 1;
            object-fit: cover;
            border-radius: 1.1rem;
            background: var(--acento-suave);
        }

        .admin-meta {
            display: grid;
            gap: 0.65rem;
            margin: 1rem 0;
        }

        .admin-meta div {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .admin-meta strong { min-width: 7rem; color: var(--muted); font-weight: 500; }

        .admin-paginacion {
            margin-top: 1.25rem;
        }

        @media (max-width: 900px) {
            .admin-shell { grid-template-columns: 1fr; }
            .admin-sidebar {
                position: relative;
                height: auto;
                border-right: none;
                border-bottom: 1px solid var(--borde);
            }
            .admin-form-grid,
            .admin-detalle-grid { grid-template-columns: 1fr; }
            .admin-contenido { padding: 1.25rem; }
        }
    </style>
</head>

<body>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a class="admin-marca" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('images/logo.jpg') }}" alt="El Rincón de las Plantas">
                <span class="admin-marca-texto">
                    <strong>Panel admin</strong>
                    <span>Gestión</span>
                </span>
            </a>

            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'activo' : '' }}">
                    <span class="admin-nav-dot"></span> Dashboard
                </a>
                <a href="{{ route('admin.plantas.index') }}"
                    class="{{ request()->routeIs('admin.plantas.*') ? 'activo' : '' }}">
                    <span class="admin-nav-dot"></span> Plantas
                </a>
                <a href="{{ route('admin.categorias.index') }}"
                    class="{{ request()->routeIs('admin.categorias.*') ? 'activo' : '' }}">
                    <span class="admin-nav-dot"></span> Categorías
                </a>
                <a href="{{ route('home') }}">
                    <span class="admin-nav-dot"></span> Ver tienda
                </a>
            </nav>

            <div class="admin-sidebar-foot">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="admin-logout">Cerrar sesión</button>
                </form>
            </div>
        </aside>

        <main class="admin-contenido">
            @if (session('success') || session('mensaje'))
                <div class="admin-alerta admin-alerta-ok">
                    {{ session('success') ?? session('mensaje') }}
                </div>
            @endif

            @if (session('error'))
                <div class="admin-alerta admin-alerta-error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="admin-alerta admin-alerta-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>

</html>
