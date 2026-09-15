<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Administrar categorías</title></head>
<body>
    <h1>Categorías</h1>
    <a href="{{ route('admin.categorias.create') }}">Nueva categoría</a>

    @if (session('mensaje')) <p>{{ session('mensaje') }}</p> @endif
    @foreach ($errors->all() as $error) <p>{{ $error }}</p> @endforeach

    <ul>
        @foreach ($categorias as $categoria)
            <li>
                {{ $categoria->nombre }}
                <a href="{{ route('admin.categorias.show', $categoria) }}">Ver</a>
                <a href="{{ route('admin.categorias.edit', $categoria) }}">Editar</a>
                <form action="{{ route('admin.categorias.destroy', $categoria) }}"
                      method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Eliminar</button>
                </form>
            </li>
        @endforeach
    </ul>

    {{ $categorias->links() }}
</body>
</html>
