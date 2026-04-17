# RDM Developments - Production Dockerfile
# Mirrors the proven NRAPA pattern: PHP 8.3 + install-php-extensions + nginx + supervisor.
# PHP 8.4 was causing PDO auth handshake failures against MySQL 8 / MariaDB 11
# (install-php-extensions builds pdo_mysql against a libmysqlclient variant
# that breaks the handshake on 8.4; 8.3 uses mysqlnd cleanly).

FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    nginx \
    supervisor \
    git \
    curl \
    netcat-openbsd \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    icu-dev \
    nodejs \
    npm \
    ca-certificates \
    tzdata

ENV TZ=Africa/Johannesburg
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer update --no-dev --optimize-autoloader --no-interaction --no-scripts --prefer-stable

RUN npm ci && npm run build && rm -rf node_modules

# Publish Filament's JS/CSS into public/ so the admin panel renders with its
# full styling. Without this, the login page loads but /js/filament/* and
# /css/filament/* return 404 and the admin UI is unstyled. Scripts-disabled
# composer install means these are NOT auto-published by the vendor:publish
# post-install hook, so we invoke it explicitly.
RUN php artisan filament:assets

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN mkdir -p /var/log/supervisor /run/nginx /var/log/php \
    && touch /var/log/php/error.log \
    && chown -R www-data:www-data /var/log/php

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
    CMD curl -fsS http://localhost/ > /dev/null || exit 1

ENTRYPOINT ["/entrypoint.sh"]
