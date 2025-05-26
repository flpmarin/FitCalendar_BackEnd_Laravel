FROM laravelsail/php83-composer:latest

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev libicu-dev g++ \
    && docker-php-ext-install pdo_pgsql intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# 1. Copia solo composer.json y lock para cache
COPY composer.json composer.lock ./

# 2. Instala dependencias PHP (sin ejecutar scripts)
RUN COMPOSER_ALLOW_SUPERUSER=1 COMPOSER_DISABLE_INSTALLER_PLUGINS=1 \
    composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 3. Ahora sí, copia todo
COPY . .

# 4. Ejecutar scripts que dependen de `artisan`
RUN php artisan package:discover --ansi

# 5. Permisos
RUN chmod -R 775 storage bootstrap/cache

# 6. Puerto expuesto en Railway
ENV PORT=8080
EXPOSE 8080

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
