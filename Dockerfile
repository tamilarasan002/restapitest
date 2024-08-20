# Use the official PHP image with Apache
FROM php:8.1-apache

# Install necessary PHP extensions and utilities
RUN apt-get update && \
    apt-get install -y \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        zip \
        unzip \
        git && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy the application code
COPY src/ /var/www/html/

# Copy Composer files
COPY composer.json composer.lock* /var/www/html/

# Set up Composer configurations and install dependencies
RUN composer config repo.packagist composer https://packagist.org && \
    composer config process-timeout 2000 && \
    composer install --no-dev --prefer-dist --no-scripts --no-interaction

# Expose port 80
EXPOSE 80
