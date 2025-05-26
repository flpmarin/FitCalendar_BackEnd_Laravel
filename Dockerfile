# ---------- Etapa 1: build frontend --------------------
FROM node:20-alpine AS node-build
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --omit=dev
# Copiamos todos los archivos necesarios para el build
COPY resources ./resources
# Copia los archivos de configuración si existen
COPY *.js ./
RUN npm run build

# ---------- Etapa 2: runtime PHP ----------------------
FROM laravelsail/php83-composer:latest
USER root

# PHP + extensiones
RUN apt-get update && apt-get install -y --no-install-recommends \
        libicu-dev libpq-dev g++ \
    && docker-php-ext-install -j$(nproc) intl pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY --chown=laravel:laravel . .
# Copiamos los assets generados - ajustamos la ruta según el sistema de build
COPY --from=node-build /app/public/build ./public/build

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader \
 && chmod -R ug+rwx storage bootstrap/cache

ENV PORT=${PORT:-8080}
EXPOSE ${PORT}

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=${PORT}"]
