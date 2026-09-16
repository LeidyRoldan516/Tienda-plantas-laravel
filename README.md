# Tienda de plantas

Aplicación web para la consulta, recomendación y compra de plantas, desarrollada con Laravel 12 y MySQL.

## Descripción

Tienda de plantas permitirá que los clientes consulten el catálogo, busquen plantas, administren un carrito de compras y creen pedidos.

La aplicación incluye recomendaciones personalizadas mediante Google Gemini. El cliente registra sus preferencias (experiencia, espacio, iluminación, tiempo de cuidado y mascotas) y el sistema sugiere plantas existentes del catálogo.

El sistema tiene dos secciones principales:

- Sección para clientes.
- Panel de administración.

## Funcionalidades de la primera entrega

1. Consulta y búsqueda de plantas.
2. Administración del carrito de compras.
3. Creación y consulta de pedidos.
4. Recomendaciones personalizadas mediante inteligencia artificial (Gemini).

La cuarta funcionalidad es la funcionalidad diferenciadora del proyecto.

## Alcance de los pagos

Las clases relacionadas con pagos se conservarán en el diseño general del proyecto porque forman parte del alcance final de la tienda.

Sin embargo, el pago mediante tarjeta de crédito y PSE no se implementará durante la primera entrega. Su desarrollo está previsto para la segunda entrega.

## Tecnologías

- PHP.
- Laravel 12.
- MySQL mediante MAMP.
- Laravel Breeze (stack Blade) para autenticación.
- Google Gemini (API de recomendaciones).
- Blade.
- HTML.
- CSS.
- JavaScript.
- Git y GitHub.

## Requisitos

Cada integrante debe tener instalado:

- PHP compatible con Laravel 12.
- Composer.
- MAMP.
- Node.js y npm.
- Git.
- Visual Studio Code o un editor equivalente.

## Clonación e instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/LeidyRoldan516/tienda-plantas-laravel.git
```

### 2. Entrar en la carpeta del proyecto

```bash
cd tienda-plantas-laravel
```

### 3. Instalar las dependencias de PHP

```bash
composer install
```

### 4. Crear el archivo `.env`

En Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

En macOS o Linux:

```bash
cp .env.example .env
```

El archivo `.env` contiene la configuración local de cada computador y no debe subirse a GitHub.

### 5. Generar la clave de Laravel

```bash
php artisan key:generate
```

### 6. Instalar las dependencias del frontend

```bash
npm install
```

## Configuración local de MAMP y MySQL

Cada integrante debe configurar MySQL localmente porque el archivo `.env` no se comparte mediante GitHub.

### 1. Evitar conflictos con MySQL80

Si el servicio `MySQL80` está utilizando el mismo puerto de MAMP, se debe detener antes de iniciar los servidores de MAMP.

En Windows:

1. Presionar `Win + R`.
2. Escribir `services.msc`.
3. Buscar el servicio `MySQL80`.
4. Presionar clic derecho.
5. Seleccionar `Detener`.

Solo se debe detener este servicio cuando cause un conflicto con el puerto de MySQL de MAMP.

### 2. Iniciar MAMP

Abrir MAMP e iniciar:

- Apache.
- MySQL.

Los dos indicadores deben aparecer activos.

### 3. Crear la base de datos

Cada integrante debe crear una base de datos local con el mismo nombre:

```text
tienda_plantas
```

La base puede crearse desde phpMyAdmin o desde la herramienta utilizada para conectarse al MySQL de MAMP.

No se deben crear manualmente las tablas. Las tablas serán creadas mediante las migraciones de Laravel.

### 4. Configurar `.env`

Dentro del archivo `.env`, reemplazar la configuración de la base de datos por:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tienda_plantas
DB_USERNAME=root
DB_PASSWORD=
```

Cada integrante debe verificar el puerto, el usuario y la contraseña correspondientes a su instalación de MAMP.

Si MAMP utiliza una contraseña para `root`, debe escribirse únicamente en el archivo `.env` local.

Ejemplo:

```env
DB_PASSWORD=contraseña_local
```

Nunca se deben publicar contraseñas reales en GitHub, en el README o en la Wiki.

### 5. Limpiar la configuración almacenada

Después de modificar `.env`, ejecutar:

```bash
php artisan config:clear
```

### 6. Ejecutar las migraciones y el seeder

```bash
php artisan migrate
php artisan db:seed
```

Si el comando termina sin errores, Laravel quedó conectado correctamente a MySQL y se crearon los usuarios de prueba.

También se puede ejecutar en un solo paso:

```bash
php artisan migrate --seed
```

## Configuración de Gemini (recomendaciones)

Las recomendaciones usan la API de Google Gemini. Cada integrante debe configurar su propia clave en el archivo `.env` local (nunca subirla a GitHub).

### 1. Obtener una clave de API

