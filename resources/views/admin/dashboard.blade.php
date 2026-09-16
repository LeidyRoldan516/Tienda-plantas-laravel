{{-- Autor: Simon Martinez Gomez --}}
@extends('layouts.admin')

@section('title', __('messages.dashboard_admin'))

@section('content')
<div class="admin-cabecera">
    <div>
        <p class="admin-eyebrow">Administración</p>
        <h1>{{ __('messages.bienvenida_admin') }}</h1>
        <p>{{ __('messages.dashboard_admin_descripcion') }}</p>
    </div>
    <div class="admin-resumen">
        <div class="admin-stat">
            <span class="admin-stat-valor">{{ $plantas->count() }}</span>
            <span class="admin-stat-etiqueta">Plantas</span>
        </div>
        <div class="admin-stat">
            <span class="admin-stat-valor">{{ $totalUnidades }}</span>
            <span class="admin-stat-etiqueta">Unidades en stock</span>
        </div>
        <div class="admin-stat">
            <span class="admin-stat-valor">{{ $categorias->count() }}</span>
            <span class="admin-stat-etiqueta">Categorías</span>
        </div>
    </div>
</div>

<section class="admin-panel">
    <div class="admin-panel-head">
        <div>
            <h2>Agregar planta</h2>
            <p class="admin-subtitulo">Registra una nueva planta en el catálogo.</p>
        </div>
    </div>

    @if ($categorias->isEmpty())
        <div class="admin-vacio">
            <p>Primero crea al menos una categoría.</p>
            <a class="admin-btn" href="{{ route('admin.categorias.create') }}" style="margin-top: 1rem;">Crear categoría</a>
        </div>
    @else
        <form method="POST" action="{{ route('admin.plantas.store') }}">
            @csrf
            @include('admin.plantas.form', ['planta' => null])
        </form>
    @endif
</section>

<section class="admin-panel">
    <div class="admin-panel-head">
        <div>
            <h2>Inventario de unidades</h2>
            <p class="admin-subtitulo">Consulta el stock y elimina plantas que ya no ofrezcas.</p>
        </div>
        <a href="{{ route('admin.plantas.index') }}" class="admin-btn-secundario">Ver listado completo</a>
    </div>

    @if ($plantas->isEmpty())
        <div class="admin-vacio">
            <p>Aún no hay plantas registradas.</p>
        </div>
    @else
        <div class="admin-tabla-wrap">
            <table class="admin-tabla">
                <thead>
                    <tr>
                        <th>Planta</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Unidades</th>
                        <th>Estado</th>
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
                                    <strong>{{ $planta->nombre }}</strong>
                                </div>
                            </td>
                            <td><span class="admin-badge">{{ $planta->categoria?->nombre ?? 'Sin categoría' }}</span></td>
                            <td>$ {{ number_format($planta->precio, 0, ',', '.') }}</td>
                            <td>
                                <span class="admin-stock {{ $planta->stock === 0 ? 'agotado' : ($planta->stock <= 5 ? 'bajo' : 'ok') }}">
                                    {{ $planta->stock }}
                                </span>
                            </td>
                            <td>
                                @if ($planta->stock === 0)
                                    Agotada
                                @elseif ($planta->stock <= 5)
                                    Stock bajo
                                @else
                                    Disponible
                                @endif
                            </td>
                            <td class="admin-acciones">
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
    @endif
</section>
@endsection
