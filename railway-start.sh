#!/bin/bash

# Démarrer PHP-FPM en arrière-plan
php-fpm &

# Attendre que PHP-FPM soit opérationnel
sleep 2

# Démarrer Nginx en premier plan
nginx -g 'daemon off;'