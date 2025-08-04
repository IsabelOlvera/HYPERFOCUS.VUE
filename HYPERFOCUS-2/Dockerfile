# Imagen base con Apache y PHP 8.3
FROM php:8.3-apache

# Instalar dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    git \
    npm \
    nodejs \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo_mysql

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar todos los archivos del proyecto al contenedor
COPY . .

# Dar permisos a las carpetas necesarias
RUN chown -R www-data:www-data storage bootstrap/cache

# Instalar dependencias de PHP y compilar assets de frontend
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN npm install && npm run build

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Copiar configuración de Apache personalizada
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Copiar y dar permisos al script de entrada
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Exponer el puerto 80
EXPOSE 80

# Ejecutar script de entrada
CMD ["/entrypoint.sh"]
