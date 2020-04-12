# PHP Dependencies
FROM composer:1.9 as vendor
COPY database/ database/
COPY composer.json composer.json
COPY composer.lock composer.lock
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

# Application
FROM php:7.4-fpm
RUN docker-php-ext-install pdo pdo_mysql opcache
COPY docker/config/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY . /usr/src/app
COPY --from=vendor /app/vendor/ /usr/src/app/vendor/

LABEL maintainer="Clemente Estrada <mirdrack@gmail.com>"