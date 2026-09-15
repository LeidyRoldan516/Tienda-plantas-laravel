<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi carrito</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 30px auto; padding: 0 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        input { width: 70px; padding: 8px; }
        button { padding: 8px 12px; cursor: pointer; }
        .error { color: #a00; }
    </style>
</head>
<body>
    <a href="{{ route('catalogo.index') }}">← Seguir viendo plantas</a>
    <h1>Mi carrito</h1>

    @if (session('mensaje'))
        <p>{{ session('mensaje') }}</p>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p class="error">{{ $error }}</p>
        @endforeach
    @endif

    @if ($carrito->items->isEmpty())
        <p>Tu carrito está vacío.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Planta</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($carrito->items as $item)
                    <tr>
                        <td>{{ $item->planta->nombre }}</td>
                        <td>$ {{ number_format($item->precio_unitario, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('carrito.actualizar', $item) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="cantidad"
                                       min="1" max="{{ $item->planta->stock }}"
                                       value="{{ $item->cantidad }}">
                                <button type="submit">Actualizar</button>
                            </form>
                        </td>
                        <td>$ {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('carrito.eliminar', $item) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Retirar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h2>Total: $ {{ number_format($total, 0, ',', '.') }}</h2>
        <form action="{{ route('pedidos.crear') }}" method="POST">
    @csrf
    <button type="submit">Confirmar pedido</button>
</form>

<a href="{{ route('pedidos.index') }}">Consultar mis pedidos</a>
    @endif
</body>
</html>
