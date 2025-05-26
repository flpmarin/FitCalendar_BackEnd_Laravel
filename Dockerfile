# ---- Etapa runtime -------------------------------------------------
FROM laravelsail/php83-composer:latest
USER root

# 1) Extensiones nativas que tu app necesita
RUN apt-get update && apt-get install -y --no-install-recommends \
        libicu-dev libpq-dev g++ \
    && docker-php-ext-install -j$(nproc) intl pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2) Copia del código
WORKDIR /var/www/html
COPY --chown=laravel:laravel . .

# 3) Dependencias y build frontend
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader \
 && npm ci --omit=dev \
 && npm run build

# 4) Permisos para storage y cache
RUN chmod -R ug+rwx storage bootstrap/cache

# 5) Puerto que expone Railway (variable $PORT)
ENV PORT=${PORT:-8080}
EXPOSE ${PORT}

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=${PORT}"]
