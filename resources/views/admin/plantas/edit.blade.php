{{-- Autor: Simon Martinez Gomez --}}
@extends('layouts.admin')

@section('title', __('messages.editar_planta'))

@section('content')
<div class="admin-cabecera">
    <div>
        <p class="admin-eyebrow">{{ __('messages.plantas') }}</p>
        <h1>{{ __('messages.editar_planta') }}</h1>
        <p>{{ $planta->nombre }}</p>
    </div>
    <div class="admin-acciones-bar">
        <a href="{{ route('admin.plantas.show', $planta) }}" class="admin-btn-secundario">{{ __('messages.ver_detalle') }}</a>
        <a href="{{ route('admin.plantas.index') }}" class="admin-btn-secundario">{{ __('messages.volver') }}</a>
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
