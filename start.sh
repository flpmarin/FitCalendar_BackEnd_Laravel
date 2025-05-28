#!/bin/bash
set -e

echo " Esperando a la base de datos…"
# Extrae host y puerto de la URL
DB_HOST=$(php -r "echo parse_url(getenv('DATABASE_URL'))['host'];")
DB_PORT=$(php -r "echo parse_url(getenv('DATABASE_URL'))['port'];")

# Intenta conectar hasta 30 s
for i in {1..30}; do
  if pg_isready -h "$DB_HOST" -p "$DB_PORT" > /dev/null 2>&1; then
    echo " Base de datos lista."
    break
  fi
  echo "⏳ Aún no responde ($i/30)…"
  sleep 1
done

echo " Cacheando config y rutas…"
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo " Migraciones…"
php artisan migrate --force || echo "⚠️  Migraciones fallaron, pero continuo."

echo " Lanzando servidor en :${PORT:-8000}"
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
