# Guía de estilo de programación

Esta guía define las convenciones que deben seguir todos los integrantes durante el desarrollo de la tienda de plantas. Su propósito es mantener un código organizado, uniforme y fácil de comprender.

## 1. Laravel Pint

El proyecto utilizará Laravel Pint para revisar y corregir automáticamente el formato del código PHP.

Antes de crear un commit, cada integrante debe ejecutar el siguiente comando desde la carpeta principal del proyecto:

```bash
php vendor/bin/pint
```

Para comprobar el formato sin modificar los archivos, se puede utilizar:

```bash
php vendor/bin/pint --test
```

Si Laravel Pint encuentra problemas de formato, estos deben corregirse antes de subir los cambios al repositorio.

## 2. Convenciones de nombres

### Clases y modelos

Las clases se escribirán en singular y utilizando PascalCase.

Ejemplos:

```text
Planta
Cliente
Pedido
ItemPedido
PerfilPreferencias
Recomendacion
```

Los modelos se almacenarán en:

```text
app/Models
```

### Controladores

Los controladores utilizarán PascalCase y terminarán con la palabra `Controller`.

Ejemplos:

```text
PlantaController
PedidoController
CarritoController
RecomendacionController
```

Los controladores se almacenarán en:

```text
app/Http/Controllers
```

Cuando existan controladores diferentes para el administrador y el cliente, deberán organizarse en carpetas separadas.

Ejemplo:

```text
app/Http/Controllers/Admin
app/Http/Controllers/Cliente
```

### Métodos y variables

Los métodos y variables utilizarán camelCase y tendrán nombres descriptivos.

Ejemplos:

```php
$fechaPedido
$cantidadDisponible
$perfilPreferencias

buscarPorNombre()
agregarItem()
actualizarCantidad()
generarRecomendacion()
```

No se utilizarán nombres poco descriptivos como:

```php
$x
$dato
$valor1
$prueba
```

### Tablas y columnas

Las tablas de la base de datos se nombrarán en plural y utilizando snake_case.

Ejemplos:

```text
plantas
categorias
pedidos
items_pedido
perfiles_preferencias
recomendaciones
```

Las llaves foráneas también utilizarán snake_case.

Ejemplos:

```text
cliente_id
planta_id
pedido_id
categoria_id
```

### Vistas

Las vistas utilizarán nombres en minúscula y la extensión `.blade.php`.

Ejemplos:

```text
index.blade.php
create.blade.php
edit.blade.php
show.blade.php
```

Las vistas se organizarán en carpetas según la sección y el recurso al que pertenecen.

Ejemplo:

```text
resources/views/admin/plantas
resources/views/admin/categorias
resources/views/cliente/catalogo
resources/views/cliente/pedidos
```

### Rutas

Las rutas tendrán nombres descriptivos y consistentes.

Ejemplos:

```php
plantas.index
plantas.create
plantas.store
plantas.edit
plantas.update
plantas.destroy
pedidos.index
pedidos.show
```

Las rutas administrativas utilizarán el prefijo `/admin`.

## 3. Sangría y formato

* Se utilizarán cuatro espacios para la sangría.
* No se utilizarán tabulaciones manuales.
* Cada instrucción PHP terminará con punto y coma.
* Se dejará una línea en blanco entre bloques de código diferentes.
* Se evitarán líneas excesivamente largas.
* Se eliminarán variables, métodos e importaciones que no se utilicen.
* No se conservará código comentado que ya no sea necesario.
* Se mantendrá el formato aplicado por Laravel Pint.

## 4. Autoría de los archivos

Como lo exige la entrega, cada archivo creado o modificado debe indicar su autor en la parte superior.

En los archivos PHP se utilizará:

```php
<?php

/**
 * Autor: Nombre completo del integrante
 */
```

En las vistas Blade se utilizará:

```blade
{{-- Autor: Nombre completo del integrante --}}
```

Si varias personas realizan cambios importantes en el mismo archivo, se podrán registrar varios autores.

