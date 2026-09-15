<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catálogo de plantas</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1100px; margin: 30px auto; padding: 0 16px; }
        form { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 24px; }
        input, select, button { padding: 10px; }
        .plantas { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; }
        .planta { border: 1px solid #ddd; border-radius: 8px; padding: 16px; }
        img { width: 100%; height: 180px; object-fit: cover; }
    </style>
</head>
<body>
    <h1>Catálogo de plantas</h1>

    <form action="{{ route('catalogo.index') }}" method="GET">
        <input type="search" name="buscar" value="{{ $busqueda }}"
               placeholder="Buscar por nombre o palabra clave">

        <select name="categoria">
            <option value="">Todas las categorías</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}"
                    @selected((string) $categoriaId === (string) $categoria->id)>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
        </select>

        <button type="submit">Buscar</button>
        <a href="{{ route('catalogo.index') }}">Limpiar filtros</a>
    </form>

    @if ($plantas->isEmpty())
        <p>No encontramos plantas con esos criterios.</p>
    @else
        <div class="plantas">
            @foreach ($plantas as $planta)
                <article class="planta">
                    @if ($planta->imagen_url)
                        <img src="{{ $planta->imagen_url }}" alt="{{ $planta->nombre }}">
                    @endif

                    <h2>{{ $planta->nombre }}</h2>
                    <p>{{ $planta->categoria?->nombre ?? 'Sin categoría' }}</p>
                    <p>{{ Str::limit($planta->descripcion, 100) }}</p>
                    <p><strong>$ {{ number_format($planta->precio, 0, ',', '.') }}</strong></p>
                    <a href="{{ route('catalogo.show', $planta) }}">Ver detalle</a>
                </article>
            @endforeach
        </div>

        <div style="margin-top: 24px">
            {{ $plantas->links() }}
        </div>
    @endif
</body>
</html>
