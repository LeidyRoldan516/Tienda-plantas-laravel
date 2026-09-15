@extends('layouts.app')

@section('titulo', 'Panel administrativo')

@section('contenido')
    <h1>Panel administrativo</h1>
    <p style="color: var(--gris); margin-top: -6px;">Gestiona plantas y categorías desde un solo lugar.</p>

    <div class="stats">
        <div class="stat">
            <span class="num">{{ $viewData["totalPlantas"] }}</span>
            <span class="lbl">Plantas registradas</span>
        </div>
        <div class="stat">
            <span class="num">{{ $viewData["totalCategorias"] }}</span>
            <span class="lbl">Categorías registradas</span>
        </div>
    </div>

    <h2>Acciones</h2>
    <div class="acciones-admin">
        <a href="{{ route('categorias.create') }}" class="accion-admin">
            <span class="emoji">🏷️</span>
            <span class="txt">Registrar categoría</span>
        </a>
        <a href="{{ route('plantas.create') }}" class="accion-admin">
            <span class="emoji">🪴</span>
            <span class="txt">Registrar planta</span>
        </a>
        <a href="{{ route('plantas.index') }}" class="accion-admin">
            <span class="emoji">🛍️</span>
            <span class="txt">Ver catálogo público</span>
        </a>
    </div>

    <h2>Plantas registradas</h2>

    @if($viewData["plantas"]->isEmpty())
        <div class="vacio">
            <span class="vacio-emoji">🌱</span>
            <p>Aún no hay plantas registradas.</p>
            <a href="{{ route('plantas.create') }}" class="btn">Registrar la primera planta</a>
        </div>
    @else
        <table class="tabla">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach($viewData["plantas"] as $planta)
                    <tr>
                        <td>#{{ $planta->getId() }}</td>
                        <td>
                            <a href="{{ route('plantas.show', $planta->getId()) }}">
                                {{ $planta->getNombreListado() }}
                            </a>
                        </td>
                        <td><span class="badge">{{ $planta->getNombreCategoria() }}</span></td>
                        <td><strong style="color: var(--verde);">{{ $planta->getPrecioFormateado() }}</strong></td>
                        <td>
                            @if($planta->getStock() > 0)
                                {{ $planta->getStock() }}
                            @else
                                <span class="badge badge-warn">Sin stock</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
