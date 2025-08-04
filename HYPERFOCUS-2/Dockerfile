# Usar la imagen base de PHP 8.3 con Apache
FROM php:8.3-apache

# Instalar extensiones necesarias de PHP + Node.js y npm
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo_mysql

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el contenido del proyecto al contenedor
COPY . /var/www/html

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Dar permisos a storage y bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Habilitar el módulo de reescritura de Apache (no se usará en Render Free, pero por si acaso)
RUN a2enmod rewrite

# Instalar dependencias, compilar assets y preparar la app
RUN composer install && \
    npm install && npm run build && \
    php artisan config:cache && \
    php artisan migrate --force && \
    php artisan db:seed --force && \
    php artisan storage:link

# Exponer el puerto usado por Laravel internamente
EXPOSE 10000

# Iniciar Laravel usando su servidor embebido
CMD php artisan serve --host=0.0.0.0 --port=10000
