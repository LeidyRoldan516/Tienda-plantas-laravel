@echo off
setlocal
chcp 65001 >nul
cd /d "%~dp0"
title Tienda de Plantas - Docker

where docker >nul 2>&1 || (echo [ERROR] Docker no esta instalado o no esta en el PATH. & pause & exit /b 1)
docker info >nul 2>&1 || (echo [ERROR] Docker Desktop no esta corriendo. Abrelo y vuelve a intentar. & pause & exit /b 1)

rem Permite usarlo directo: docker.bat up / down / logs / ...
if "%~1"=="" goto menu
call :run %~1
exit /b %errorlevel%

:menu
echo.
echo ==========================================
echo   El Rincon de las Plantas - Docker
echo ==========================================
echo   1. Levantar (construye la imagen)
echo   2. Levantar sin reconstruir
echo   3. Ver estado de los contenedores
echo   4. Ver logs de la app
echo   5. Ver logs de la cola (queue)
echo   6. Reiniciar la app
echo   7. Detener (conserva los datos)
echo   8. Borrar todo (contenedores + base de datos)
echo   9. Reiniciar BD (migrate:fresh --seed)
echo  10. Limpiar cache de Laravel
echo  11. Abrir consola en la app
echo  12. Abrir MySQL
echo  13. Ver tamano de la imagen
echo  14. Liberar espacio (imagenes viejas y cache de build)
echo   0. Salir
echo ==========================================
set "op="
set /p op="Opcion: "
if not defined op exit /b 0

if "%op%"=="1"  call :run up
if "%op%"=="2"  call :run start
if "%op%"=="3"  call :run ps
if "%op%"=="4"  call :run logs
if "%op%"=="5"  call :run logs-queue
if "%op%"=="6"  call :run restart
if "%op%"=="7"  call :run stop
if "%op%"=="8"  call :run down
if "%op%"=="9"  call :run fresh
if "%op%"=="10" call :run clear
if "%op%"=="11" call :run shell
if "%op%"=="12" call :run mysql
if "%op%"=="13" call :run size
if "%op%"=="14" call :run prune
if "%op%"=="0"  exit /b 0
goto menu

:run
if /i "%~1"=="up" (
    docker compose up -d --build
    goto :urls
)
if /i "%~1"=="start" (
    docker compose up -d
    goto :urls
)
if /i "%~1"=="ps"         docker compose ps & exit /b
if /i "%~1"=="logs"       docker compose logs -f app & exit /b
if /i "%~1"=="logs-queue" docker compose logs -f queue & exit /b
if /i "%~1"=="restart"    docker compose restart app queue & exit /b
if /i "%~1"=="stop"       docker compose stop & exit /b
if /i "%~1"=="down" goto :down
if /i "%~1"=="fresh"  docker compose exec app php artisan migrate:fresh --seed --force & exit /b
if /i "%~1"=="clear"  docker compose exec app php artisan optimize:clear & exit /b
if /i "%~1"=="shell"  docker compose exec app sh & exit /b
if /i "%~1"=="mysql"  docker compose exec db mysql -utienda -ptienda tienda_plantas & exit /b
if /i "%~1"=="size"   docker images tienda-plantas & exit /b
if /i "%~1"=="prune" (
    docker image prune -f
    docker builder prune -f
    exit /b
)
echo [ERROR] Comando desconocido: %~1
echo Comandos: up, start, ps, logs, logs-queue, restart, stop, down, fresh, clear, shell, mysql, size, prune
exit /b 1

:down
set "conf="
set /p conf="Esto borra la base de datos y los archivos subidos. Escribe SI para confirmar: "
if /i "%conf%"=="SI" (docker compose down -v) else echo Cancelado.
exit /b

:urls
echo.
echo App:        http://localhost:8080
echo phpMyAdmin: http://localhost:8081
echo MySQL:      localhost:3307  (usuario tienda / clave tienda)
echo (La primera vez puede tardar unos segundos en migrar y sembrar la BD)
exit /b
