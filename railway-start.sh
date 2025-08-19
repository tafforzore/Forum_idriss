
#!/bin/bash

# Démarrer PHP-FPM en arrière-plan
php-fpm &

# Démarrer Nginx
nginx -g 'daemon off;'