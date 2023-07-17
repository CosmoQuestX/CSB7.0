FROM php:8.1.21-apache

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
