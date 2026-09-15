<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><title>Crear planta</title></head>
<body>
    <h1>Crear planta</h1>
    <p><a href="{{ route('admin.plantas.index') }}">Volver al listado</a></p>

    <form method="POST" action="{{ route('admin.plantas.store') }}">
        @csrf
        @include('admin.plantas.form')
    </form>
</body>
</html>
