#!/usr/bin/env bash
# Tienda de Plantas - Docker (version Linux/macOS de docker.bat)
# Uso: ./docker.sh            -> menu interactivo
#      ./docker.sh up|start|ps|logs|logs-queue|restart|stop|down|fresh|clear|shell|mysql|size|prune

cd "$(dirname "$0")" || exit 1

if ! command -v docker >/dev/null 2>&1; then
    echo "[ERROR] Docker no esta instalado o no esta en el PATH."
    exit 1
fi
if ! docker info >/dev/null 2>&1; then
    echo "[ERROR] Docker no esta corriendo (o tu usuario no tiene permisos)."
    echo "        Prueba: sudo systemctl start docker   o agrega tu usuario al grupo docker."
    exit 1
fi

urls() {
    echo
    echo "App:        http://localhost:8080"
    echo "phpMyAdmin: http://localhost:8081"
    echo "MySQL:      localhost:3307  (usuario tienda / clave tienda)"
    echo "(La primera vez puede tardar unos segundos en migrar y sembrar la BD)"
}

down() {
    read -r -p "Esto borra la base de datos y los archivos subidos. Escribe SI para confirmar: " conf
    if [ "$(echo "$conf" | tr '[:lower:]' '[:upper:]')" = "SI" ]; then
        docker compose down -v
    else
        echo "Cancelado."
    fi
}

run() {
    case "$(echo "$1" | tr '[:upper:]' '[:lower:]')" in
        up)         docker compose up -d --build && urls ;;
        start)      docker compose up -d && urls ;;
        ps)         docker compose ps ;;
        logs)       docker compose logs -f app ;;
        logs-queue) docker compose logs -f queue ;;
        restart)    docker compose restart app queue ;;
        stop)       docker compose stop ;;
        down)       down ;;
        fresh)      docker compose exec app php artisan migrate:fresh --seed --force ;;
        clear)      docker compose exec app php artisan optimize:clear ;;
        shell)      docker compose exec app sh ;;
        mysql)      docker compose exec db mysql -utienda -ptienda tienda_plantas ;;
        size)       docker images tienda-plantas ;;
        prune)      docker image prune -f && docker builder prune -f ;;
        *)
            echo "[ERROR] Comando desconocido: $1"
            echo "Comandos: up, start, ps, logs, logs-queue, restart, stop, down, fresh, clear, shell, mysql, size, prune"
            return 1
            ;;
    esac
}

if [ -n "$1" ]; then
    run "$1"
    exit $?
fi

while true; do
    echo
    echo "=========================================="
    echo "  El Rincon de las Plantas - Docker"
    echo "=========================================="
    echo "  1. Levantar (construye la imagen)"
    echo "  2. Levantar sin reconstruir"
    echo "  3. Ver estado de los contenedores"
    echo "  4. Ver logs de la app"
    echo "  5. Ver logs de la cola (queue)"
    echo "  6. Reiniciar la app"
    echo "  7. Detener (conserva los datos)"
    echo "  8. Borrar todo (contenedores + base de datos)"
    echo "  9. Reiniciar BD (migrate:fresh --seed)"
    echo " 10. Limpiar cache de Laravel"
    echo " 11. Abrir consola en la app"
    echo " 12. Abrir MySQL"
    echo " 13. Ver tamano de la imagen"
    echo " 14. Liberar espacio (imagenes viejas y cache de build)"
    echo "  0. Salir"
    echo "=========================================="
    read -r -p "Opcion: " op || exit 0

    case "$op" in
        1)  run up ;;
        2)  run start ;;
        3)  run ps ;;
        4)  run logs ;;
        5)  run logs-queue ;;
        6)  run restart ;;
        7)  run stop ;;
        8)  run down ;;
        9)  run fresh ;;
        10) run clear ;;
        11) run shell ;;
        12) run mysql ;;
        13) run size ;;
        14) run prune ;;
        0|"") exit 0 ;;
        *)  echo "Opcion invalida." ;;
    esac
done
