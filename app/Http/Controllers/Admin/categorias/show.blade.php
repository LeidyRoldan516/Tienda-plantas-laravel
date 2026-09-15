<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>{{ $categoria->nombre }}</title></head>
<body>
    <a href="{{ route('admin.categorias.index') }}">← Volver</a>
    <h1>{{ $categoria->nombre }}</h1>
    <p>Plantas en esta categoría: {{ $categoria->plantas->count() }}</p>
    <a href="{{ route('admin.categorias.edit', $categoria) }}">Editar</a>
</body>
</html>
