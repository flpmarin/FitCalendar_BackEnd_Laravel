#!/bin/bash
set -e

echo "📄 Generando archivo .env…"
cat <<EOF > .env
APP_ENV=production
APP_KEY=${APP_KEY}
APP_DEBUG=false
APP_URL=${APP_URL}
DATABASE_URL=${DATABASE_URL}
EOF

echo "🔄 Esperando a la base de datos…"
DB_HOST=$(php -r "echo parse_url(getenv('DATABASE_URL'))['host'];")
DB_PORT=$(php -r "echo parse_url(getenv('DATABASE_URL'))['port'];")

echo "DATABASE_URL: $DATABASE_URL"
echo "DB_HOST: $DB_HOST"
echo "DB_PORT: $DB_PORT"
#for i in {1..60}; do
#  if pg_isready -h "$DB_HOST" -p "$DB_PORT" > /dev/null 2>&1; then
#    echo "✅ Base de datos lista."
#    break
#  fi
#  echo "⏳ Aún no responde ($i/60)…"
#  sleep 1
#done
echo "⏳ Probando conexión a la base de datos con migración..."
php artisan migrate --force && echo "✅ Migración OK" || echo "❌ Falló la migración"


echo "🚀 Cacheando config y rutas…"
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "📦 Ejecutando migraciones…"
php artisan migrate --force || echo "⚠️  Migraciones fallaron, pero continuo."

echo "🟢 Lanzando servidor en :${PORT:-8000}"
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
