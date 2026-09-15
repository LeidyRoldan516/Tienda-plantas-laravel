<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>{{ $planta->nombre }}</title></head>
<body>
    <h1>{{ $planta->nombre }}</h1>
    <p>Categoría: {{ $planta->categoria?->nombre ?? 'Sin categoría' }}</p>
    <p>{{ $planta->descripcion }}</p>
    <p>Precio: $ {{ number_format($planta->precio, 0, ',', '.') }}</p>
    <p>Disponibles: {{ $planta->stock }}</p>

    @if ($planta->imagen_url)
        <p><a href="{{ $planta->imagen_url }}">Ver imagen</a></p>
    @endif

    <p><a href="{{ route('admin.plantas.edit', $planta) }}">Editar</a></p>
    <p><a href="{{ route('admin.plantas.index') }}">Volver al listado</a></p>
</body>
</html>
