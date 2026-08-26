FROM php:7.4-fpm

RUN apt-get update

RUN apt-get install -y libzip-dev zip libpng-dev libjpeg-dev libfreetype6-dev libonig-dev

RUN docker-php-ext-install pdo_mysql mysqli mbstring zip

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

RUN echo "short_open_tag=On" > /usr/local/etc/php/conf.d/custom.ini \
    && echo "output_buffering=On" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "error_reporting=E_ALL & ~E_NOTICE & ~E_WARNING" >> /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html