<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Nueva categoría</title></head>
<body>
    <a href="{{ route('admin.categorias.index') }}">← Volver</a>
    <h1>Nueva categoría</h1>

    @foreach ($errors->all() as $error) <p>{{ $error }}</p> @endforeach

    <form action="{{ route('admin.categorias.store') }}" method="POST">
        @csrf
        <label for="nombre">Nombre</label>
        <input id="nombre" name="nombre" value="{{ old('nombre') }}" required>
        <button type="submit">Guardar</button>
    </form>
</body>
</html>
