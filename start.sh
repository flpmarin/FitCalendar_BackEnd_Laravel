#!/bin/bash
set -e

echo "Cargando variables de entorno de Railway..."

# Verifica que DATABASE_URL esté definida
if [ -z "$DATABASE_URL" ]; then
  echo "Error: DATABASE_URL no está definido."
  exit 1
fi

# Extraer variables desde DATABASE_URL
DB_CONNECTION=pgsql
DB_HOST=$(php -r "echo parse_url(getenv('DATABASE_URL'))['host'];")
DB_PORT=$(php -r "echo parse_url(getenv('DATABASE_URL'))['port'];")
DB_DATABASE=$(php -r "echo ltrim(parse_url(getenv('DATABASE_URL'))['path'], '/');")
DB_USERNAME=$(php -r "echo parse_url(getenv('DATABASE_URL'))['user'];")
DB_PASSWORD=$(php -r "echo parse_url(getenv('DATABASE_URL'))['pass'];")

echo "Generando archivo .env…"
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
EOF

echo "Contenido generado del .env:"
cat .env

echo "Limpiando cachés..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

echo "Cacheando configuración..."
php artisan config:cache
php artisan route:cache

echo "Ejecutando migraciones..."
php artisan migrate --force || echo "Migraciones fallaron, pero continúo."

echo "Ejecutando seeders..."
php artisan db:seed --force || echo "Seeder falló, pero continúo."

echo "Compilando assets de Filament..."
php artisan filament:assets || echo "Falló la compilación de Filament, pero continúo."

echo "Lanzando servidor en puerto ${PORT:-8000}"
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
