FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev zip unzip git curl libicu-dev g++ \
    && docker-php-ext-install pdo_pgsql intl zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .

RUN php artisan package:discover --ansi

# Puedes dejar este cache si quieres que compile más rápido
RUN php artisan config:cache && php artisan route:cache

RUN chmod -R 775 storage bootstrap/cache

ENV PORT=8000
EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

# Da permisos al script que arrancará Laravel
RUN chmod +x /var/www/html/start.sh

# Comando que ejecuta tu app
CMD ["/bin/bash", "/var/www/html/start.sh"]
