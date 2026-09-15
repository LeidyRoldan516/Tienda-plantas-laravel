@extends('layouts.app')

@section('titulo', $viewData["planta"]->getNombre())

@section('contenido')
    <p><a href="{{ route('plantas.index') }}">← Volver al catálogo</a></p>

    <div class="detalle-planta">
        <div class="imagen">
            @if($viewData["planta"]->getImagenUrl())
                <img src="{{ $viewData["planta"]->getImagenUrl() }}" alt="{{ $viewData["planta"]->getNombre() }}">
            @else
                🪴
            @endif
        </div>
        <div>
            <span class="badge">{{ $viewData["planta"]->getNombreCategoria() }}</span>
            <h1 style="margin-top: 12px;">{{ $viewData["planta"]->getNombreListado() }}</h1>
            <div class="precio" style="font-size: 1.8rem;">{{ $viewData["planta"]->getPrecioFormateado() }}</div>

            <h2 style="margin-top: 20px; font-size: 1.15rem;">Descripción</h2>
            <p>{{ $viewData["planta"]->getDescripcion() }}</p>

            <p><strong>Stock disponible:</strong> {{ $viewData["planta"]->getStock() }}</p>

            @if($viewData["planta"]->getStock() > 0)
                <form method="POST" action="{{ route('carrito.agregar') }}" class="form-inline" style="margin-top: 20px;">
                    @csrf
                    <input type="hidden" name="planta_id" value="{{ $viewData["planta"]->getId() }}">
                    <label for="cantidad" style="margin: 0; margin-right: 4px;">Cantidad:</label>
                    <input type="number" name="cantidad" id="cantidad" value="1" min="1" max="{{ $viewData["planta"]->getStock() }}" required>
                    <button type="submit" class="btn">Agregar al carrito</button>
                </form>
            @else
                <div class="alerta alerta-error" style="margin-top: 20px;">Este producto no tiene stock disponible.</div>
            @endif
        </div>
    </div>
@endsection
