FROM php:7.4-fpm

MAINTAINER Clemente Estrada <mirdrack@gmail.com>

RUN docker-php-ext-install pdo pdo_mysql opcache
COPY docker/config/opcache.ini /usr/local/etc/php/conf.d/opcache.ini