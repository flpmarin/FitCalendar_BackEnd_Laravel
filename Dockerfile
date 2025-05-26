FROM ghcr.io/laravelphp/sail-8.2:latest

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . .

# Instalar dependencias PHP para producción
RUN composer install --no-dev --optimize-autoloader

# Compilar assets de frontend (si usas Vite/Laravel Mix)
RUN npm ci && npm run build

# Permisos para storage y cache
RUN chmod -R 775 storage bootstrap/cache
RUN chown -R sail:sail storage bootstrap/cache

# Configuración para PostgreSQL
# La imagen ya incluye el cliente PostgreSQL

# Exponer puerto
EXPOSE 8000

# Configurar el comando para iniciar la aplicación
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
