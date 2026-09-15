@extends('layouts.app')

@section('titulo', 'Registrar categoría')

@section('contenido')
    <p><a href="{{ route('admin.index') }}">← Volver al panel administrativo</a></p>

    <h1>Registrar categoría</h1>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start;">
        <div class="card">
            <h2 style="margin-top: 0;">Nueva categoría</h2>
            <form method="POST" action="{{ route('categorias.save') }}" class="stack">
                @csrf

                <p>
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required>
                </p>

                <p>
                    <button type="submit" class="btn">Guardar categoría</button>
                </p>
            </form>
        </div>

        <div class="card">
            <h2 style="margin-top: 0;">Categorías existentes</h2>
            @forelse($viewData["categorias"] as $categoria)
                <p style="padding: 10px 14px; background: var(--verde-suave); border-radius: 8px; margin: 8px 0;">
                    <span class="badge">{{ $categoria->getNombre() }}</span>
                </p>
            @empty
                <p style="color: var(--gris);">Aún no hay categorías registradas.</p>
            @endforelse
        </div>
    </div>
@endsection
