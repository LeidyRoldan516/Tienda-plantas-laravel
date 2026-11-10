#!/usr/bin/env bash
# Se ejecuta EN LA VM: descarga la imagen nueva y reinicia los contenedores. No compila nada.
# Requisitos: docker-compose.prod.yml y .env (con APP_IMAGE) en esta misma carpeta.
set -euo pipefail
cd "$(dirname "$0")"

COMPOSE="docker compose -f docker-compose.prod.yml"

$COMPOSE pull app queue
$COMPOSE up -d --remove-orphans
docker image prune -f >/dev/null   # borra la imagen anterior que quedo colgada

$COMPOSE ps
