@extends('layouts.app')

@section('titulo', 'Catálogo')

@section('contenido')
    <h1>Catálogo de plantas</h1>

    <form method="GET" action="{{ route('plantas.index') }}" class="filtro-bar">
        <div>
            <label for="categoria_id">Filtrar por categoría</label>
            <select name="categoria_id" id="categoria_id">
                <option value="">Todas las categorías</option>
                @foreach($viewData["categorias"] as $categoria)
                    <option value="{{ $categoria->getId() }}" @selected($viewData["categoriaSeleccionada"] == $categoria->getId())>
                        {{ $categoria->getNombre() }}
                    </option>
                @endforeach
            </select>
        </div>
        <div style="flex: 0;">
            <button type="submit" class="btn">Filtrar</button>
        </div>
    </form>

    @if($viewData["plantas"]->isEmpty())
        <div class="vacio">
            <span class="vacio-emoji">🌿</span>
            <p>No hay plantas disponibles en esta categoría.</p>
            <a href="{{ route('plantas.index') }}" class="btn btn-secundario">Ver todas las plantas</a>
        </div>
    @else
        <div class="grid-plantas">
            @foreach($viewData["plantas"] as $planta)
                <article class="planta-card">
                    <div class="planta-imagen">
                        @if($planta->getImagenUrl())
                            <img src="{{ $planta->getImagenUrl() }}" alt="{{ $planta->getNombre() }}">
                        @else
                            🪴
                        @endif
                    </div>
                    <div class="planta-cuerpo">
                        <h3>
                            <a href="{{ route('plantas.show', $planta->getId()) }}">
                                {{ $planta->getNombreListado() }}
                            </a>
                        </h3>
                        <div class="planta-meta">
                            <span class="badge">{{ $planta->getNombreCategoria() }}</span>
                            @if($planta->getStock() > 0)
                                <span style="color: var(--gris); font-size: 0.85rem;">{{ $planta->getStock() }} disponibles</span>
                            @else
                                <span class="badge badge-warn">Sin stock</span>
                            @endif
                        </div>
                        <div class="precio">{{ $planta->getPrecioFormateado() }}</div>

                        @if($planta->getStock() > 0)
                            <form method="POST" action="{{ route('carrito.agregar') }}" class="form-inline">
                                @csrf
                                <input type="hidden" name="planta_id" value="{{ $planta->getId() }}">
                                <input type="number" name="cantidad" id="cantidad_{{ $planta->getId() }}" value="1" min="1" max="{{ $planta->getStock() }}" required aria-label="Cantidad">
                                <button type="submit" class="btn" style="flex: 1;">Agregar al carrito</button>
                            </form>
                        @else
                            <a href="{{ route('plantas.show', $planta->getId()) }}" class="btn btn-secundario btn-bloque">Ver detalle</a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @endif
@endsection
