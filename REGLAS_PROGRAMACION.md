# Reglas de programación

Las siguientes reglas son obligatorias para todos los integrantes del proyecto Tienda de plantas. Su finalidad es conservar una arquitectura organizada, facilitar la integración del trabajo y evitar errores durante el desarrollo.

## 1. Reglas generales

* El proyecto debe desarrollarse con Laravel 12 y MySQL.
* Todo archivo creado o modificado debe incluir el nombre de su autor.
* No se debe duplicar código que pueda reutilizarse.
* No se subirán archivos con contraseñas, claves privadas o credenciales.
* El archivo `.env` nunca se subirá al repositorio.
* No se modificará directamente la rama `main` para desarrollar funcionalidades.
* Cada integrante trabajará únicamente en las tareas que tenga asignadas.
* Antes de integrar cambios, el código debe probarse y revisarse.
* Todas las clases incluidas en el diagrama de clases deberán implementarse durante el desarrollo del proyecto.

## 2. Reglas para los modelos

* Cada modelo representará una entidad del dominio de la aplicación.
* Los modelos se almacenarán en `app/Models`.
* Cada tabla principal de la base de datos tendrá su modelo correspondiente.
* Los campos permitidos para asignación masiva se definirán mediante `$fillable`.
* Las relaciones entre modelos se implementarán con Eloquent.
* Cada asociación del diagrama de clases deberá representarse en los dos modelos relacionados cuando corresponda.
* Se utilizarán métodos como `hasOne`, `hasMany`, `belongsTo` y `belongsToMany` según la relación.
* No se utilizarán consultas SQL escritas directamente si Eloquent permite realizar la operación de forma clara.
* La lógica propia de una entidad deberá ubicarse en su modelo cuando corresponda.
* Los modelos no deberán mostrar vistas ni redirigir al usuario.

Ejemplo de una relación en ambos modelos:

```php
// En Pedido
public function items()
{
    return $this->hasMany(ItemPedido::class);
}

// En ItemPedido
public function pedido()
{
    return $this->belongsTo(Pedido::class);
}
```

## 3. Reglas para los controladores

* Todos los controladores se almacenarán en `app/Http/Controllers`.
* Toda ruta que procese una acción del sistema estará asociada con un controlador.
* Nunca se utilizará `echo` dentro de un controlador.
* Los controladores no contendrán código HTML.
* Cada método del controlador tendrá una responsabilidad clara.
* Los datos recibidos mediante formularios deberán validarse antes de utilizarse.
* Los controladores devolverán vistas, redirecciones o respuestas apropiadas.
* Los controladores administrativos estarán separados de los controladores del cliente.
* La lógica de conexión con la inteligencia artificial no se escribirá directamente dentro del controlador; se ubicará en una clase de servicio.
* No se repetirán las mismas validaciones o consultas en varios controladores.

## 4. Reglas para las vistas

* Todas las vistas utilizarán Blade y tendrán la extensión `.blade.php`.
* Las vistas estarán almacenadas en `resources/views`.
* Toda vista deberá extender un layout general o utilizar los componentes definidos por el proyecto.
* Las vistas administrativas serán independientes de las vistas del cliente.
* Nunca se utilizará `echo` para mostrar información.
* No se abrirán y cerrarán bloques de PHP manualmente dentro de las vistas.
* Se utilizarán las instrucciones de Blade como `@if`, `@foreach` y `@auth`.
* Las vistas no realizarán consultas directas a la base de datos.
* No se incluirá lógica compleja de negocio dentro de las vistas.
* Los formularios deberán incluir protección CSRF mediante `@csrf`.
* Los formularios para actualizar o eliminar información deberán indicar el método HTTP correspondiente.
* Los errores de validación deberán mostrarse de forma comprensible.
* Los componentes visuales repetidos deberán reutilizarse.

## 5. Reglas para las rutas

* Las rutas web se definirán en `routes/web.php`.
* Toda ruta deberá tener un nombre descriptivo.
* Las rutas administrativas utilizarán el prefijo `/admin`.
* Las rutas administrativas estarán protegidas para permitir el acceso únicamente a administradores.
* Las rutas que requieran una cuenta estarán protegidas mediante autenticación.
* Se utilizarán los métodos HTTP apropiados: `GET`, `POST`, `PUT`, `PATCH` y `DELETE`.
* No se utilizarán funciones anónimas en las rutas para implementar funcionalidades principales.
* Las rutas del cliente y del administrador deberán mantenerse organizadas y diferenciadas.
* No se repetirán rutas con el mismo nombre o propósito.

Ejemplo:

```php
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('plantas', PlantaController::class);
    });
```

## 6. Reglas para las migraciones

* Todos los cambios en la estructura de la base de datos se realizarán mediante migraciones.
* No se crearán o modificarán tablas manualmente sin registrar el cambio en una migración.
* Cada migración tendrá un nombre descriptivo.
* Todas las tablas deberán tener una llave primaria.
* Las relaciones deberán incluir las llaves foráneas correspondientes.
* Se definirán las restricciones necesarias para conservar la integridad de los datos.
* El método `down()` deberá permitir revertir la migración.
* No se modificará una migración que ya haya sido compartida y ejecutada por el equipo; se creará una nueva para aplicar el cambio.
* Antes de compartir una migración, se comprobará que pueda ejecutarse y revertirse correctamente.

## 7. Reglas para la validación

* Todo formulario deberá validar la información recibida.
* Los campos obligatorios deberán marcarse como requeridos.
* Los precios y cantidades deberán validar que sean valores numéricos válidos.
* Los correos electrónicos deberán validar su formato.
* No se confiará únicamente en las validaciones realizadas en el navegador.
* Los mensajes de error deberán ser claros para el usuario.
* No se guardará información si la validación falla.

