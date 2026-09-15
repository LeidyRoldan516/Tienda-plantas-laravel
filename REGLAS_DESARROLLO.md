# Reglas de desarrollo

Este documento define cómo se escribe el código en este proyecto. Toda funcionalidad nueva debe seguir estas reglas para mantener el mismo estilo (acuerdos del curso y recomendaciones del taller 1).

**Stack:** Laravel 12, PHP 8.2+, SQLite. No se usa Lang ni archivos de traducción.

---

## 1. Principios

1. La vista no accede a atributos del modelo. Solo usa getters.
2. El controlador no pinta HTML. Prepara datos en `$viewData` y los pasa a la vista.
3. La lógica de presentación (mensajes al lado del título, valores transformados) vive en el modelo, no en Blade.
4. Los textos de interfaz van en español, escritos directo en la vista. No se usa `__()`, `Lang` ni `lang/`.
5. La apariencia visual no es prioridad. Sí lo son nombres claros, capas separadas y validación.
6. No se configuran motores de base de datos externos. Se usa SQLite por defecto.

---

## 2. Estructura de archivos

| Tipo | Ubicación | Ejemplo |
|------|-----------|---------|
| Rutas web | `routes/web.php` | — |
| Controlador | `app/Http/Controllers/` | `TicketController.php` |
| Modelo | `app/Models/` | `Ticket.php` |
| Migración | `database/migrations/` | `*_create_tickets_table.php` |
| Vista de inicio | `resources/views/` | `home.blade.php` |
| Vistas de un recurso | `resources/views/{recurso}/` | `tickets/create.blade.php` |

Nombres de clase: PascalCase. Un recurso = un controlador y un modelo.

Las vistas de un mismo recurso se agrupan en carpeta (`tickets/create`, `tickets/index`, `tickets/estadisticas`). La vista inicial queda en la raíz de `views` como `home`.

---

## 3. Rutas

Todas las rutas web se declaran en `routes/web.php`.

### Imports

Importar cada controlador usado y el facade `Route`:

```php
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
```

### Forma de la ruta

```php
Route::{metodo}('{uri}', [{Controlador}::class, '{accion}'])->name('{nombre}');
```

- El primer argumento es la URI.
- El segundo es `[Clase::class, 'metodo']`. No usar strings `'TicketController@index'`.
- Toda ruta debe tener `->name(...)`.

### URI

- Inicio: `/`.
- Recurso en plural e inglés: `/tickets`.
- Acciones visibles al usuario en español: `/tickets/registrar`, `/tickets/estadisticas`.
- Persistencia con verbo en inglés: `POST /tickets/save`.

### Nombres de ruta

Patrón `{recurso}.{accion}` en minúsculas:

| URI | Método HTTP | Nombre | Controlador |
|-----|-------------|--------|-------------|
| `/` | GET | `home` | `HomeController@index` |
| `/tickets/registrar` | GET | `tickets.create` | `TicketController@create` |
| `/tickets` | GET | `tickets.index` | `TicketController@index` |
| `/tickets/estadisticas` | GET | `tickets.estadisticas` | `TicketController@estadisticas` |
| `/tickets/save` | POST | `tickets.save` | `TicketController@save` |

En las vistas **siempre** se enlaza con `route('nombre')`, nunca con la URI escrita a mano.

```blade
<a href="{{ route('tickets.create') }}">Registrar tickets</a>
<form method="POST" action="{{ route('tickets.save') }}">
```

### Orden

Registrar primero las URIs estáticas (`/tickets/registrar`, `/tickets/estadisticas`) y después las genéricas si en el futuro existiera `/tickets/{id}`, para que no se confundan.

No usar `Route::resource` en este proyecto: las URIs y nombres se declaran uno a uno.

---

## 4. Controladores

Namespace: `App\Http\Controllers`. Extienden `Controller`.

### Responsabilidades

- Recibir la petición.
- Validar (en el método que guarda).
- Consultar o persistir con el modelo.
- Armar `$viewData` y devolver `view(...)->with("viewData", $viewData)`.

No poner HTML en el controlador. No acceder a `$ticket->titulo` en el controlador cuando exista getter: usar `getTitulo()`, `getPrioridad()`, etc.

### Patrón `$viewData`

Cuando la vista necesita datos:

```php
$viewData = [];
$viewData["tickets"] = Ticket::orderBy('tiempo_estimado', 'desc')->get();

return view('tickets.index')->with("viewData", $viewData);
```

Reglas:

- Inicializar `$viewData = [];`.
- Las claves van en **comillas dobles** y camelCase o español corto (`"tickets"`, `"porPrioridad"`, `"tiempoMaximo"`).
- Pasar el arreglo completo con `->with("viewData", $viewData)`.
- No usar `compact()`, `view(..., ['tickets' => ...])` ni `with('tickets', $tickets)`.

Si la vista no necesita datos (formulario vacío o home), se puede devolver solo `view('tickets.create')` o `view('home')`.

### Nombres de métodos

