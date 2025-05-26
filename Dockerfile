FROM laravelsail/php83-composer:latest
USER root

# --- PHP extensiones ---
RUN apt-get update && apt-get install -y --no-install-recommends \
        curl gnupg ca-certificates libicu-dev libpq-dev g++ \
    && docker-php-ext-install -j$(nproc) intl pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# --- Node 20 + npm ---
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# 1. Copiar SOLO los archivos de dependencias primero
COPY --chown=laravel:laravel composer.json composer.lock ./
COPY --chown=laravel:laravel package.json package-lock.json ./

# 2. Instalar dependencias
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader
RUN npm install --omit=dev --no-audit --progress=false

# 3. Copiar el resto del código
COPY --chown=laravel:laravel . .

# 4. Compilar assets y limpiar
RUN npm run build && rm -rf node_modules

# 5. Configurar permisos
RUN chmod -R ug+rwx storage bootstrap/cache

ENV PORT=${PORT:-8080}
EXPOSE ${PORT}
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=${PORT}"]
