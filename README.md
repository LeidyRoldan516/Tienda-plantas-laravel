# Tienda de plantas

Aplicación web para la consulta, recomendación y compra de plantas, desarrollada con Laravel 12 y MySQL.

## Descripción

La tienda de plantas permitirá que los clientes consulten el catálogo, busquen plantas, administren un carrito de compras y creen pedidos.

La aplicación también contará con recomendaciones personalizadas mediante un servicio externo de inteligencia artificial. Para generar las recomendaciones se tendrán en cuenta aspectos como la experiencia del cliente, el espacio disponible, la iluminación, el tiempo de cuidado y la presencia de mascotas.

El sistema tendrá dos secciones principales:

- Sección para clientes.
- Panel de administración.

## Funcionalidades

1. Consulta y búsqueda de plantas.
2. Administración del carrito de compras.
3. Creación y consulta de pedidos.
4. Recomendaciones personalizadas mediante inteligencia artificial.

## Tecnologías

- PHP
- Laravel 12
- MySQL
- Blade
- HTML
- CSS
- JavaScript
- Git y GitHub

## Requisitos

Antes de ejecutar el proyecto se debe tener instalado:

- PHP compatible con Laravel 12.
- Composer.
- MySQL.
- Node.js y npm.
- Git.

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/LeidyRoldan516/tienda-plantas-laravel.git
```

### 2. Entrar en la carpeta

```bash
cd tienda-plantas-laravel
```

### 3. Instalar las dependencias de PHP

```bash
composer install
```

### 4. Crear el archivo de configuración

En Windows:

```powershell
Copy-Item .env.example .env
```

En macOS o Linux:

```bash
cp .env.example .env
```

### 5. Generar la clave de la aplicación

```bash
php artisan key:generate
```

### 6. Configurar MySQL

Crear una base de datos en MySQL llamada:

```text
tienda_plantas
```

Después, configurar estas variables en el archivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tienda_plantas
DB_USERNAME=root
DB_PASSWORD=
```

El valor de `DB_PASSWORD` debe ajustarse según la configuración local de cada integrante.

### 7. Ejecutar las migraciones

```bash
php artisan migrate
```

### 8. Instalar las dependencias del frontend

```bash
npm install
```

### 9. Compilar los recursos

Para trabajar durante el desarrollo:

```bash
npm run dev
```

### 10. Iniciar Laravel

En una terminal diferente se debe ejecutar:

```bash
php artisan serve
```

La aplicación estará disponible en:

```text
http://127.0.0.1:8000
```

## Rutas principales

| Sección | Ruta |
|---|---|
| Página principal | `/` |
| Inicio de sesión | `/login` |
| Registro | `/register` |
| Catálogo | `/catalogo` |
| Carrito | `/carrito` |
| Pedidos | `/pedidos` |
| Recomendaciones | `/recomendaciones` |
| Administración | `/admin` |

Las rutas diferentes de `/` se habilitarán progresivamente durante el desarrollo.

## Organización del trabajo

El equipo utilizará GitHub Projects para registrar y distribuir las tareas.

Estados del tablero:

- `Hacer`: tarea pendiente.
- `En curso`: tarea en desarrollo.
- `Hecho`: tarea finalizada y verificada.

Cada integrante trabajará en una rama independiente. Los cambios se revisarán mediante Pull Requests antes de incorporarse a `main`.

## Ramas

Ejemplos de nombres permitidos:

```text
feature/catalogo
feature/pedidos
feature/recomendaciones
docs/diagramas
fix/validacion-plantas
```

## Documentación

La documentación del proyecto se encuentra en la Wiki del repositorio:

- Entregable 1.
- Guía de estilo de programación.
- Reglas de programación.
- Funcionalidades interesantes.
- Pantallazos.

## Integrantes

- Leidy Dayhana Roldán
- Simón Martínez Gómez
- David Zapata Orozco

## Estado del proyecto

Proyecto en desarrollo para el Entregable 1 de Arquitectura MVC.
