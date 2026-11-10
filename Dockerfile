# 1) Assets del frontend (Vite + Tailwind)
FROM node:22-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci --no-audit --no-fund
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build

# 2) Dependencias PHP (Composer no llega a la imagen final)
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --no-dev --optimize --no-scripts

# 3) Imagen final liviana
FROM php:8.3-cli-alpine
COPY --from=mlocati/php-extension-installer:2 /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_mysql pdo_pgsql zip intl gd bcmath \
    && rm /usr/local/bin/install-php-extensions
WORKDIR /app
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build
RUN php artisan package:discover --ansi
CMD sh -c "php artisan storage:link || true; php artisan migrate --force; php artisan db:seed --class=PlantasSeeder --force; php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"
