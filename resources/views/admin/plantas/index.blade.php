<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>Administrar plantas</title></head>
<body>
    <h1>Administrar plantas</h1>
    <p><a href="{{ route('admin.plantas.create') }}">Agregar planta</a></p>
    <p><a href="{{ route('admin.categorias.index') }}">Administrar categorías</a></p>

    @if (session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif
    @if (session('error'))
        <p style="color: #b91c1c">{{ session('error') }}</p>
    @endif

    @forelse ($plantas as $planta)
        <section>
            <h2>{{ $planta->nombre }}</h2>
            <p>{{ $planta->categoria?->nombre ?? 'Sin categoría' }}
                · $ {{ number_format($planta->precio, 0, ',', '.') }}
                · Stock: {{ $planta->stock }}</p>
            <a href="{{ route('admin.plantas.show', $planta) }}">Ver</a>
            <a href="{{ route('admin.plantas.edit', $planta) }}">Editar</a>
            <form method="POST" action="{{ route('admin.plantas.destroy', $planta) }}"
                  onsubmit="return confirm('¿Eliminar esta planta?')">
                @csrf
                @method('DELETE')
                <button type="submit">Eliminar</button>
            </form>
        </section>
    @empty
        <p>No hay plantas registradas.</p>
    @endforelse

    {{ $plantas->links() }}
</body>
</html>
