
# ---- Etapa runtime -------------------------------------------------
FROM laravelsail/php82-composer:latest

# 1) Código
WORKDIR /var/www/html
COPY --chown=laravel:laravel . .

# 2) Dependencias
RUN composer install --no-dev --optimize-autoloader \
 && npm ci --omit=dev \
 && npm run build

# 3) Permisos
RUN chmod -R ug+rwx storage bootstrap/cache

# 4) Railway expone $PORT en runtime; úsalo
ENV PORT=${PORT:-8000}
EXPOSE ${PORT}

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=${PORT}"]
