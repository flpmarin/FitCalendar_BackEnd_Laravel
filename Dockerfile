FROM php:8.3-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev zip unzip git curl libicu-dev g++ \
    && docker-php-ext-install pdo_pgsql intl zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar y preparar dependencias
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copiar el resto del código
COPY . .

# Crear APP_KEY y cache config (opcional)
RUN php artisan config:cache && php artisan route:cache

# Permisos
RUN chmod -R 775 storage bootstrap/cache

# Variables
ENV PORT=8080

EXPOSE 8080

COPY start.sh /start.sh
RUN chmod +x /start.sh
CMD ["sh", "/start.sh"]

