FROM node:22-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

FROM php:8.3-cli
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN apt-get update && apt-get install -y git unzip libzip-dev libicu-dev libpng-dev libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip intl gd bcmath \
    && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY . .
COPY --from=assets /app/public/build ./public/build
RUN composer install --no-dev --optimize-autoloader --no-interaction
CMD sh -c "php artisan storage:link || true; php artisan migrate --force; php artisan db:seed --class=PlantasSeeder --force; php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"
