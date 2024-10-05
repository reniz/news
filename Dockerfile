# Use an official PHP image with Apache
FROM php:8.2-apache

# Set working directory in container
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo mbstring exif pcntl bcmath gd \
    && docker-php-ext-install pdo_mysql  # Add this line to install pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files to container
COPY . .

# Set up Apache to serve Laravel
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite

# Expose port 80
EXPOSE 80

# Run Laravel's Artisan commands (e.g., clear cache, config)
RUN composer install
RUN php artisan key:generate

# Start Apache in the foreground
CMD ["apache2-foreground"]
