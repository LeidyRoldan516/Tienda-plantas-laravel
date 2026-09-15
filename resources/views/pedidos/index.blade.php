<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mis pedidos</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 30px auto; padding: 0 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
    </style>
</head>
<body>
    <a href="{{ route('catalogo.index') }}">← Volver al catálogo</a>
    <h1>Mis pedidos</h1>

    @if ($pedidos->isEmpty())
        <p>Todavía no has realizado pedidos.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Detalle</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $pedido)
                    <tr>
                        <td>#{{ $pedido->id }}</td>
                        <td>{{ $pedido->fecha }}</td>
                        <td>{{ ucfirst($pedido->estado) }}</td>
                        <td>$ {{ number_format($pedido->total, 0, ',', '.') }}</td>
                        <td><a href="{{ route('pedidos.show', $pedido) }}">Ver pedido</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 24px">{{ $pedidos->links() }}</div>
    @endif
</body>
</html>