## 8. Reglas para autenticación y permisos

* La aplicación tendrá inicio de sesión funcional.
* El sistema diferenciará entre clientes y administradores.
* Un cliente no podrá acceder a las rutas del panel administrativo.
* Un usuario no autenticado no podrá realizar acciones que requieran una cuenta.
* Las contraseñas nunca se almacenarán como texto sin protección.
* No se expondrá información privada de los usuarios.
* Los permisos deberán comprobarse en el servidor y no solamente ocultando botones en la vista.

## 9. Reglas para la sección administrativa

* El panel administrativo tendrá vistas y controladores independientes.
* Se implementarán como mínimo dos CRUD completos.
* Los CRUD seleccionados inicialmente serán plantas y categorías.
* El administrador podrá crear, consultar, modificar y eliminar o desactivar registros.
* Antes de eliminar información relacionada con otros registros, se deberán revisar sus dependencias.
* Las acciones administrativas deberán mostrar mensajes de confirmación o resultado.
* La apariencia del panel administrativo será diferente de la sección del cliente.

## 10. Reglas para la sección del cliente

* El cliente podrá consultar las plantas, pero no podrá crearlas, modificarlas ni eliminarlas.
* El cliente podrá buscar plantas en el catálogo.
* El cliente podrá agregar plantas a su carrito y modificar sus cantidades.
* El cliente podrá crear y consultar sus propios pedidos.
* Un cliente no podrá consultar o modificar pedidos pertenecientes a otra persona.
* Las plantas sin disponibilidad no podrán agregarse al carrito.
* Las acciones del cliente deberán mostrar mensajes claros de confirmación o error.

## 11. Reglas para el carrito y los pedidos

* Cada cliente tendrá únicamente un carrito activo.
* Un item del carrito estará relacionado con una planta existente.
* La cantidad agregada deberá ser mayor que cero.
* No se podrá agregar una cantidad superior al inventario disponible.
* El total del carrito deberá calcularse con base en sus items.
* Al finalizar el carrito se creará un pedido con sus respectivos items.
* Cada item del pedido conservará el precio unitario utilizado al momento de confirmar el pedido.
* Los estados del pedido deberán estar previamente definidos.
* Un pedido solamente podrá cancelarse cuando su estado lo permita.
* Los pagos podrán conservarse dentro del diseño general del proyecto, aunque su implementación se realice en una etapa posterior.

## 12. Reglas para las recomendaciones con inteligencia artificial

* La inteligencia artificial se implementará como un servicio externo.
* El cliente deberá completar sus preferencias antes de solicitar una recomendación.
* El servicio analizará factores como experiencia, espacio, iluminación, tiempo disponible y presencia de mascotas.
* La inteligencia artificial únicamente podrá recomendar plantas existentes en el catálogo.
* La respuesta de la inteligencia artificial deberá validarse antes de mostrarse al cliente.
* El sistema no permitirá que una recomendación invente plantas o productos inexistentes.
* La conexión con la inteligencia artificial se implementará en una clase de servicio.
* Las credenciales del servicio externo se guardarán en variables de entorno.
* Ninguna clave de acceso se incluirá directamente en el código o en GitHub.
* Si el servicio externo no responde, la aplicación mostrará un mensaje comprensible y continuará funcionando.

## 13. Reglas para los textos e idiomas

* Todos los textos visibles de la aplicación se almacenarán en `resources/lang`.
* Las vistas utilizarán las funciones de traducción de Laravel.
* El idioma principal será español.
* Si el tiempo lo permite, se añadirá un segundo idioma.
* No se repetirán manualmente los mismos textos en varias vistas.

## 14. Reglas para Git y GitHub

* La rama `main` conservará únicamente versiones estables del proyecto.
* Cada integrante trabajará en una rama independiente para su tarea.
* Los nombres de las ramas deberán describir el trabajo realizado.

Ejemplos:

```text
feature/catalogo
feature/pedidos
feature/recomendaciones
docs/diagramas
fix/validacion-plantas
```

* No se realizarán cambios directamente sobre `main`.
* Cada commit debe corresponder a un cambio específico.
* Antes de subir cambios se debe ejecutar Laravel Pint.
* No se subirán las carpetas o archivos excluidos mediante `.gitignore`.
* Los cambios se integrarán mediante una solicitud de extracción o Pull Request.
* La persona encargada de revisar deberá comprobar que el código cumpla las reglas y no afecte otras funcionalidades.
* Si un cambio incumple las reglas, deberá corregirse antes de integrarse.
* Las tareas se registrarán y actualizarán en GitHub Projects.
* Cada integrante moverá sus tareas entre `Hacer`, `En curso` y `Hecho` según su estado real.

## 15. Revisión antes de integrar cambios

Antes de aprobar un Pull Request se debe comprobar:

1. Que la tarea esté identificada.
2. Que el código tenga registrado su autor.
3. Que Laravel Pint no reporte problemas.
4. Que las validaciones funcionen.
5. Que las relaciones coincidan con el diagrama de clases.
6. Que las migraciones se ejecuten correctamente.
7. Que no se hayan incluido credenciales o archivos privados.
8. Que la funcionalidad haya sido probada.
9. Que no se haya duplicado código innecesariamente.
10. Que la documentación se actualice cuando sea necesario.

---

[[Volver a la página principal](https://chatgpt.com/g/g-p-6a5ec7ae6fd48191bf649982b0eda594-daya/c/Home)](Home)