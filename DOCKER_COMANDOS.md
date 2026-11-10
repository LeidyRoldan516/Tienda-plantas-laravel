```
docker compose up -d --build
docker compose up -d
docker compose ps
docker compose logs -f app
docker compose logs -f queue
docker compose logs -f db
docker compose restart app
docker compose stop
docker compose down
docker compose down -v
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --force
docker compose exec app php artisan migrate:fresh --seed --force
docker compose exec app php artisan optimize:clear
docker compose exec app sh
docker compose exec db mysql -utienda -ptienda tienda_plantas
docker images tienda-plantas
docker image prune -f
docker builder prune -f
```

La imagen `tienda-plantas` se construye en 3 etapas (Node para los assets, Composer para `vendor`, y una final `php:8.3-cli-alpine`), por eso la imagen final no lleva Node, Composer, git ni herramientas de compilacion.

Despues de reconstruir, la imagen anterior queda "colgada" ocupando espacio: `docker image prune -f` y `docker builder prune -f` la borran (no tocan la base de datos).

Scripts: en Windows `docker.bat`, en Linux/macOS `./docker.sh` (mismas opciones, p. ej. `./docker.sh up`, `./docker.sh size`, `./docker.sh prune`).
