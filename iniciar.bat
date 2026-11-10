@echo off
setlocal
chcp 65001 >nul
cd /d "%~dp0"
title Tienda de Plantas - Laravel

echo ==========================================
echo   El Rincon de las Plantas - Iniciando...
echo ==========================================

where php >nul 2>&1 || (echo [ERROR] PHP no esta instalado o no esta en el PATH. & pause & exit /b 1)
where composer >nul 2>&1 || (echo [ERROR] Composer no esta instalado o no esta en el PATH. & pause & exit /b 1)
where npm >nul 2>&1 || (echo [ERROR] Node.js/npm no esta instalado o no esta en el PATH. & pause & exit /b 1)

if not exist "vendor" (
    echo [1/5] Instalando dependencias de PHP...
    call composer install || (pause & exit /b 1)
) else echo [1/5] Dependencias de PHP OK

if not exist "node_modules" (
    echo [2/5] Instalando dependencias de Node...
    call npm install || (pause & exit /b 1)
) else echo [2/5] Dependencias de Node OK

if not exist ".env" (
    echo [3/5] Creando archivo .env...
    copy ".env.example" ".env" >nul
)
findstr /r /c:"^APP_KEY=.." ".env" >nul || php artisan key:generate
echo [3/5] Archivo .env OK

if not exist "database\database.sqlite" type nul > "database\database.sqlite"
echo [4/5] Ejecutando migraciones...
php artisan migrate --force || (pause & exit /b 1)

if not exist "public\storage" php artisan storage:link >nul 2>&1

echo [5/5] Levantando servidores...
start "Vite (npm run dev)" cmd /k "npm run dev"
start "" "http://127.0.0.1:8000"
echo.
echo Aplicacion en http://127.0.0.1:8000  (Ctrl+C para detener)
echo.
php artisan serve
