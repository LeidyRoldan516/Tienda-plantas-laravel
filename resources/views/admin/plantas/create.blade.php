{{-- Autor: Simon Martinez Gomez --}}
@extends('layouts.admin')

@section('title', __('messages.agregar_planta'))

@section('content')
<div class="admin-cabecera">
    <div>
        <p class="admin-eyebrow">{{ __('messages.plantas') }}</p>
        <h1>{{ __('messages.agregar_planta') }}</h1>
        <p>{{ __('messages.nueva_planta_ayuda') }}</p>
    </div>
    <a href="{{ route('admin.plantas.index') }}" class="admin-btn-secundario">{{ __('messages.volver_listado') }}</a>
</div>

<section class="admin-panel">
    <form method="POST" action="{{ route('admin.plantas.store') }}">
        @csrf
        @include('admin.plantas.form', ['planta' => null])
    </form>
</section>
@endsection
