FROM laravelsail/php83-composer:latest

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev libicu-dev g++ \
    && docker-php-ext-install pdo_pgsql intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar solo lo necesario primero (para cache)
COPY composer.json composer.lock ./

# Instalar dependencias
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Ahora sí: copiar el resto del código
COPY . .

# Configurar permisos
RUN chmod -R 775 storage bootstrap/cache

# Exponer puerto que Railway usa
ENV PORT=8080
EXPOSE 8080

# Arrancar servidor Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
