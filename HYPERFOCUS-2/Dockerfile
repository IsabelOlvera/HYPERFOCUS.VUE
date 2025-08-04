# Imagen base con PHP 8.3 y Apache
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

# Dar permisos correctos a las carpetas necesarias
RUN chmod -R 775 storage bootstrap/cache && chown -R www-data:www-data storage bootstrap/cache

# Instalar dependencias de PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Instalar dependencias frontend y limpiar assets anteriores
RUN npm install && rm -rf public/build && npm run build

# Verificar que los assets se hayan generado correctamente
RUN if [ ! -d "public/build" ]; then echo "❌ ERROR: Assets no se generaron"; exit 1; fi

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Copiar configuración personalizada de Apache
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Copiar y preparar el script de entrada
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Exponer el puerto 80
EXPOSE 80

# Ejecutar el script de entrada
CMD ["/entrypoint.sh"]
