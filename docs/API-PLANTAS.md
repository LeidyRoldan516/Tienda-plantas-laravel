# API de plantas disponibles


## Propósito

Entregar el catálogo de plantas disponibles al siguiente equipo consumidor.
Solo se incluyen plantas con `stock > 0`, ordenadas por `id` ascendente.
Se entrega el listado completo, sin paginación; `meta.total` cuenta los elementos
de esta respuesta.

## Solicitud

- Método y endpoint: `GET /api/plantas-disponibles`
- Autenticación: ninguna. No requiere login, token, cookie ni sesión.
- Parámetros: ninguno.
- URL local: http://127.0.0.1:8000/api/plantas-disponibles
- La URL pública se agregará después del despliegue.

```sh
curl -i http://127.0.0.1:8000/api/plantas-disponibles
```

## Respuesta

`Content-Type: application/json`

```json
{
  "data": [
    {
      "id": 1,
      "nombre": "Lavanda",
      "categoria": "Aromáticas",
      "descripcion": "Planta aromática para el jardín.",
      "precio": 25000,
      "stock": 10,
      "imagen_url": "https://example.com/lavanda.jpg",
      "detalle_url": "http://127.0.0.1:8000/catalogo/1"
    }
  ],
  "meta": {
    "total": 1,
    "proveedor": "El Rincón de las Plantas"
  }
}
```

| Campo | Tipo JSON | Descripción |
| --- | --- | --- |
| `data` | array | Plantas disponibles; siempre un arreglo. |
| `data[].id` | integer | Identificador público de la planta. |
| `data[].nombre` | string | Nombre guardado de la planta. |
| `data[].categoria` | string o null | Nombre de la categoría relacionada; null si no existe relación. |
| `data[].descripcion` | string | Descripción guardada de la planta. |
| `data[].precio` | integer | Precio en pesos, sin formato monetario. |
| `data[].stock` | integer | Unidades disponibles, siempre mayores que cero. |
| `data[].imagen_url` | string o null | URL guardada, sin modificar; null si no tiene imagen. |
| `data[].detalle_url` | string | URL absoluta generada mediante la ruta `catalogo.show`; el dominio depende del servidor que atiende la solicitud. |
| `meta` | object | Información del listado. |
| `meta.total` | integer | Cantidad exacta de elementos entregados en `data`. |
| `meta.proveedor` | string | Valor fijo: `El Rincón de las Plantas`. |

Sin plantas disponibles, se responde igualmente con HTTP 200:

```json
{
  "data": [],
  "meta": {
    "total": 0,
    "proveedor": "El Rincón de las Plantas"
  }
}
```

No se exponen timestamps, claves foráneas, relaciones completas ni datos de usuarios.
Los textos del catálogo se entregan tal como están guardados, sin traducción.

## Códigos de respuesta

| Código | Significado |
| --- | --- |
| `200 OK` | Listado obtenido, incluido el caso vacío. |
| `405 Method Not Allowed` | Método no admitido, por ejemplo POST. |
| `500 Internal Server Error` | Fallo inesperado del servidor o de acceso a la base de datos. |

El contrato `data`/`meta` corresponde a respuestas exitosas. Los errores utilizan
el manejo estándar de Laravel; no se define aquí un cuerpo de error propio.

## Compatibilidad con el modelo actual

El esquema actual guarda el precio como entero y exige una categoría mediante
una clave foránea no nullable. Por eso el precio de la API es entero en pesos.
El Resource contempla `categoria: null` de forma defensiva si la relación está
ausente; no se cambia el esquema ni se crean registros inválidos para probarlo.

Las categorías se cargan anticipadamente para evitar consultas N+1.

## Verificación

```sh
php artisan route:list --path=api/plantas-disponibles
php artisan test --filter=PlantasDisponiblesTest
php artisan test
git diff --check
```