| Método | Uso |
|--------|-----|
| `index` | Listar |
| `create` | Mostrar formulario de alta |
| `save` | Persistir (POST) |
| `estadisticas` | Consultas agregadas |

El método que guarda se llama `save`, no `store`.

### Validación y persistencia

La validación va en el controlador, en el método `save`, con `$request->validate([...])`.

```php
public function save(Request $request): RedirectResponse
{
    $request->validate([
        "titulo" => "required|string|max:255",
        "tiempo_estimado" => "required|integer|min:1",
        "prioridad" => "required|string|in:alta,media,baja",
    ]);

    $data = $request->only([
        "titulo",
        "tiempo_estimado",
        "prioridad",
    ]);

    Ticket::create($data);

    return back()->with("success", "Elemento creado satisfactoriamente");
}
```

- Las claves de validación coinciden con el `name` del input y con la columna.
- Después de validar, tomar solo esos campos con `$request->only([...])`.
- Crear con `Modelo::create($data)` (mass assignment vía `$fillable`).
- Tras guardar, `return back()`.
- Tipos de retorno: se pueden anotar (`RedirectResponse`, `View`). No son obligatorios en métodos que solo devuelven una vista.

No instanciar el modelo a mano ni asignar atributo por atributo en el controlador si `create` + `$fillable` basta.

### Consultas

- Ordenar en Eloquent: `Ticket::orderBy('tiempo_estimado', 'desc')->get()`.
- Agregaciones sobre la colección ya cargada, usando getters:

```php
$tickets = Ticket::all();
$viewData["porPrioridad"] = $tickets
    ->groupBy(fn ($ticket) => $ticket->getPrioridad())
    ->map->count();
$viewData["tiempoMaximo"] = $tickets->max(fn ($ticket) => $ticket->getTiempo());
```

---

## 5. Modelos

Namespace: `App\Models`. Extienden `Illuminate\Database\Eloquent\Model`.

### Propiedades de clase

```php
public $timestamps = false;
protected $fillable = ['titulo', 'tiempo_estimado', 'prioridad'];
```

- `$timestamps = false` si la migración no tiene `created_at` / `updated_at`.
- `$fillable` lista **todas** las columnas que pueden llegar del formulario. Nunca incluir `id`.

### Acceso a datos

Getters y setters leen y escriben `$this->attributes['columna']`. No usar `$this->titulo` dentro del modelo.

### Getters

```php
public function getId(): int
{
    return $this->attributes['id'];
}

public function getTitulo(): string
{
    return $this->attributes['titulo'];
}
```

- Nombre: `get` + atributo en PascalCase: `getTitulo`, `getPrioridad`, `getId`.
- El getter del id siempre existe.
- Tipo de retorno explícito (`int`, `string`).
- Devuelven el valor crudo de base de datos.

Si el nombre del método no coincide letra por letra con la columna (por brevedad), se documenta con el atributo real:

```php
public function getTiempo(): int
{
    return $this->attributes['tiempo_estimado'];
}
```

### Setters

```php
public function setTitulo(string $titulo): void
{
    $this->attributes['titulo'] = $titulo;
}

public function setTiempo(int $tiempo_estimado): void
{
    $this->attributes['tiempo_estimado'] = $tiempo_estimado;
}
```

- Nombre: `set` + mismo PascalCase que el getter (`setTitulo` ↔ `getTitulo`).
- Un parámetro tipado. Retorno `void`.
- Asignan `$this->attributes['columna']`.
- No hace falta setter de `id`.

### Getters de presentación

Cuando el listado o una vista necesita un valor **transformado**, se crea otro getter, no se altera el getter crudo.

```php
public function getTituloListado(): string
{
    $titulo = $this->getTitulo();
    if ($this->getPrioridad() === 'alta') {
        return $titulo . ' (Atención prioritaria)';
    }
    return $titulo;
}

public function getTiempoListado(): int
{
    $tiempo = $this->getTiempo();
    if ($this->getPrioridad() === 'baja') {
        return $tiempo * 2;
    }
    return $tiempo;
}
```

- Sufijo que indica el uso: `getTituloListado`, `getTiempoListado`.
- Internamente llaman a los getters crudos, no a `attributes` de nuevo.
- Las estadísticas y el orden en BD usan los getters crudos (`getTiempo()`, `getPrioridad()`).

---

## 6. Migraciones

Una migración por tabla. Nombre generado por Artisan: `create_{tabla}_table`.

```php
public function up(): void
{
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        $table->string('titulo');
        $table->integer('tiempo_estimado');
        $table->string('prioridad');
    });
}

public function down(): void
{
    Schema::dropIfExists('tickets');
}
```

- Tabla en plural, snake_case: `tickets`.
- Columnas en snake_case, en español: `titulo`, `tiempo_estimado`, `prioridad`.
- `id()` para la clave primaria.
- Tipos: `string` para texto y enumeraciones simples; `integer` para cantidades.
- No agregar `$table->timestamps()` si el modelo tiene `$timestamps = false`.
- `down()` elimina la tabla con `Schema::dropIfExists`.

Base de datos: `DB_CONNECTION=sqlite` en `.env`. No cambiar a MySQL/Postgres salvo que se pida.

