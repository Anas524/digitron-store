FROM php:8.4-cli

# System deps
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev \
    && docker-php-ext-install zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy code
COPY . .

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader

# Render provides PORT, serve public/
CMD php -S 0.0.0.0:${PORT} -t public
