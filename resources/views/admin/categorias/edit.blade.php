{{-- Autor: Simon Martinez Gomez --}}
@extends('layouts.admin')

@section('title', __('messages.editar_categoria'))

@section('content')
<div class="admin-cabecera">
    <div>
        <p class="admin-eyebrow">{{ __('messages.categorias') }}</p>
        <h1>{{ __('messages.editar_categoria') }}</h1>
        <p>{{ $categoria->nombre }}</p>
    </div>
    <a href="{{ route('admin.categorias.index') }}" class="admin-btn-secundario">{{ __('messages.volver') }}</a>
</div>

<section class="admin-panel" style="max-width: 32rem;">
    <form method="POST" action="{{ route('admin.categorias.update', $categoria) }}">
        @csrf
        @method('PUT')
        <label class="admin-campo" style="margin-bottom: 1.15rem;">
            <span>{{ __('messages.nombre') }}</span>
            <input id="nombre" type="text" name="nombre"
                value="{{ old('nombre', $categoria->nombre) }}" required autofocus>
        </label>
        <button type="submit" class="admin-btn">{{ __('messages.actualizar_categoria') }}</button>
    </form>
</section>
@endsection
