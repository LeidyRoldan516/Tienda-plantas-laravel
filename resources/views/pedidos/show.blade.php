<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pedido #{{ $pedido->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 30px auto; padding: 0 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        button { padding: 10px; cursor: pointer; }
    </style>
</head>
<body>
    <a href="{{ route('pedidos.index') }}">← Mis pedidos</a>
    <h1>Pedido #{{ $pedido->id }}</h1>

    @if (session('mensaje'))
        <p>{{ session('mensaje') }}</p>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    @endif

    <p><strong>Fecha:</strong> {{ $pedido->fecha }}</p>
    <p><strong>Estado:</strong> {{ ucfirst($pedido->estado) }}</p>

    <table>
        <thead>
            <tr>
                <th>Planta</th>
                <th>Cantidad</th>
                <th>Precio unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pedido->items as $item)
                <tr>
                    <td>{{ $item->planta?->nombre ?? 'Planta no disponible' }}</td>
                    <td>{{ $item->cantidad }}</td>
                    <td>$ {{ number_format($item->precio_unitario, 0, ',', '.') }}</td>
                    <td>$ {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Total: $ {{ number_format($pedido->total, 0, ',', '.') }}</h2>

    @if ($pedido->estado === 'pendiente')
        <form action="{{ route('pedidos.cancelar', $pedido) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit">Cancelar pedido</button>
        </form>
    @endif
</body>
</html>
