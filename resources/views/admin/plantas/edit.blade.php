{{-- Autor: Simon Martinez Gomez --}}
@extends('layouts.admin')

@section('title', 'Editar planta')

@section('content')
<div class="admin-cabecera">
    <div>
        <p class="admin-eyebrow">Plantas</p>
        <h1>Editar planta</h1>
        <p>{{ $planta->nombre }}</p>
    </div>
    <div class="admin-acciones-bar">
        <a href="{{ route('admin.plantas.show', $planta) }}" class="admin-btn-secundario">Ver detalle</a>
        <a href="{{ route('admin.plantas.index') }}" class="admin-btn-secundario">Volver</a>
    </div>
</div>

<section class="admin-panel">
    <form method="POST" action="{{ route('admin.plantas.update', $planta) }}">
        @csrf
        @method('PUT')
        @include('admin.plantas.form')
    </form>
</section>
@endsection
