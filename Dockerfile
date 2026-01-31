# =========================
# 1) Build frontend assets
# =========================
FROM node:20-alpine AS nodebuild
WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm ci

# Copy only what Vite needs
COPY vite.config.* ./
COPY resources ./resources
COPY public ./public
COPY tailwind.config.* postcss.config.* . 2>/dev/null || true

RUN npm run build


# =========================
# 2) PHP + Laravel runtime
# =========================
FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
  git unzip libzip-dev \
  && docker-php-ext-install zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader

# Copy built Vite assets into Laravel public/build
COPY --from=nodebuild /app/public/build /var/www/public/build

# Ensure writable dirs
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache || true

CMD php -S 0.0.0.0:${PORT} -t public