---

## 7. Vistas Blade

### Documento

Cada vista es un HTML completo (no hay layout compartido obligatorio):

```blade
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>...</title>
</head>
<body>
    ...
</body>
</html>
```

- `lang="es"` y `charset="utf-8"`.
- Título en español, acorde a la pantalla.

### Datos

En listados y estadísticas se lee **solo** `$viewData["clave"]`:

```blade
@forelse($viewData["tickets"] as $ticket)
    <td>{{ $ticket->getId() }}</td>
    <td>{{ $ticket->getTituloListado() }}</td>
    <td>{{ $ticket->getTiempoListado() }}</td>
    <td>{{ $ticket->getPrioridad() }}</td>
@empty
    ...
@endforelse
```

Prohibido en vistas:

- `$ticket->titulo`, `$ticket->attributes['titulo']`, `$ticket['titulo']`
- `{{ __("...") }}` o `@lang`
- URLs fijas en `href` / `action` (usar `route()`)

### Enlaces

- Navegación principal y “Volver al inicio” con `route('home')`, `route('tickets.create')`, etc.
- Textos de enlace en español, como en el enunciado: “Registrar tickets”, “Listar tickets”, “Estadísticas de tickets”.

### Listados

- Tabla HTML con encabezados en español.
- `@forelse` / `@empty` para el caso sin registros.
- Condiciones de negocio (prioridad alta/baja) **no** se resuelven con `@if` en la vista si ya existen getters de listado.

### Estadísticas

- Recorrer colecciones con `@foreach($viewData["porPrioridad"] as $prioridad => $total)`.
- Vacío: `@if($viewData["porPrioridad"]->isEmpty())` o `is_null($viewData["tiempoMaximo"])`.

---

## 8. Formularios

```blade
<form method="POST" action="{{ route('tickets.save') }}">
    @csrf
    ...
</form>
```

- Método `POST` y `@csrf` siempre.
- Cada control tiene `name` igual a la columna: `titulo`, `tiempo_estimado`, `prioridad`.
- `id` del input igual al `name`. El `label` usa `for` con ese `id`.
- Repoblar con `old('campo')`.
- Prioridad (y valores fijos similares): `<select>`, no texto libre. Incluir opción vacía (“Seleccione una prioridad”) y `required` en la vista.
- Conservar la opción elegida con `@selected(old('prioridad') === 'alta')`.
- Campos numéricos: `type="number"`.
- Texto: `type="text"`.

La prioridad se valida en la vista con el select y **también** en el controlador (`in:alta,media,baja`). Los valores persistidos van en minúsculas: `alta`, `media`, `baja`.

---

## 9. Nombres e idioma

| Qué | Idioma | Estilo | Ejemplo |
|-----|--------|--------|---------|
| Clases PHP | Inglés | PascalCase | `TicketController` |
| Métodos PHP | Inglés o español del dominio | camelCase | `create`, `save`, `estadisticas` |
| Getters/setters | Español del atributo | get/set + PascalCase | `getTitulo`, `setPrioridad` |
| Columnas y tablas | Español | snake_case | `tiempo_estimado` |
| Claves de `$viewData` | Español o inglés corto | camelCase entre comillas dobles | `"porPrioridad"` |
| Nombres de ruta | Inglés + acción | `recurso.accion` | `tickets.create` |
| URIs de pantalla | Recurso EN + acción ES | kebab-case | `/tickets/registrar` |
| Textos de UI | Español | Frases literales | `Listar tickets` |

Cadenas de reglas de validación y claves de `only()`: comillas dobles, iguales a la columna.

No importar clases que no se usen.

---

## 10. Capas: qué va en cada sitio

| Dato o regla | Dónde |
|--------------|--------|
| Columnas de la tabla | Migración |
| Mass assignment | `$fillable` del modelo |
| Lectura/escritura de un atributo | Getter / setter |
| Valor mostrado distinto al de BD | Getter de presentación (`getTituloListado`) |
| Orden de un listado | Consulta Eloquent en el controlador |
| Conteos y máximos | Controlador (colección + getters) |
| Validación de entrada | `save()` del controlador + `select`/`required` en la vista |
| HTML y textos | Vista Blade |
| Navegación | `routes/web.php` + `route()` en vistas |

---

## 11. Checklist para código nuevo

1. Migración con `id`, columnas snake_case en español, `up` y `down`. ¿Hace falta timestamps? Si no, no agregarlos y poner `$timestamps = false`.
2. Modelo con `$fillable`, getter/setter por atributo (excepto `setId`) y getters de presentación si la vista transforma el valor.
3. Ruta GET/POST con `->name()`, controlador `[Clase::class, 'metodo']`.
4. Controlador: `$viewData = []`, claves entre comillas dobles, `->with("viewData", $viewData)`.
5. `save`: `validate`, `only`, `create`, `back()`.
6. Vista: `route()`, getters, `$viewData["..."]`, sin Lang.
7. Formulario: `@csrf`, `name` = columna, `old()`, select para enumeraciones.
)