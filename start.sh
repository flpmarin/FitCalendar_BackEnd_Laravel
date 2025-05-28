#!/bin/bash
set -e

# (1) ALOJAR VARIABLES DE ENTORNO CORRECTAS
# Railway las inyecta en runtime, cachear ahora.
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# (2) MIGRACIONES siempre que arranque
php artisan migrate --force

# (3) LEVANTAR el servidor
php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
