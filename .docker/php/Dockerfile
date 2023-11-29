# Composer Install (Src : https://stackoverflow.com/a/61015273/8589728 [CC BY-SA 4.0](https://creativecommons.org/licenses/by-sa/4.0/))

FROM composer:lts as builder
WORKDIR /app/
COPY composer.* ./
RUN composer install

# Main Stuff

FROM php:8.2.13-apache

# Copy config
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN mkdir /app

# Install pre-requisits
RUN docker-php-source extract \
    && pecl install xdebug \
    && docker-php-ext-install mysqli \
    && docker-php-ext-enable xdebug mysqli \
    && docker-php-source delete

# Set permissions
RUN chown -R www-data:www-data /app \
    && a2enmod rewrite
