FROM laravelsail/php83-composer:latest

# Instala extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev libicu-dev g++ \
    && docker-php-ext-install pdo_pgsql intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copia el código
WORKDIR /var/www/html
COPY . .

# Permisos para Laravel
RUN chmod -R 775 storage bootstrap/cache

# Puerto para Railway
ENV PORT=8080
EXPOSE 8080

# Comando de inicio
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
