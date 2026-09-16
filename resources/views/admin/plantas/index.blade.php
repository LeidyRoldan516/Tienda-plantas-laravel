{{-- Autor: Simon Martinez Gomez --}}
@extends('layouts.admin')

@section('title', 'Plantas')

@section('content')
<div class="admin-cabecera">
    <div>
        <p class="admin-eyebrow">Catálogo</p>
        <h1>Plantas</h1>
        <p>Administra el inventario completo de la tienda.</p>
    </div>
    <div class="admin-acciones-bar">
        <a href="{{ route('admin.dashboard') }}" class="admin-btn-secundario">Ir al dashboard</a>
        <a href="{{ route('admin.plantas.create') }}" class="admin-btn">Agregar planta</a>
    </div>
</div>

<section class="admin-panel">
    @if ($plantas->isEmpty())
        <div class="admin-vacio">
            <p>No hay plantas registradas.</p>
            <a href="{{ route('admin.plantas.create') }}" class="admin-btn" style="margin-top: 1rem;">Crear la primera</a>
        </div>
    @else
        <div class="admin-tabla-wrap">
            <table class="admin-tabla">
                <thead>
                    <tr>
                        <th>Planta</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($plantas as $planta)
                        <tr>
                            <td>
                                <div class="admin-planta-cell">
                                    @if ($planta->imagen_url)
                                        <img src="{{ $planta->imagen_url }}" alt="" class="admin-thumb">
                                    @else
                                        <div class="admin-thumb admin-thumb-empty">NP</div>
                                    @endif
                                    <div>
                                        <strong>{{ $planta->nombre }}</strong>
                                        <div style="color: var(--muted); font-size: 0.82rem; margin-top: 0.15rem;">
                                            {{ \Illuminate\Support\Str::limit($planta->descripcion, 50) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="admin-badge">{{ $planta->categoria?->nombre ?? 'Sin categoría' }}</span></td>
                            <td>$ {{ number_format($planta->precio, 0, ',', '.') }}</td>
                            <td>
                                <span class="admin-stock {{ $planta->stock === 0 ? 'agotado' : ($planta->stock <= 5 ? 'bajo' : 'ok') }}">
                                    {{ $planta->stock }}
                                </span>
                            </td>
                            <td class="admin-acciones">
                                <a href="{{ route('admin.plantas.show', $planta) }}" class="admin-enlace">Ver</a>
                                <a href="{{ route('admin.plantas.edit', $planta) }}" class="admin-enlace">Editar</a>
                                <form method="POST" action="{{ route('admin.plantas.destroy', $planta) }}"
                                    onsubmit="return confirm('¿Eliminar {{ $planta->nombre }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn-peligro">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="admin-paginacion">
            {{ $plantas->links() }}
        </div>
    @endif
</section>
@endsection
