#!/bin/bash

# Démarrer PHP-FPM (port 9000 en interne)
php-fpm -D

# Attendre que PHP-FPM soit prêt
sleep 2

# Démarrer Nginx sur le port 8000
echo "🚀 Application démarrée sur le port 8000"
nginx -g 'daemon off;'