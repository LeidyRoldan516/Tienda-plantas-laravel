{{-- Autor: Simon Martinez Gomez --}}
@extends('layouts.admin')

@section('title', __('messages.nueva_categoria'))

@section('content')
<div class="admin-cabecera">
    <div>
        <p class="admin-eyebrow">{{ __('messages.categorias') }}</p>
        <h1>{{ __('messages.nueva_categoria') }}</h1>
        <p>{{ __('messages.nueva_categoria_ayuda') }}</p>
    </div>
    <a href="{{ route('admin.categorias.index') }}" class="admin-btn-secundario">{{ __('messages.volver') }}</a>
</div>

<section class="admin-panel" style="max-width: 32rem;">
    <form method="POST" action="{{ route('admin.categorias.store') }}">
        @csrf
        <label class="admin-campo" style="margin-bottom: 1.15rem;">
            <span>{{ __('messages.nombre') }}</span>
            <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}" required autofocus>
        </label>
        <button type="submit" class="admin-btn">{{ __('messages.guardar_categoria') }}</button>
    </form>
</section>
@endsection
