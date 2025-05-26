FROM laravelsail/php83-composer:latest
USER root

# --- PHP extensiones ---
RUN apt-get update && apt-get install -y --no-install-recommends \
        curl gnupg ca-certificates libicu-dev libpq-dev g++ \
    && docker-php-ext-install -j$(nproc) intl pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# --- Node 20 + npm (desde NodeSource) ---
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# --- código ---
WORKDIR /var/www/html
COPY --chown=laravel:laravel . .

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader \
 && npm install --omit=dev --no-audit --progress=false \
 && npm run build

RUN chmod -R ug+rwx storage bootstrap/cache

ENV PORT=${PORT:-8080}
EXPOSE ${PORT}

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=${PORT}"]