1. Entrar a [Google AI Studio](https://aistudio.google.com/apikey).
2. Crear una API key.
3. Copiarla únicamente en el `.env` local.

### 2. Variables en `.env`

```env
GEMINI_API_KEY=
GEMINI_BASE_URL=https://generativelanguage.googleapis.com/v1beta
GEMINI_MODEL=gemini-3.6-flash
```

Después de guardar la clave:

```bash
php artisan config:clear
```

Sin `GEMINI_API_KEY` la aplicación seguirá funcionando, pero al generar recomendaciones mostrará un mensaje indicando que el servicio no está configurado.

## Recomendaciones personalizadas

Flujo del cliente autenticado:

1. Abrir `/recomendaciones`.
2. Completar y guardar el perfil de preferencias.
3. Pulsar **Generar recomendación**.
4. Revisar la explicación y las plantas sugeridas (solo del catálogo con stock).
5. Consultar el historial de recomendaciones anteriores.

Componentes principales:

| Pieza           | Ubicación                                          |
| --------------- | -------------------------------------------------- |
| Controlador     | `app/Http/Controllers/RecomendacionController.php` |
| Servicio Gemini | `app/Services/RecomendacionIAService.php`          |
| Preferencias    | `app/Models/PerfilPreferencias.php`                |
| Recomendación   | `app/Models/Recomendacion.php`                     |
| Vistas          | `resources/views/recomendaciones/`                 |

La IA solo puede recomendar plantas existentes en el inventario. Si Gemini no responde o la respuesta es inválida, se muestra un mensaje al cliente y la tienda sigue operativa.

## Autenticación

La autenticación usa **Laravel Breeze** (Blade). Existen dos roles: `cliente` y `administrador`.

- El registro público (`/register`) crea siempre usuarios con `rol = cliente`.
- El administrador inicial se crea con el seeder (no se elige rol en el formulario).
- Las rutas `/admin/*` requieren middleware `auth` + `admin`.
- El catálogo público y la home no exigen sesión; el dashboard del cliente sí.

### Credenciales de prueba (seeder)

| Rol           | Correo               | Contraseña |
| ------------- | -------------------- | ---------- |
| Administrador | `admin@tienda.com`   | `password` |
| Cliente       | `cliente@tienda.com` | `password` |

Estas credenciales son solo para desarrollo local.

### Rutas de autenticación

| Acción            | Ruta           | Protección   |
| ----------------- | -------------- | ------------ |
| Login             | `/login`       | guest        |
| Registro          | `/register`    | guest        |
| Logout            | `POST /logout` | auth         |
| Dashboard cliente | `/dashboard`   | auth         |
| Panel admin       | `/admin`       | auth + admin |

Tras el login, el administrador va a `/admin` y el cliente a `/dashboard`. Un cliente que intente entrar a `/admin` recibe `403`.

El locale por defecto es `es` (`APP_LOCALE=es`). Los textos de la interfaz están en `resources/lang/es`.

## Ejecución del proyecto

### 1. Iniciar MAMP

Antes de ejecutar Laravel, MySQL debe estar activo en MAMP.

### 2. Iniciar el servidor de Laravel

```bash
php artisan serve
```

La aplicación estará disponible en:

```text
http://127.0.0.1:8000
```

### 3. Compilar los recursos del frontend

En otra terminal:

```bash
npm run dev
```

Las dos terminales deben permanecer abiertas durante el desarrollo.

## Rutas principales

| Sección                  | Ruta                                 | Protección   |
| ------------------------ | ------------------------------------ | ------------ |
| Página principal         | `/`                                  | pública      |
| Inicio de sesión         | `/login`                             | guest        |
| Registro                 | `/register`                          | guest        |
| Dashboard cliente        | `/dashboard`                         | auth         |
| Catálogo                 | `/catalogo`                          | pública      |
| Carrito                  | `/carrito`                           | auth         |
| Pedidos                  | `/pedidos`                           | auth         |
| Recomendaciones          | `/recomendaciones`                   | auth         |
| Guardar preferencias     | `POST /recomendaciones/preferencias` | auth         |
| Generar recomendación    | `POST /recomendaciones/generar`      | auth         |
| Detalle de recomendación | `/recomendaciones/{recomendacion}`   | auth         |
| Panel administrativo     | `/admin`                             | auth + admin |

## Datos ficticios

Los datos de prueba se crearán mediante seeders y factories de Laravel.

Cuando exista un conjunto suficiente de datos ficticios, se exportarán los registros a un archivo SQL y se subirán al repositorio, de acuerdo con las instrucciones de la entrega.

Las contraseñas, credenciales y datos personales reales no se incluirán en el archivo SQL.

## Organización del trabajo

El equipo utilizará GitHub Projects para registrar, distribuir y actualizar las tareas.

Estados del tablero:

- `Hacer`: tarea pendiente.
- `En curso`: tarea en desarrollo.
- `Hecho`: tarea terminada y verificada.

Cada integrante será responsable de mantener actualizado el estado de sus tareas.

## Ramas

La rama `main` conservará la versión estable del proyecto.

Cada integrante desarrollará sus tareas en una rama independiente.

Ejemplos:

```text
feature/plantas
feature/catalogo
feature/carrito
feature/pedidos
feature/autenticacion
feature/recomendaciones
docs/diagramas
fix/validacion-plantas
```

Los cambios deberán integrarse mediante Pull Requests después de ser revisados.

## Formato del código

Antes de crear un commit se debe ejecutar:

```bash
php vendor/bin/pint
```

Para comprobar el formato sin modificar archivos:

```bash
php vendor/bin/pint --test
```

## Documentación

La Wiki del repositorio contiene:

- Página principal.
- Entregable 1.
- Guía de estilo de programación.
- Reglas de programación.
- Funcionalidades interesantes.
- Pantallazos.

## Integrantes

- Leidy Dayhana Roldán
- Simon Martinez Gomez
- David Zapata Orozco

## Estado

Proyecto en desarrollo para el Entregable 1 de Arquitectura MVC.
