FROM laravelsail/php83-composer:latest

# Instala extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev libicu-dev g++ \
    && docker-php-ext-install pdo_pgsql intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copia código de la app
WORKDIR /var/www/html
COPY . .

# Configura permisos necesarios
RUN chmod -R 775 storage bootstrap/cache \
 && chown -R sail:sail storage bootstrap/cache

# Configura el puerto que Railway usa
ENV PORT=8080
EXPOSE 8080

# Comando de arranque para servir la API
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
