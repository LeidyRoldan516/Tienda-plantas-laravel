@extends('layouts.app')

@section('titulo', 'Carrito')

@section('contenido')
    <h1>Carrito de compras</h1>

    @if($viewData["items"]->isEmpty())
        <div class="vacio">
            <span class="vacio-emoji">🛒</span>
            <p>Tu carrito está vacío.</p>
            <a href="{{ route('plantas.index') }}" class="btn">Explorar catálogo</a>
        </div>
    @else
        @foreach($viewData["items"] as $planta)
            <div class="item-carrito">
                <div class="mini-img">
                    @if($planta->getImagenUrl())
                        <img src="{{ $planta->getImagenUrl() }}" alt="{{ $planta->getNombre() }}">
                    @else
                        🪴
                    @endif
                </div>
                <div>
                    <h3>{{ $planta->getNombreListado() }}</h3>
                    <div class="info">
                        {{ $planta->getNombreCategoria() }} · {{ $viewData["cantidades"][$planta->getId()] }} × {{ $planta->getPrecioFormateado() }}
                    </div>
                    <div class="subtotal">
                        Subtotal: $ {{ number_format($planta->getPrecio() * $viewData["cantidades"][$planta->getId()], 0, ',', '.') }}
                    </div>
                </div>
                <form method="POST" action="{{ route('carrito.eliminar') }}">
                    @csrf
                    <input type="hidden" name="planta_id" value="{{ $planta->getId() }}">
                    <button type="submit" class="btn btn-peligro">Eliminar</button>
                </form>
            </div>
        @endforeach

        <div class="resumen-carrito">
            <div>
                <div class="total-label">Total del pedido</div>
                <div class="total-monto">$ {{ number_format($viewData["total"], 0, ',', '.') }}</div>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <form method="POST" action="{{ route('carrito.vaciar') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-peligro">Vaciar carrito</button>
                </form>
                <a href="{{ route('plantas.index') }}" class="btn" style="background: var(--verde-claro); color: var(--verde-oscuro);">Seguir comprando</a>
            </div>
        </div>
    @endif
@endsection
