@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')
    <section class="hero">
        <h1>Trae la naturaleza a casa</h1>
        <p>Descubre plantas cuidadosamente seleccionadas para transformar tus espacios. Interior, exterior, suculentas y mucho más.</p>
        <a href="{{ route('plantas.index') }}" class="btn">Explorar catálogo</a>
    </section>

    <h2>Plantas recientes</h2>

    @if($viewData["destacadas"]->isEmpty())
        <div class="vacio">
            <span class="vacio-emoji">🌱</span>
            <p>Aún no hay plantas registradas.</p>
            <a href="{{ route('admin.index') }}" class="btn btn-secundario">Ir al panel administrativo</a>
        </div>
    @else
        <div class="grid-plantas">
            @foreach($viewData["destacadas"] as $planta)
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
                        <div class="precio">{{ $planta->getPrecioFormateado() }}</div>
                        <a href="{{ route('plantas.show', $planta->getId()) }}" class="btn btn-secundario btn-bloque">Ver detalle</a>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
@endsection
