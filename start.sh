#!/bin/bash
set -e

echo " Cargando variables de entorno de Railway..."

# Verificar que DATABASE_URL exista
if [ -z "$DATABASE_URL" ]; then
  echo " Error: DATABASE_URL no está definido."
  exit 1
fi

# Desglosar DATABASE_URL
DB_HOST=$(php -r "echo parse_url(getenv('DATABASE_URL'))['host'];")
DB_PORT=$(php -r "echo parse_url(getenv('DATABASE_URL'))['port'];")
DB_DATABASE=$(php -r "echo ltrim(parse_url(getenv('DATABASE_URL'))['path'], '/');")
DB_USERNAME=$(php -r "echo parse_url(getenv('DATABASE_URL'))['user'];")
DB_PASSWORD=$(php -r "echo parse_url(getenv('DATABASE_URL'))['pass'];")
DB_CONNECTION=pgsql-railway

# Exportar para que Artisan los use
export DB_CONNECTION DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD
export RAILWAY_DB_HOST=$DB_HOST
export RAILWAY_DB_PORT=$DB_PORT
export RAILWAY_DB_DATABASE=$DB_DATABASE
export RAILWAY_DB_USERNAME=$DB_USERNAME
export RAILWAY_DB_PASSWORD=$DB_PASSWORD
export CACHE_STORE=file   # evita que optimize:clear requiera la base

echo " Generando archivo .env…"
cat <<EOF > .env
APP_ENV=production
APP_KEY=${APP_KEY}
APP_DEBUG=false
APP_URL=${APP_URL}
DATABASE_URL=${DATABASE_URL}

DB_CONNECTION=${DB_CONNECTION}
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

RAILWAY_DB_HOST=${DB_HOST}
RAILWAY_DB_PORT=${DB_PORT}
RAILWAY_DB_DATABASE=${DB_DATABASE}
RAILWAY_DB_USERNAME=${DB_USERNAME}
RAILWAY_DB_PASSWORD=${DB_PASSWORD}

CACHE_STORE=file
EOF

echo " Verificando conexión a la base de datos…"
echo "DB_HOST: $DB_HOST"
echo "DB_PORT: $DB_PORT"
echo "DB_DATABASE: $DB_DATABASE"
echo "DB_USERNAME: $DB_USERNAME"

# Limpiar cachés viejas generadas en build
rm -f bootstrap/cache/config.php bootstrap/cache/routes.php

echo " Limpiando y cacheando configuración…"
php artisan optimize:clear
php artisan config:cache
php artisan route:cache

echo " Ejecutando migraciones…"
php artisan migrate --force || echo "  Migraciones fallaron, pero continuo."

echo " Ejecutando seeders…"
php artisan db:seed --force || echo "  Seeder falló, pero continuo."

echo " Compilando assets de Filament…"
php artisan filament:assets || echo "  Falló la compilación de Filament, pero continuo."

echo " Lanzando servidor en :${PORT:-8000}"
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
