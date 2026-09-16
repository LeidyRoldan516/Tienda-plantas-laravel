{{-- Autor: Simon Martinez Gomez --}}
@extends('layouts.admin')

@section('title', 'Agregar planta')

@section('content')
<div class="admin-cabecera">
    <div>
        <p class="admin-eyebrow">Plantas</p>
        <h1>Agregar planta</h1>
        <p>Completa los datos para publicarla en el catálogo.</p>
    </div>
    <a href="{{ route('admin.plantas.index') }}" class="admin-btn-secundario">Volver al listado</a>
</div>

<section class="admin-panel">
    <form method="POST" action="{{ route('admin.plantas.store') }}">
        @csrf
        @include('admin.plantas.form', ['planta' => null])
    </form>
</section>
@endsection
