{{-- Autor: Simon Martinez Gomez --}}
@extends('layouts.admin')

@section('title', 'Categorías')

@section('content')
<div class="admin-cabecera">
    <div>
        <p class="admin-eyebrow">Organización</p>
        <h1>Categorías</h1>
        <p>Agrupa las plantas del catálogo por tipo.</p>
    </div>
    <a href="{{ route('admin.categorias.create') }}" class="admin-btn">Nueva categoría</a>
</div>

<section class="admin-panel">
    @if ($categorias->isEmpty())
        <div class="admin-vacio">
            <p>No hay categorías todavía.</p>
            <a href="{{ route('admin.categorias.create') }}" class="admin-btn" style="margin-top: 1rem;">Crear categoría</a>
        </div>
    @else
        <div class="admin-tabla-wrap">
            <table class="admin-tabla">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categorias as $categoria)
                        <tr>
                            <td><strong>{{ $categoria->nombre }}</strong></td>
                            <td class="admin-acciones">
                                <a href="{{ route('admin.categorias.show', $categoria) }}" class="admin-enlace">Ver</a>
                                <a href="{{ route('admin.categorias.edit', $categoria) }}" class="admin-enlace">Editar</a>
                                <form method="POST" action="{{ route('admin.categorias.destroy', $categoria) }}"
                                    onsubmit="return confirm('¿Eliminar la categoría {{ $categoria->nombre }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn-peligro">Eliminar</button>
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
