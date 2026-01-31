FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
  git unzip libzip-dev \
  && docker-php-ext-install zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache || true

CMD php -S 0.0.0.0:${PORT} -t public
