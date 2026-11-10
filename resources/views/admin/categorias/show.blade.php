{{-- Autor: Simon Martinez Gomez --}}
@extends('layouts.admin')

@section('title', $categoria->nombre)

@section('content')
<div class="admin-cabecera">
    <div>
        <p class="admin-eyebrow">{{ __('messages.categoria') }}</p>
        <h1>{{ $categoria->nombre }}</h1>
        <p>{{ __('messages.categoria_plantas_ayuda') }}</p>
    </div>
    <div class="admin-acciones-bar">
        <a href="{{ route('admin.categorias.edit', $categoria) }}" class="admin-btn">{{ __('messages.editar') }}</a>
        <a href="{{ route('admin.categorias.index') }}" class="admin-btn-secundario">{{ __('messages.volver') }}</a>
    </div>
</div>

<section class="admin-panel">
    <div class="admin-resumen" style="margin-bottom: 1.25rem;">
        <div class="admin-stat">
            <span class="admin-stat-valor">{{ $categoria->plantas->count() }}</span>
            <span class="admin-stat-etiqueta">{{ __('messages.plantas_categoria') }}</span>
        </div>
    </div>

    @if ($categoria->plantas->isEmpty())
        <div class="admin-vacio">
            <p>{{ __('messages.categoria_vacia') }}</p>
        </div>
    @else
        <div class="admin-tabla-wrap">
            <table class="admin-tabla">
                <thead>
                    <tr>
                        <th>{{ __('messages.planta') }}</th>
                        <th>{{ __('messages.precio') }}</th>
                        <th>{{ __('messages.stock') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categoria->plantas as $planta)
                        <tr>
                            <td><strong>{{ $planta->nombre }}</strong></td>
                            <td>$ {{ number_format($planta->precio, 0, ',', '.') }}</td>
                            <td>
                                <span class="admin-stock {{ $planta->stock === 0 ? 'agotado' : ($planta->stock <= 5 ? 'bajo' : 'ok') }}">
                                    {{ $planta->stock }}
                                </span>
                            </td>
                            <td class="admin-acciones">
                                <a href="{{ route('admin.plantas.show', $planta) }}" class="admin-enlace">{{ __('messages.ver') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>
@endsection
