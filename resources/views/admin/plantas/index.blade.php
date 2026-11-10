{{-- Autor: Simon Martinez Gomez --}}
@extends('layouts.admin')

@section('title', __('messages.plantas'))

@section('content')
<div class="admin-cabecera">
    <div>
        <p class="admin-eyebrow">{{ __('messages.catalogo') }}</p>
        <h1>{{ __('messages.plantas') }}</h1>
        <p>{{ __('messages.inventario_descripcion') }}</p>
    </div>
    <div class="admin-acciones-bar">
        <a href="{{ route('admin.dashboard') }}" class="admin-btn-secundario">{{ __('messages.ir_dashboard') }}</a>
        <a href="{{ route('admin.plantas.create') }}" class="admin-btn">{{ __('messages.agregar_planta') }}</a>
    </div>
</div>

<section class="admin-panel">
    @if ($plantas->isEmpty())
        <div class="admin-vacio">
            <p>{{ __('messages.sin_plantas') }}</p>
            <a href="{{ route('admin.plantas.create') }}" class="admin-btn" style="margin-top: 1rem;">{{ __('messages.crear_primera') }}</a>
        </div>
    @else
        <div class="admin-tabla-wrap">
            <table class="admin-tabla">
                <thead>
                    <tr>
                        <th>{{ __('messages.planta') }}</th>
                        <th>{{ __('messages.categoria') }}</th>
                        <th>{{ __('messages.precio') }}</th>
                        <th>{{ __('messages.stock') }}</th>
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
                                    <div>
                                        <strong>{{ $planta->nombre }}</strong>
                                        <div style="color: var(--muted); font-size: 0.82rem; margin-top: 0.15rem;">
                                            {{ \Illuminate\Support\Str::limit($planta->descripcion, 50) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="admin-badge">{{ $planta->categoria?->nombre ?? __('messages.sin_categoria') }}</span></td>
                            <td>$ {{ number_format($planta->precio, 0, ',', '.') }}</td>
                            <td>
                                <span class="admin-stock {{ $planta->stock === 0 ? 'agotado' : ($planta->stock <= 5 ? 'bajo' : 'ok') }}">
                                    {{ $planta->stock }}
                                </span>
                            </td>
                            <td class="admin-acciones">
                                <a href="{{ route('admin.plantas.show', $planta) }}" class="admin-enlace">{{ __('messages.ver') }}</a>
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

        <div class="admin-paginacion">
            {{ $plantas->links() }}
        </div>
    @endif
</section>
@endsection
