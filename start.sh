#!/bin/bash

# Cache configuración y rutas
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones forzadas (evita usar en dev sin control)
php artisan migrate --force

# Levantar servidor Laravel
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
