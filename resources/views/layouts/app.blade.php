<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo') · Tienda de plantas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --verde-oscuro: #1f3d2b;
            --verde: #2f7a4d;
            --verde-claro: #7ab98a;
            --verde-suave: #e8f3ec;
            --crema: #faf8f2;
            --texto: #223027;
            --gris: #6b7a70;
            --borde: #e0e6e1;
            --error: #b23a3a;
            --error-suave: #fde8e8;
            --exito: #2f7a4d;
            --exito-suave: #e6f4ea;
            --sombra: 0 4px 14px rgba(31, 61, 43, 0.08);
            --sombra-hover: 0 8px 24px rgba(31, 61, 43, 0.14);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--crema);
            color: var(--texto);
            line-height: 1.55;
        }

        h1, h2, h3 {
            font-family: 'Playfair Display', Georgia, serif;
            color: var(--verde-oscuro);
            margin: 0 0 12px;
            line-height: 1.2;
        }
        h1 { font-size: 2.2rem; }
        h2 { font-size: 1.5rem; margin-top: 28px; }
        h3 { font-size: 1.2rem; font-family: 'Inter', sans-serif; font-weight: 600; }

        a { color: var(--verde); text-decoration: none; transition: color .15s; }
        a:hover { color: var(--verde-oscuro); text-decoration: underline; }

        .site-header {
            background: var(--verde-oscuro);
            color: #fff;
            padding: 16px 0;
            box-shadow: var(--sombra);
        }
        .site-header .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }
        .brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .brand:hover { color: var(--verde-claro); text-decoration: none; }
        .brand-leaf {
            display: inline-block;
            width: 28px;
            height: 28px;
            background: var(--verde-claro);
            border-radius: 50% 10% 50% 10%;
            transform: rotate(45deg);
        }
        .nav {
            display: flex;
            gap: 6px;
            align-items: center;
            flex-wrap: wrap;
        }
        .nav a {
            color: #dbe7de;
            padding: 8px 14px;
            border-radius: 999px;
            font-weight: 500;
            font-size: 0.95rem;
        }
        .nav a:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
            text-decoration: none;
        }
        .nav a.cart {
            background: var(--verde-claro);
            color: var(--verde-oscuro);
            font-weight: 600;
        }
        .nav a.cart:hover { background: #fff; }

        .container {
            max-width: 1150px;
            margin: 0 auto;
            padding: 0 24px;
        }

        main { padding: 40px 0 60px; min-height: calc(100vh - 200px); }

        .site-footer {
            background: var(--verde-oscuro);
            color: #cdd6d0;
            padding: 24px 0;
            text-align: center;
            font-size: 0.9rem;
        }

        .alerta {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }
        .alerta-exito {
            background: var(--exito-suave);
            border-color: var(--exito);
            color: var(--verde-oscuro);
        }
        .alerta-error {
            background: var(--error-suave);
            border-color: var(--error);
            color: var(--error);
        }
        .alerta ul { margin: 6px 0 0; padding-left: 20px; }

        .card {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: var(--sombra);
            border: 1px solid var(--borde);
        }

        .grid-plantas {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .planta-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: var(--sombra);
            border: 1px solid var(--borde);
            display: flex;
            flex-direction: column;
            transition: transform .18s, box-shadow .18s;
        }
        .planta-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--sombra-hover);
        }
        .planta-imagen {
            aspect-ratio: 4/3;
            background: var(--verde-suave);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            color: var(--verde);
            overflow: hidden;
        }
        .planta-imagen img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .planta-cuerpo {
            padding: 16px 18px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .planta-cuerpo h3 { margin: 0 0 6px; }
        .planta-cuerpo h3 a { color: var(--texto); }
        .planta-cuerpo h3 a:hover { color: var(--verde); text-decoration: none; }
        .planta-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 8px 0 12px;
            font-size: 0.9rem;
        }
        .badge {
            display: inline-block;
            background: var(--verde-suave);
            color: var(--verde-oscuro);
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 500;
        }
        .badge-warn {
            background: #fdeed0;
            color: #8a5a00;
        }
        .precio {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--verde);
            margin: 6px 0 12px;
        }

        .btn {
            display: inline-block;
            background: var(--verde);
            color: #fff;
            padding: 10px 18px;
            border-radius: 8px;
            border: none;
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background .15s, transform .1s;
        }
        .btn:hover { background: var(--verde-oscuro); color: #fff; text-decoration: none; }
        .btn:active { transform: translateY(1px); }
        .btn-secundario {
            background: #fff;
            color: var(--verde-oscuro);
            border: 1px solid var(--borde);
        }
        .btn-secundario:hover { background: var(--verde-suave); color: var(--verde-oscuro); }
        .btn-peligro {
            background: #fff;
            color: var(--error);
            border: 1px solid #f0c8c8;
        }
        .btn-peligro:hover { background: var(--error-suave); color: var(--error); }
        .btn-bloque { display: block; width: 100%; text-align: center; }

        .form-inline {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-top: auto;
        }
        .form-inline input[type="number"] {
            width: 70px;
        }

        form.stack p { margin: 0 0 16px; }
        label {
            display: block;
            font-weight: 500;
            margin-bottom: 6px;
            font-size: 0.92rem;
            color: var(--verde-oscuro);
        }
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--borde);
            border-radius: 8px;
            font-family: inherit;
            font-size: 1rem;
            background: #fff;
            color: var(--texto);
            transition: border-color .15s, box-shadow .15s;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--verde);
            box-shadow: 0 0 0 3px rgba(47, 122, 77, 0.15);
        }
        textarea { resize: vertical; min-height: 100px; }

        .hero {
            background: linear-gradient(135deg, var(--verde-oscuro), var(--verde));
            color: #fff;
            padding: 50px 40px;
            border-radius: 18px;
            margin-bottom: 36px;
            box-shadow: var(--sombra);
        }
        .hero h1 { color: #fff; font-size: 2.6rem; margin-bottom: 10px; }
        .hero p { font-size: 1.1rem; opacity: 0.92; max-width: 600px; margin: 0 0 24px; }
        .hero .btn { background: #fff; color: var(--verde-oscuro); }
        .hero .btn:hover { background: var(--verde-suave); }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin: 20px 0 30px;
        }
        .stat {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid var(--borde);
            box-shadow: var(--sombra);
        }
        .stat .num {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            color: var(--verde);
            font-weight: 700;
            display: block;
        }
        .stat .lbl {
            color: var(--gris);
            font-size: 0.9rem;
        }

        .tabla {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--sombra);
        }
        .tabla th, .tabla td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid var(--borde);
        }
        .tabla th {
            background: var(--verde-suave);
            color: var(--verde-oscuro);
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .tabla tr:last-child td { border-bottom: none; }
        .tabla tr:hover td { background: #fafcfa; }

        .vacio {
            text-align: center;
            padding: 48px 20px;
            color: var(--gris);
            background: #fff;
            border-radius: 12px;
            border: 2px dashed var(--borde);
        }
        .vacio-emoji {
            font-size: 3rem;
            display: block;
            margin-bottom: 12px;
        }

        .detalle-planta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            background: #fff;
            border-radius: 14px;
            padding: 28px;
            box-shadow: var(--sombra);
            border: 1px solid var(--borde);
        }
        .detalle-planta .imagen {
            aspect-ratio: 1;
            background: var(--verde-suave);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 6rem;
            color: var(--verde);
            overflow: hidden;
        }
        .detalle-planta .imagen img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }
        @media (max-width: 720px) {
            .detalle-planta { grid-template-columns: 1fr; }
            .hero { padding: 32px 24px; }
            .hero h1 { font-size: 2rem; }
        }

        .item-carrito {
            display: grid;
            grid-template-columns: 80px 1fr auto;
            gap: 16px;
            align-items: center;
            background: #fff;
            padding: 16px;
            border-radius: 12px;
            border: 1px solid var(--borde);
            box-shadow: var(--sombra);
            margin-bottom: 12px;
        }
        .item-carrito .mini-img {
            width: 80px;
            height: 80px;
            background: var(--verde-suave);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--verde);
            overflow: hidden;
        }
        .item-carrito .mini-img img { width: 100%; height: 100%; object-fit: cover; border-radius: 10px; }
        .item-carrito h3 { margin: 0 0 4px; font-size: 1.05rem; }
        .item-carrito .info { color: var(--gris); font-size: 0.9rem; }
        .item-carrito .subtotal { font-weight: 700; color: var(--verde); font-size: 1.1rem; }
        .resumen-carrito {
            background: var(--verde-oscuro);
            color: #fff;
            padding: 20px 24px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            gap: 16px;
            flex-wrap: wrap;
        }
        .resumen-carrito .total-label { font-size: 0.9rem; opacity: 0.8; }
        .resumen-carrito .total-monto { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 700; }

        .filtro-bar {
            background: #fff;
            padding: 16px 20px;
            border-radius: 12px;
            box-shadow: var(--sombra);
            border: 1px solid var(--borde);
            display: flex;
            gap: 12px;
            align-items: end;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }
        .filtro-bar label { margin-bottom: 6px; }
        .filtro-bar > div { flex: 1; min-width: 200px; }

        .acciones-admin {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
            margin: 20px 0 30px;
        }
        .accion-admin {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid var(--borde);
            box-shadow: var(--sombra);
            text-align: center;
            transition: transform .15s, box-shadow .15s;
        }
        .accion-admin:hover {
            transform: translateY(-2px);
            box-shadow: var(--sombra-hover);
            text-decoration: none;
        }
        .accion-admin .emoji { font-size: 2rem; display: block; margin-bottom: 8px; }
        .accion-admin .txt { color: var(--verde-oscuro); font-weight: 600; }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="container">
            <a href="{{ route('home') }}" class="brand">
                <span class="brand-leaf"></span>
                Vivero Verde
            </a>
            <nav class="nav">
                <a href="{{ route('home') }}">Inicio</a>
                <a href="{{ route('plantas.index') }}">Catálogo</a>
                <a href="{{ route('admin.index') }}">Administrador</a>
                <a href="{{ route('carrito.index') }}" class="cart">Carrito</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            @if(session('success'))
                <div class="alerta alerta-exito">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alerta alerta-error">
                    <strong>Revisa los siguientes campos:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('contenido')
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            Vivero Verde · Cultivamos vida en cada rincón
        </div>
    </footer>
</body>
</html>
