{{-- Autor: Simon Martinez Gomez --}}
@extends('layouts.admin')

@section('title', __('messages.dashboard_admin'))

@section('content')
<div class="admin-cabecera">
    <div>
        <p class="admin-eyebrow">{{ __('messages.dashboard_admin') }}</p>
        <h1>{{ __('messages.bienvenida_admin') }}</h1>
        <p>{{ __('messages.dashboard_admin_descripcion') }}</p>
    </div>
    <div class="admin-resumen">
        <div class="admin-stat">
            <span class="admin-stat-valor">{{ $plantas->count() }}</span>
            <span class="admin-stat-etiqueta">{{ __('messages.plantas') }}</span>
        </div>
        <div class="admin-stat">
            <span class="admin-stat-valor">{{ $totalUnidades }}</span>
            <span class="admin-stat-etiqueta">{{ __('messages.unidades_stock') }}</span>
        </div>
        <div class="admin-stat">
            <span class="admin-stat-valor">{{ $categorias->count() }}</span>
            <span class="admin-stat-etiqueta">{{ __('messages.categorias') }}</span>
        </div>
    </div>
</div>

<section class="admin-panel">
    <div class="admin-panel-head">
        <div>
            <h2>{{ __('messages.agregar_planta') }}</h2>
            <p class="admin-subtitulo">{{ __('messages.agregar_planta_ayuda') }}</p>
        </div>
    </div>

    @if ($categorias->isEmpty())
        <div class="admin-vacio">
            <p>{{ __('messages.crear_categoria_primero') }}</p>
            <a class="admin-btn" href="{{ route('admin.categorias.create') }}" style="margin-top: 1rem;">{{ __('messages.crear_categoria') }}</a>
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
            <h2>{{ __('messages.inventario_unidades') }}</h2>
            <p class="admin-subtitulo">{{ __('messages.inventario_ayuda') }}</p>
        </div>
        <a href="{{ route('admin.plantas.index') }}" class="admin-btn-secundario">{{ __('messages.ver_listado') }}</a>
    </div>

    @if ($plantas->isEmpty())
        <div class="admin-vacio">
            <p>{{ __('messages.sin_plantas_aun') }}</p>
        </div>
    @else
        <div class="admin-tabla-wrap">
            <table class="admin-tabla">
                <thead>
                    <tr>
                        <th>{{ __('messages.planta') }}</th>
                        <th>{{ __('messages.categoria') }}</th>
                        <th>{{ __('messages.precio') }}</th>
                        <th>{{ __('messages.unidades') }}</th>
                        <th>{{ __('messages.estado') }}</th>
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
                                        <div class="admin-thumb admin-thumb-empty">{{ __('messages.sin_imagen_abreviado') }}</div>
                                    @endif
                                    <strong>{{ $planta->nombre }}</strong>
                                </div>
                            </td>
                            <td><span class="admin-badge">{{ $planta->categoria?->nombre ?? __('messages.sin_categoria') }}</span></td>
                            <td>$ {{ number_format($planta->precio, 0, ',', '.') }}</td>
                            <td>
                                <span class="admin-stock {{ $planta->stock === 0 ? 'agotado' : ($planta->stock <= 5 ? 'bajo' : 'ok') }}">
                                    {{ $planta->stock }}
                                </span>
                            </td>
                            <td>
                                @if ($planta->stock === 0)
                                    {{ __('messages.agotado') }}
                                @elseif ($planta->stock <= 5)
                                    {{ __('messages.stock_bajo') }}
                                @else
                                    {{ __('messages.disponible') }}
                                @endif
                            </td>
                            <td class="admin-acciones">
                                <a href="{{ route('admin.plantas.edit', $planta) }}" class="admin-enlace">{{ __('messages.editar') }}</a>
                                <form method="POST" action="{{ route('admin.plantas.destroy', $planta) }}"
                                    onsubmit="return confirm({{ Illuminate\Support\Js::from(__('messages.confirmar_eliminar_planta', ['nombre' => $planta->nombre])) }})">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn-peligro">{{ __('messages.eliminar') }}</button>
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