Ejemplo:

```php
/**
 * Autores:
 * - Nombre del primer integrante
 * - Nombre del segundo integrante
 */
```

## 5. Comentarios

Los comentarios se utilizarán para explicar decisiones importantes o comportamientos que no sean evidentes.

Los comentarios no deben repetir literalmente lo que hace el código.

Ejemplo que debe evitarse:

```php
// Incrementa la cantidad en uno
$cantidad++;
```

Ejemplo apropiado:

```php
// El pedido solo puede cancelarse mientras permanezca pendiente.
```

## 6. Textos de la aplicación

Todos los textos visibles para el usuario deben almacenarse en:

```text
resources/lang
```

En las vistas se utilizarán las funciones de traducción de Laravel.

Ejemplo:

```blade
{{ __('messages.catalogo') }}
```

No se deben escribir directamente en las vistas textos que puedan necesitar traducción posteriormente.

## 7. Controladores

* Los controladores recibirán las solicitudes del usuario y coordinarán la respuesta.
* No se utilizará `echo` dentro de los controladores.
* La validación de formularios debe realizarse antes de guardar información.
* Los controladores no incluirán consultas innecesariamente complejas.
* Los controladores administrativos estarán separados de los controladores del cliente.
* Cada método debe tener una responsabilidad clara.

## 8. Modelos

* Cada modelo representará una entidad del dominio.
* Las relaciones entre modelos deben corresponder con el diagrama de clases.
* Se definirán correctamente las relaciones de Eloquent.
* Se utilizará `$fillable` para indicar los campos que pueden asignarse.
* La lógica propia de una entidad debe ubicarse en su modelo cuando corresponda.
* No se duplicará la misma lógica en varios modelos.

## 9. Migraciones

* Todos los cambios en la base de datos se realizarán mediante migraciones.
* No se modificarán manualmente las tablas sin crear la migración correspondiente.
* Las migraciones tendrán nombres descriptivos.
* Las llaves foráneas y sus restricciones deberán quedar definidas.
* Una migración compartida con el equipo no debe modificarse si ya fue utilizada; debe crearse una nueva migración para aplicar el cambio.

## 10. Vistas Blade

* Todas las vistas utilizarán Blade.
* Las vistas deben extender un layout general.
* No se utilizará `echo` para mostrar información.
* Se utilizará la sintaxis de Blade para condiciones, ciclos y variables.
* Las vistas administrativas serán diferentes de las vistas del cliente.
* No se incluirá lógica compleja de negocio dentro de las vistas.
* Se reutilizarán componentes o parciales cuando haya contenido repetido.

## 11. Commits

Cada commit debe corresponder a un cambio específico y utilizar un mensaje descriptivo.

Ejemplos apropiados:

```text
Crear modelo y migración de plantas
Agregar búsqueda de plantas por nombre
Implementar relación entre pedido e item
Actualizar diagrama de clases
Corregir validación del formulario de categorías
```

No se utilizarán mensajes imprecisos como:

```text
Cambios
Arreglos
Prueba
Final
Cosas nuevas
```

## 12. Validación antes de subir cambios

Antes de realizar un push, cada integrante debe:

1. Verificar que se encuentra en su propia rama.
2. Actualizar su rama con los cambios necesarios.
3. Ejecutar Laravel Pint.
4. Probar la funcionalidad desarrollada.
5. Revisar los archivos que incluirá en el commit.
6. Crear un commit con un mensaje descriptivo.
7. Subir únicamente los archivos relacionados con su tarea.
8. Crear una solicitud de extracción para que los cambios sean revisados antes de integrarlos en `main`.

## 13. Responsabilidad del equipo

Cada integrante es responsable de verificar que su código cumpla esta guía. Los cambios que no respeten las convenciones podrán ser corregidos o devueltos antes de incorporarse a la rama principal.

---

[[Volver a la página principal](https://chatgpt.com/g/g-p-6a5ec7ae6fd48191bf649982b0eda594-daya/c/Home)](Home)