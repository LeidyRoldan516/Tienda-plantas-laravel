{{-- Autor: Simon Martinez Gomez --}}
@extends('layouts.admin')

@section('title', $planta->nombre)

@section('content')
<div class="admin-cabecera">
    <div>
        <p class="admin-eyebrow">Detalle</p>
        <h1>{{ $planta->nombre }}</h1>
        <p>Información completa de la planta en inventario.</p>
    </div>
    <div class="admin-acciones-bar">
        <a href="{{ route('admin.plantas.edit', $planta) }}" class="admin-btn">Editar</a>
        <a href="{{ route('admin.plantas.index') }}" class="admin-btn-secundario">Volver</a>
    </div>
</div>

<section class="admin-panel">
    <div class="admin-detalle-grid">
        @if ($planta->imagen_url)
            <img src="{{ $planta->imagen_url }}" alt="{{ $planta->nombre }}" class="admin-detalle-img">
        @else
            <div class="admin-detalle-img admin-thumb-empty" style="display:grid; place-items:center;">Sin imagen</div>
        @endif

        <div>
            <span class="admin-badge">{{ $planta->categoria?->nombre ?? 'Sin categoría' }}</span>
            <p style="margin: 1rem 0; line-height: 1.6; color: var(--muted);">{{ $planta->descripcion }}</p>

            <div class="admin-meta">
                <div>
                    <strong>Precio</strong>
                    <span>$ {{ number_format($planta->precio, 0, ',', '.') }}</span>
                </div>
                <div>
                    <strong>Unidades</strong>
                    <span class="admin-stock {{ $planta->stock === 0 ? 'agotado' : ($planta->stock <= 5 ? 'bajo' : 'ok') }}">
                        {{ $planta->stock }}
                    </span>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.plantas.destroy', $planta) }}"
                onsubmit="return confirm('¿Eliminar {{ $planta->nombre }}?')" style="margin-top: 1.25rem;">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-btn-peligro">Eliminar planta</button>
            </form>
        </div>
    </div>
</section>
@endsection
