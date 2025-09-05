#!/bin/bash

# Reaplicar permisos de escritura (por si el contenedor lo requiere)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Esperar por si la base de datos tarda en responder
sleep 5

# Verificar que la clave de aplicación exista
if [ -z "$APP_KEY" ]; then
  echo "ERROR: APP_KEY is not set. Laravel will not run."
  exit 1
fi

# Ejecutar comandos de Laravel
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link

# Iniciar Apache
exec apache2-foreground
