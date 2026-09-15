<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $planta->nombre }}</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 30px auto; padding: 0 16px; }
        img { max-width: 100%; max-height: 400px; object-fit: contain; }
    </style>
</head>
<body>
    <a href="{{ route('catalogo.index') }}">← Volver al catálogo</a>

    <h1>{{ $planta->nombre }}</h1>

    @if ($planta->imagen_url)
        <img src="{{ $planta->imagen_url }}" alt="{{ $planta->nombre }}">
    @endif

    <p><strong>Categoría:</strong> {{ $planta->categoria?->nombre ?? 'Sin categoría' }}</p>
    <p>{{ $planta->descripcion }}</p>
    <p><strong>Precio:</strong> $ {{ number_format($planta->precio, 0, ',', '.') }}</p>
    <p><strong>Disponibles:</strong> {{ $planta->stock }}</p>
    @if ($planta->stock > 0)
    @auth
        <form action="{{ route('carrito.agregar') }}" method="POST">
            @csrf
            <input type="hidden" name="planta_id" value="{{ $planta->id }}">
            <label for="cantidad">Cantidad:</label>
            <input id="cantidad" type="number" name="cantidad"
                   min="1" max="{{ $planta->stock }}" value="1" required>
            <button type="submit">Agregar al carrito</button>
        </form>
        <a href="{{ route('carrito.index') }}">Ver mi carrito</a>
    @else
        <p>Inicia sesión para agregar esta planta al carrito.</p>
    @endauth
@else
    <p>Esta planta no está disponible.</p>
@endif
</body>
</html>
