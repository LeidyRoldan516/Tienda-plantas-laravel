<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>Editar planta</title></head>
<body>
    <h1>Editar: {{ $planta->nombre }}</h1>
    <p><a href="{{ route('admin.plantas.index') }}">Volver al listado</a></p>

    <form method="POST" action="{{ route('admin.plantas.update', $planta) }}">
        @csrf
        @method('PUT')
        @include('admin.plantas.form')
    </form>
</body>
</html>
