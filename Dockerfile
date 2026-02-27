# Dockerfile para desplegar DulceConMaria (Laravel + Vite) en Render

FROM php:8.4-apache

# Instalar dependencias del sistema necesarias para PHP, Composer y Vite
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev libzip-dev curl \
    libpq-dev \
    nodejs npm \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Configurar document root a /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

# Copiar composer desde la imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar archivos del proyecto
COPY . /var/www/html

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Crear base de datos sqlite (si se usa)
RUN touch database/database.sqlite

# Ejecutar migraciones (no fallar si ya existen)
RUN php artisan migrate --force || true

# Limpiar cachés de Laravel para que use siempre la config/rutas/vistas recientes
RUN php artisan config:clear && php artisan route:clear && php artisan view:clear

# Instalar dependencias JS y compilar assets
RUN npm install && npm run build

# Establecer permisos sobre storage, cache y base de datos
RUN chown -R www-data:www-data storage bootstrap/cache database

EXPOSE 80

CMD ["apache2-foreground"]
