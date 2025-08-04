#!/bin/bash

# Esperar unos segundos por si la base de datos tarda en arrancar
sleep 5

# Ejecutar comandos de Laravel
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link

# Iniciar Apache en primer plano
apache2-foreground
