# Gunakan base image PHP + Apache
FROM php:8.2-apache

# Install dependencies Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project Laravel ke folder container
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Set permission
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Copy Apache config
COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Jalankan Composer install (opsional kalau belum)
RUN composer install

EXPOSE 80
