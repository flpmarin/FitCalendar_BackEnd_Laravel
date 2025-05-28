FROM php:8.3-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    bash postgresql-client \
    libpq-dev libzip-dev zip unzip git curl libicu-dev g++ \
    && docker-php-ext-install pdo_pgsql intl zip opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar primero los archivos necesarios para instalar dependencias
COPY composer.json composer.lock ./

# Instalar dependencias sin ejecutar scripts (porque aún no hay artisan)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Ahora copiar el resto del proyecto, incluido artisan
COPY . .

# Ahora sí, ejecutar scripts que usan artisan
RUN php artisan package:discover --ansi

# Opcional: cache de configuración y rutas
# RUN php artisan config:cache && php artisan route:cache

# Asignar permisos
RUN chmod -R 775 storage bootstrap/cache

# Variables y puertos
ENV PORT=8000
EXPOSE 8000

# CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
CMD ["/bin/bash", "/var/www/html/start.sh"]

