FROM php:8.2-fpm

# Mise à jour et installation des dépendances
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl

# Configuration de GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Installation des extensions PHP
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli \
    gd

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définition du répertoire de travail
WORKDIR /var/www/html

# Correction des permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exposition du port
EXPOSE 9000

# Commande de démarrage
CMD ["php-fpm"]