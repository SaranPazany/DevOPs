FROM php:8.2-fpm

# Install system dependencies
RUN apt update && apt install -y unzip curl git nodejs npm libzip-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Copy Laravel project
COPY ./laravel .

# Remove existing lock file to start fresh
RUN rm -f composer.lock

# Install base Laravel dependencies first
RUN composer install --no-scripts --no-interaction --no-dev

# Now install dev dependencies with compatible versions
RUN composer require pestphp/pest:^3.0 pestphp/pest-plugin-laravel:^3.0 --dev --with-all-dependencies --no-interaction

# Install remaining dev dependencies
RUN composer install --no-scripts --no-interaction

# Dump autoload
RUN composer dump-autoload --optimize

# Initialize PEST
RUN ./vendor/bin/pest --init

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]