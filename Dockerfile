FROM php:8.2-fpm

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    nginx

# Configurer et installer les extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql mysqli

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier la configuration Nginx
COPY nginx.conf /etc/nginx/sites-available/default

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de l'application
COPY . .

# Configurer les permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod +x railway-start.sh

# Exposer le port (Railway utilise le port 8000 par défaut)
EXPOSE 8000

# Script de démarrage
CMD ["./railway-start.sh"]