<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Editar categoría</title></head>
<body>
    <a href="{{ route('admin.categorias.index') }}">← Volver</a>
    <h1>Editar categoría</h1>

    @foreach ($errors->all() as $error) <p>{{ $error }}</p> @endforeach

    <form action="{{ route('admin.categorias.update', $categoria) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="nombre">Nombre</label>
        <input id="nombre" name="nombre"
               value="{{ old('nombre', $categoria->nombre) }}" required>
        <button type="submit">Actualizar</button>
    </form>
</body>
</html>
