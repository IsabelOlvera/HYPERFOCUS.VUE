#!/bin/bash

# Reparar permisos por si se reinician
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Esperar que la base de datos esté lista
sleep 5

# Verificar que exista la clave de encriptación
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
