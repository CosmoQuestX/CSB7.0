FROM php:7.4.2-apache

COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN mkdir /app \
    && docker-php-source extract \
    && pecl install xdebug \
    && docker-php-ext-install mysqli \
    && docker-php-ext-enable xdebug mysqli \
    && docker-php-source delete \
    && chown -R www-data:www-data /app \
    && a2enmod rewrite
