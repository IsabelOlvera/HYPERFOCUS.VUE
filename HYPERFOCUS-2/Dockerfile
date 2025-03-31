# Usar la imagen base de PHP 8.3 con Apache
FROM php:8.3-apache

# Instalar extensiones necesarias de PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo_mysql

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el contenido del proyecto al contenedor
COPY . /var/www/html

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Dar permisos a las carpetas necesarias
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Habilitar el módulo de reescritura de Apache
RUN a2enmod rewrite

# Exponer el puerto 80
EXPOSE 80

# Ejecutar Composer para instalar dependencias
RUN composer install

# Copiar el archivo de configuración de Apache
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf
