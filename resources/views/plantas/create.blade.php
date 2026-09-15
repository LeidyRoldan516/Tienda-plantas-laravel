@extends('layouts.app')

@section('titulo', 'Registrar planta')

@section('contenido')
    <p><a href="{{ route('admin.index') }}">← Volver al panel administrativo</a></p>

    <h1>Registrar planta</h1>

    @if($viewData["categorias"]->isEmpty())
        <div class="vacio">
            <span class="vacio-emoji">🏷️</span>
            <p>Debe registrar al menos una categoría antes de agregar plantas.</p>
            <a href="{{ route('categorias.create') }}" class="btn">Registrar categoría</a>
        </div>
    @else
        <div class="card" style="max-width: 640px;">
            <form method="POST" action="{{ route('plantas.save') }}" class="stack">
                @csrf

                <p>
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required>
                </p>

                <p>
                    <label for="descripcion">Descripción</label>
                    <textarea name="descripcion" id="descripcion" required>{{ old('descripcion') }}</textarea>
                </p>

                <p style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <span>
                        <label for="precio">Precio</label>
                        <input type="number" name="precio" id="precio" value="{{ old('precio') }}" min="0" required>
                    </span>
                    <span>
                        <label for="stock">Stock</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock') }}" min="0" required>
                    </span>
                </p>

                <p>
                    <label for="imagen_url">URL de imagen (opcional)</label>
                    <input type="text" name="imagen_url" id="imagen_url" value="{{ old('imagen_url') }}" placeholder="https://...">
                </p>

                <p>
                    <label for="categoria_id">Categoría</label>
                    <select name="categoria_id" id="categoria_id" required>
                        <option value="">Seleccione una categoría</option>
                        @foreach($viewData["categorias"] as $categoria)
                            <option value="{{ $categoria->getId() }}" @selected(old('categoria_id') == $categoria->getId())>
                                {{ $categoria->getNombre() }}
                            </option>
                        @endforeach
                    </select>
                </p>

                <p style="margin-top: 24px;">
                    <button type="submit" class="btn">Guardar planta</button>
                    <a href="{{ route('admin.index') }}" class="btn btn-secundario">Cancelar</a>
                </p>
            </form>
        </div>
    @endif
@endsection
