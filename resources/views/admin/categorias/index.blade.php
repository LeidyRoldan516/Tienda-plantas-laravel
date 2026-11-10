{{-- Autor: Simon Martinez Gomez --}}
@extends('layouts.admin')

@section('title', __('messages.categorias'))

@section('content')
<div class="admin-cabecera">
    <div>
        <p class="admin-eyebrow">{{ __('messages.organizacion') }}</p>
        <h1>{{ __('messages.categorias') }}</h1>
        <p>{{ __('messages.categorias_ayuda') }}</p>
    </div>
    <a href="{{ route('admin.categorias.create') }}" class="admin-btn">{{ __('messages.nueva_categoria') }}</a>
</div>

<section class="admin-panel">
    @if ($categorias->isEmpty())
        <div class="admin-vacio">
            <p>{{ __('messages.sin_categorias') }}</p>
            <a href="{{ route('admin.categorias.create') }}" class="admin-btn" style="margin-top: 1rem;">{{ __('messages.crear_categoria') }}</a>
        </div>
    @else
        <div class="admin-tabla-wrap">
            <table class="admin-tabla">
                <thead>
                    <tr>
                        <th>{{ __('messages.nombre') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categorias as $categoria)
                        <tr>
                            <td><strong>{{ $categoria->nombre }}</strong></td>
                            <td class="admin-acciones">
                                <a href="{{ route('admin.categorias.show', $categoria) }}" class="admin-enlace">{{ __('messages.ver') }}</a>
                                <a href="{{ route('admin.categorias.edit', $categoria) }}" class="admin-enlace">{{ __('messages.editar') }}</a>
                                <form method="POST" action="{{ route('admin.categorias.destroy', $categoria) }}"
                                    onsubmit="return confirm({{ Illuminate\Support\Js::from(__('messages.confirmar_eliminar_categoria', ['nombre' => $categoria->nombre])) }})">
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
            {{ $categorias->links() }}
        </div>
    @endif
</section>
@endsection
