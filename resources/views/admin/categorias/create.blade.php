{{-- Autor: Simon Martinez Gomez --}}
@extends('layouts.admin')

@section('title', 'Nueva categoría')

@section('content')
<div class="admin-cabecera">
    <div>
        <p class="admin-eyebrow">Categorías</p>
        <h1>Nueva categoría</h1>
        <p>Define un grupo para organizar el catálogo.</p>
    </div>
    <a href="{{ route('admin.categorias.index') }}" class="admin-btn-secundario">Volver</a>
</div>

<section class="admin-panel" style="max-width: 32rem;">
    <form method="POST" action="{{ route('admin.categorias.store') }}">
        @csrf
        <label class="admin-campo" style="margin-bottom: 1.15rem;">
            <span>Nombre</span>
            <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}" required autofocus>
        </label>
        <button type="submit" class="admin-btn">Guardar categoría</button>
    </form>
</section>
@endsection
